<?php

// SITE NAME
define('SITE_NAME', 'HealthCare+ Booking');

// MYSQL CONFIGURATION
define('MYSQL_HOST', '161.117.122.252');
define('MYSQL_USERNAME', 'group14');
define('MYSQL_PASSWORD', 'VXxpEAmjwLJdgzI1');
define('MYSQL_DATABASE', 'group14');
define('MYSQL_PORT', 3306);

define('MYSQL_CA_CERT', __DIR__ . '/mysql_cert/ca-cert.pem');
define('MYSQL_CLIENT_CERT', __DIR__ . '/mysql_cert/client-cert.pem');
define('MYSQL_CLIENT_KEY', __DIR__ . '/mysql_cert/client-key.pem');

// SMTP CONFIGURATION
define('SMTP_EMAIL', 'Ict1004001@gmail.com');

// INCLUDES DIRECTORY
define('HEADER_INCLUDES', __DIR__ . '/includes/header.inc.php');
define('FOOTER_INCLUDES', __DIR__ . '/includes/footer.inc.php');

// USER DEFINED CONSTANTS
define('HOME_PAGE', 'index.php');
define('PATIENT_HOME_PAGE', 'patientHome.php');
define('DOCTOR_HOME_PAGE', 'doctorHome.php');

define('CSRF_ERROR', 'Invalid CSRF checksum. Either you have submit a protected form request in another site or you have resubmitted the form by refreshing the page.');

// WEBSITE SESSION CHECKS
if (session_status() === PHP_SESSION_NONE)
    session_start();

if (session_status() === PHP_SESSION_NONE)
    die('Unable to start a new session.');

/**
 * PHP Includes: THESE ARE ESSENTIALS FOR THE WEBSITE TO RUN.
 * PLEASE DO NOT REMOVE ANYTHING BELOW.
 */
$files = getDirContents(__DIR__ . '/src');

foreach ($files as $index => $file) {
    if (is_file($file))
        require_once $file;
}

function getDirContents($dir, &$results = array())
{
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}
