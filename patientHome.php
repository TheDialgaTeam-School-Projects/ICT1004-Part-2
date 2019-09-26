<?php
include_once __DIR__ . '/config.php';

$errorMessage = [];

$db = MysqliExtensions::getDatabaseConnection();
$result = [];
if ($query = MysqliExtensions::query($db, "select ds.doctor_schedule_id,date,start_time, end_time,u.name, v.name, u.email from doctor_schedule ds
       inner join user u on ds.doctor_id = u.user_id
       left join patient_appointment pa on ds.doctor_schedule_id = pa.doctor_schedule_id
       inner join venue v on ds.venue_id = v.venue_id WHERE date = ? and (status is null or status != 'booked')", (isset($_POST['date'])) ? $_POST['date'] : '')) ;
{
    $result = $query->fetch_all(MYSQLI_NUM);
    $query->close();
}

if (isset($_GET['doctor_schedule_id']) && isset($_GET['user_id'])) {
    $query = MysqliExtensions::query($db, 'INSERT INTO patient_appointment(doctor_schedule_id,user_id) VALUES(?,?)', $_GET['doctor_schedule_id'], $_GET['user_id']);
    $emailMessage = sprintf("Hi Dr %s,\n\n You have a new booking under your schedule,you are required to accept or reject the booking in the following link %s.\n\n\nYour regards,\n%s Administrator",
        $_GET['name'], "http://ict1004.ddns.net/AY18/Group14/project2/index.php", SITE_NAME);
    mail($_GET['email'], 'To Doctor' . SITE_NAME, $emailMessage, sprintf('From: %s Administrator <%s>', SITE_NAME, SMTP_EMAIL));

    $success = "You have successfully booked an appointment";
}
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 p-5">
    <div class="container background-white rounded p-2">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p><?= $success ?></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <h1 class="display-5">Make appointment</h1>
                <p class="lead">Kindly apply for an appointment on the date and time that is available.</p>
                <hr class="my-4">
                <form id="loginForm" method="post" action="patientHome.php">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dobLabel">Date </span>
                                    </div>
                                    <input type="date"
                                           class="form-control <?= isset($_POST['date']) ? (isset($errorMessage['date']) ? 'is-invalid' : 'is-valid') : '' ?>"
                                           id="date" name="date" aria-describedby="dobLabel"
                                           value="<?= $_POST['date'] ?? ''; ?>">
                                    <input value="View Appointment" type="submit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <p class="lead">Kindly select an available time-slot on the selected date.</p>
                    <table class="table table-hover table-light table-responsive">
                        <thead>
                        <tr>
                            <th scope="col">Appt Num</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Venue</th>
                            <th scope="col">Select</th>
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
                                <td><a class="btn btn-danger"
                                       href="patientHome.php?action=booking&email=<?= $row[6] ?>&name=<?= $row[4] ?>&user_id=<?= $_SESSION['user_id'] ?>&doctor_schedule_id=<?= $row[0] ?>"
                                       data-toggle="tooltip" data-placement="top" title="Book Session">
                                        <i class="fas fa-calendar-plus"></i>
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