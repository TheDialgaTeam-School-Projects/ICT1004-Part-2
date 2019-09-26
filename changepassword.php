<?php
include_once __DIR__ . '/config.php';

$incorrect = null;
$match = null;
$success = null;

if (isset($_POST['action']) && $_POST['action'] == 'newPassword') {
    $password = $_POST['password'];
    $newpassword = $_POST['newpassword'];
    $confirmnewpassword = $_POST['cnewpassword'];
    $db = MysqliExtensions::getDatabaseConnection();
    $result = MysqliExtensions::query($db, 'select password from user where user_id = ?', $_SESSION['user_id']);
    if (!password_verify($password, $result->fetch_assoc()['password'])) {
        $incorrect = "You entered an incorrect password";
    } else {
        if ($newpassword == $confirmnewpassword) {
            $sql = MysqliExtensions::query($db, "UPDATE user SET password=? where user_id = ?", password_hash($newpassword, PASSWORD_BCRYPT), $_SESSION['user_id']);
            $success = "Congratulations You have successfully changed your password";
        } else {
            $match = "The new password and confirm new password fields must be the same";
        }
    }

    $db->close();
}



?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 p-5">
    <div class="container  background-white rounded p-2">
        <?php if (isset($incorrect)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p><?= $incorrect ?></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (isset($exist)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p><?= $exist ?></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (isset($match)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p><?= $match ?></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p><?= $success ?></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="display-5">Change Password</h1>
                <p class="lead">Remember to change your password every 180 days.</p>
                <hr class="my-4">
                <form id="loginForm" method="post" action="changepassword.php">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="passwordLabel">Old Password</span>
                                    </div>
                                    <input type="password"
                                           class="form-control"
                                           id="password" name="password" placeholder="Old Password"
                                           aria-describedby="passwordLabel">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="passwordLabel1">New Password</span>
                                    </div>
                                    <input type="password"
                                           class="form-control"
                                           id="newpassword" name="newpassword" placeholder="New Password"
                                           aria-describedby="passwordLabel1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="passwordLabel2">Confirm Password</span>
                                    </div>
                                    <input type="password"
                                           class="form-control"
                                           id="cnewpassword" name="cnewpassword" placeholder="Confirm New Password"
                                           aria-describedby="passwordLabel2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="registerCsrf" name="registerCsrf"
                           value="<?= CsrfExtensions::GenerateCsrfToken('registerCsrf') ?>">
                    <input type="hidden" id="action" name="action" value="newPassword">
                    <button type="submit" class="btn btn-primary btn-block" id="submit">Save my new Password</button>
                </form>
            </div>
        </div>
        <hr class="my-4">
        <p class=".text-danger text-center">Tip : It's a good idea to use a strong password that you don't use
            elsewhere</p>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
<script src="js/login.js"></script>
</body>
</html>