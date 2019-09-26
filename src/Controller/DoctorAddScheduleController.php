<?php

namespace Controller;

use CsrfExtensions;
use DateInterval;
use DateTime;
use Exception;
use MysqliExtensions;

class DoctorAddScheduleController extends AbstractController
{
    protected function indexAction(): array
    {
        $this->authenticationHandler->redirectFromDoctorPage();

        $result = [];

        try {
            $db = MysqliExtensions::getDatabaseConnection();

            if ($query = MysqliExtensions::query($db, 'select venue_id, name from venue')) {
                $result['venue_list'] = $query->fetch_all(MYSQLI_ASSOC);
                $query->close();
            }

            $db->close();
        } catch (Exception $exception) {
            return ['alert' => $exception->getMessage()];
        }

        return $result;
    }

    protected function createAppointmentAction(): array
    {
        $result = $this->indexAction();
        $csrfResult = CsrfExtensions::ValidateCsrfToken('createAppointmentCsrf', $_POST['createAppointmentCsrf']);

        if (count($csrfResult) > 0)
            return array_merge($result, $csrfResult);

        $venue = trim($_POST['venue'] ?? '');
        $startTime = trim($_POST['startTime'] ?? '');
        $date = trim($_POST['date'] ?? '');

        try {
            $db = MysqliExtensions::getDatabaseConnection();
            $validVenueIds = [];

            if ($query = MysqliExtensions::query($db, 'select venue_id from venue')) {
                while ($temp = $query->fetch_assoc()['venue_id'])
                    $validVenueIds[] = $temp;

                $query->close();
            }

            if (!isset($venue) || !in_array($venue, $validVenueIds))
                $result['venue'] = 'Venue is invalid!';

            if (!isset($startTime) || !preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $startTime))
                $result['startTime'] = 'Start time is invalid!';

            /*if (!isset($date) || !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date))
                $result['date'] = 'Date is invalid!';
            elseif (strtotime($date) < time() || strtotime($date) > strtotime(sprintf('%s-%s-%s', date('Y'), date('m') + 1, date('d'))))
                $result['date'] = 'Date is invalid!';
            elseif (date("Y-m-d", strtotime($date)) != $date)
                $result['date'] = 'Date is invalid!';*/

            if (count($result) > 1) {
                $db->close();
                return $result;
            }

            if ($query = MysqliExtensions::query($db, 'select date, start_time from doctor_schedule where doctor_id = ? order by date, start_time', $_SESSION['user_id'])) {
                $temp = $query->fetch_all(MYSQLI_ASSOC);

                foreach ($temp as $row) {
                    if ($row['date'] == $date && $row['start_time'] == $startTime) {
                        $result['alert'] = 'This schedule has already been added. Try again!';
                        $db->close();
                        return $result;
                    }
                }

                $query->close();
            }

            $endTime = DateTime::createFromFormat('H:i:s', $startTime);
            $endTime->add(new DateInterval('PT1H'));
            $endTimeStr = $endTime->format('H:i:s');

            if ($query = MysqliExtensions::query($db, 'insert into doctor_schedule(doctor_id, date, start_time, end_time, venue_id) values (?, ?, ?, ?, ?)', $_SESSION['user_id'], $date, $startTime, $endTimeStr, $venue)) {
                $query->close();
            }

            $db->close();

            header('Location: doctorHome.php?success=' . urlencode('Successfully added a new schedule for booking.'));

            return $result;
        } catch (Exception $exception) {
            return ['alert' => $exception->getMessage()];
        }
    }
}