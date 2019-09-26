<?php

namespace Controller;

use CsrfExtensions;
use Exception;
use MysqliExtensions;

class DoctorHomeController extends AbstractController
{
    protected function indexAction(): array
    {
        $this->authenticationHandler->redirectFromDoctorPage();

        $listCount = null;
        $list = null;
        $page = 1;
        $maxPage = 1;
        $alert = null;
        $success = null;

        if (isset($_GET['page']) && preg_match('/^\d+$/', $_GET['page']))
            $page = $_GET['page'];

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, "
select count(*)
from doctor_schedule ds
inner join user u on ds.doctor_id = u.user_id
left join patient_appointment pa on ds.doctor_schedule_id = pa.doctor_schedule_id
inner join venue v on ds.venue_id = v.venue_id
where u.user_id = ? and date >= curdate() and (status is null or status != 'rejected' or (status = 'rejected' and (select count(*) from patient_appointment where patient_appointment.doctor_schedule_id = pa.doctor_schedule_id) = 1))", $_SESSION['user_id'])) {
                $listCount = $query->fetch_assoc()['count(*)'];
                $query->close();
            }

            $maxPage = intdiv($listCount - 1, 10) + 1;

            if ($page > $maxPage)
                $page = $maxPage;
            else if ($page < 1)
                $page = 1;

            if ($query = MysqliExtensions::query($db, "
select ds.doctor_schedule_id, v.name, date, start_time, end_time, UNIX_TIMESTAMP(TIMESTAMP(date, start_time)) as start_time_unix, UNIX_TIMESTAMP(book_time) as 'book_time', status, patient_appointment_id
from doctor_schedule ds
       inner join user u on ds.doctor_id = u.user_id
       left join patient_appointment pa on ds.doctor_schedule_id = pa.doctor_schedule_id
       inner join venue v on ds.venue_id = v.venue_id
where u.user_id = ? and date >= curdate() and (status is null or status != 'rejected' or (status = 'rejected' and (select count(*) from patient_appointment where patient_appointment.doctor_schedule_id = pa.doctor_schedule_id) = 1))
order by v.name, date, start_time
limit ?, 10", $_SESSION['user_id'], ($page - 1) * 10)) {
                $list = $query->fetch_all(MYSQLI_ASSOC);
                $query->close();
            }
        } catch (Exception $exception) {
            $alert = $exception->getMessage();
        } finally {
            if (isset($db))
                $db->close();
        }

        if (isset($_GET['success']))
            $success = $_GET['success'];

        return [
            'navigation' => 'doctorHome',
            'list' => $list,
            'page' => $page,
            'maxPage' => $maxPage,
            'alert' => $alert,
            'success' => $success,
        ];
    }

    protected function deleteAction(): array
    {
        $result = $this->indexAction();
        $csrfResult = CsrfExtensions::ValidateCsrfToken('doctorHomeCsrf', $_GET['doctorHomeCsrf']);

        if (count($csrfResult) > 0)
            return array_merge($result, $csrfResult);

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'select count(*) from doctor_schedule where doctor_schedule_id = ? and doctor_id = ?', $_GET['doctor_schedule_id'], $_GET['doctor_id'])) {
                if ($query->fetch_assoc()['count(*)'] == 0) {
                    $result['alert'] = 'Unable to delete as schedule does not exist!';

                    $query->close();
                    $db->close();

                    return $result;
                }

                $query->close();
            }

            if ($query = MysqliExtensions::query($db, 'delete from doctor_schedule where doctor_schedule_id = ? and doctor_id = ?', $_GET['doctor_schedule_id'], $_GET['doctor_id'])) {
                $query->close();
            }

            $result = $this->indexAction();
            $result['success'] = 'Successfully deleted the schedule from the list.';

            $db->close();

            return $result;
        } catch (Exception $exception) {
            return ['alert' => $exception->getMessage()];
        }
    }

    protected function rejectAction(): array
    {
        $result = $this->indexAction();
        $csrfResult = CsrfExtensions::ValidateCsrfToken('doctorHomeCsrf', $_GET['doctorHomeCsrf']);

        if (count($csrfResult) > 0)
            return array_merge($result, $csrfResult);

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, '
select count(*) from patient_appointment
inner join doctor_schedule ds on patient_appointment.doctor_schedule_id = ds.doctor_schedule_id
where patient_appointment_id = ? and doctor_id = ?', $_GET['patient_appointment_id'], $_GET['doctor_id'])) {
                if ($query->fetch_assoc()['count(*)'] == 0) {
                    $result['alert'] = 'Unable to reject this session as it does not exist!';

                    $query->close();
                    $db->close();

                    return $result;
                }

                $query->close();
            }

            if ($query = MysqliExtensions::query($db, "update patient_appointment set status = 'rejected' where patient_appointment_id = ?", $_GET['patient_appointment_id'])) {
                $query->close();
            }

            $result = $this->indexAction();
            $result['success'] = 'Successfully rejected the session from the list.';

            $db->close();

            return $result;
        } catch (Exception $exception) {
            return ['alert' => $exception->getMessage()];
        }
    }
}