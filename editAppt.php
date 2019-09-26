<?php
include_once __DIR__ . '/config.php';

$errorMessage = [];

$db = MysqliExtensions::getDatabaseConnection();
$result = [];

if (isset($_GET['action']) && $_GET['action'] == 'booking') {
    if ($query = MysqliExtensions::query($db, "DELETE FROM patient_appointment WHERE user_id = ? AND doctor_schedule_id = ?", $_SESSION['user_id'], $_GET['appointmentid'])) {
        $query->close();
    }

    $success = "You have successfully deleted an appointment";
}

if ($query = MysqliExtensions::query($db, "select ds.doctor_schedule_id,ds.date,ds.start_time,ds.end_time,u.name,v.name,pa.status,UNIX_TIMESTAMP(book_time) as 'book_time'
from doctor_schedule ds
inner join user u on ds.doctor_id = u.user_id
left join patient_appointment pa on ds.doctor_schedule_id = pa.doctor_schedule_id
inner join venue v on ds.venue_id = v.venue_id
where pa.user_id = ?", $_SESSION['user_id'])) {
    $result = $query->fetch_all(MYSQLI_NUM);
    $query->close();
}

$db->close();
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>

<section class="background-cover2 p-5">
    <div class="container background-white rounded p-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p><?= $success ?></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <h1 class="display-5">Edit appointment</h1>
                <p class="lead"><b>View you current appointments/edit your appointments.</b></p>
                <form id="loginForm" method="post" action="index.php">

                    <hr class="my-4">
                    <p class="lead">Kindly select an available time-slot on the selected date.</p>
                    <table class="table table-hover table-light table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Venue</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <th scope="row"><?= $row[0] ?></th>
                                <td><?= $row[1] ?></td>
                                <td><?= $row[2] ?> to <?= $row[3] ?></td>
                                <td><?= $row[4] ?></td>
                                <td><?= $row[5] ?></td>
                                <td><?= $row[6] ?></td>
                                <td>
                                    <?php if (time() <= $row[7] + 60 * 60): ?>
                                    <a class="btn btn-danger"
                                       href="editAppt.php?action=booking&appointmentid=<?= $row[0] ?>&userid=<?= $_SESSION['user_id'] ?>"
                                       data-toggle="tooltip" data-placement="top" title="Delete Session">
                                        <i class="far fa-trash-alt"></i>
                                        <?php endif; ?>
                                    </a></td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
<script src="js/login.js"></script>
</body>
</html>