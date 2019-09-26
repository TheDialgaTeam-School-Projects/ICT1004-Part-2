<?php
include_once __DIR__ . '/config.php';

$errorMessage = [];

if (!isset($_POST['venue']) || $_POST['venue'] < 1 || $_POST['venue'] > 22)
    $errorMessage['venue'] = 'Venue is invalid!';

if (!isset($_POST['time']) || $_POST['time'] < 1 || $_POST['time'] > 8)
    $errorMessage['time'] = 'Time is invalid!';


if (!isset($_POST['date']) || !preg_match('/^\d+-\d+-\d+$/', $_POST['date']))
    $errorMessage['date'] = 'Select a proper Date!';

$result = CsrfExtensions::ValidateCsrfToken('doctorappointmentCsrf', $_POST['doctorappointmentCsrf']);
$errorMessage = array_merge($errorMessage, $result);

if (count($errorMessage) === 0) {
    try {
        $db = MysqliExtensions::getDatabaseConnection();

        if ($query = MysqliExtensions::query($db, 'insert into doctor_schedule(venue_id, start_time, date, doctor_id) VALUES (?, ?, ?, ?)', (int)$_POST['venue'], (int)$_POST['time'], $_POST['date'], $_SESSION['user_id'])) {
            $query->close();
        }
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }

}
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<body>
<section class="background-cover2 p-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="display-5">Congratulations</h1>
                <p class="lead">You have successfully created your booking appt</p>
                <hr class="my-4">
                <a type="button" class="btn btn-primary" href="index.php">Back to Home Page</a>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
</body>
</html>
