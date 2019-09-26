<?php
$activePage = basename($_SERVER['PHP_SELF'], ".php"); // Gets URL from address bar
?>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        <!-- Extra CSS -->
        <link rel="stylesheet" href="css/extra.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">

        <title><?= SITE_NAME ?></title>
    </head>
    <body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="img/medlogo.jpg" class="d-inline-block align-top navbar-logo" alt="<?= SITE_NAME ?> Logo">
            <?= SITE_NAME ?>
        </a>

        <?php if (isset($_SESSION['user_type'])): ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'patient'): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item <?= ($activePage == 'patientHome') ? 'active' : ''; ?>">
                            <a class="nav-link" href="patientHome.php">Book Appointment</a>
                        </li>
                        <li class="nav-item <?= ($activePage == 'changepassword') ? 'active' : ''; ?>">
                            <a class="nav-link" href="changepassword.php">Change Password</a>
                        </li>
                        <li class="nav-item <?= ($activePage == 'editAppt') ? 'active' : ''; ?>">
                            <a class="nav-link" href="editAppt.php">Edit Booking</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                    <p class="ml-auto"><?= 'Welcome ' . $_SESSION['user_name'] ?></p>
                <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor'): ?>
                    <ul class="navbar-nav">
                        <li class="nav-item <?= $pageVariables['navigation'] == 'doctorHome' ? 'active' : '' ?>">
                            <a class="nav-link" href="doctorHome.php">Doctor
                                Schedule <?= $pageVariables['navigation'] == 'doctorHome' ? '<span class="sr-only">(current)</span>' : '' ?></a>
                        </li>
                        <li class="nav-item <?= $pageVariables['navigation'] == 'doctorAddSchedule' ? 'active' : '' ?>">
                            <a class="nav-link" href="doctorAddSchedule.php">Add New
                                Schedule <?= $pageVariables['navigation'] == 'doctorAddSchedule' ? '<span class="sr-only">(current)</span>' : '' ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                    <div class="ml-auto va-middle"><?= 'Welcome ' . $_SESSION['user_name'] ?></div>
                <?php endif ?>
            </div>
        <?php endif ?>
    </nav>
</header>
<?php
$db2 = MysqliExtensions::getDatabaseConnection();

if ($query2 = MysqliExtensions::query($db2, "select pa.patient_appointment_id,date,start_time, end_time,u.name, v.name, u.email from patient_appointment pa
INNER JOIN doctor_schedule ds ON pa.doctor_schedule_id = ds.doctor_schedule_id
INNER JOIN user u ON pa.user_id = u.user_id
INNER JOIN venue v ON ds.venue_id = v.venue_id
       WHERE ADDTIME(book_time,'0 1:00:00')<= current_timestamp() AND email_sent = false;")) {
    $result2 = $query2->fetch_all(MYSQLI_NUM);
    foreach ($result2 as $row) {
        $emailMessage = sprintf("Hi Patient %s,\n\n This is to inform you of your appointment on %s at %s to %s at %s.you may click on the link to view more %s.\n\n\nYour regards,\n%s Administrator",
            $row[4], $row[1], $row[2], $row[3], $row[5], "http://ict1004.ddns.net/AY18/Group14/project2/index.php", SITE_NAME);
        mail($row[6], 'To Patient' . SITE_NAME, $emailMessage, sprintf('From: %s Administrator <%s>', SITE_NAME, SMTP_EMAIL));

        MysqliExtensions::query($db2, "UPDATE patient_appointment
            SET email_sent ='1'
            WHERE patient_appointment_id = ?", $row[0]);
    }
}

$db2->close();