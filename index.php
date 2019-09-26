<?php
include_once __DIR__ . '/config.php';
$pageVariables = (new \Controller\LoginController())->doAction();
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 pt-5 pb-5 pl-2 pr-2" role="main">
    <div class="container">
        <div class="row">
            <div class="col-md-6 background-white p-2">
                <?php if (isset($pageVariables['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p><?= $pageVariables['error'] ?></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <h1 class="display-5">Make appointment today!</h1>
                <p class="lead">Please login to make an appointment.</p>
                <hr class="my-4">
                <form id="loginForm" method="post" action="index.php" novalidate>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="usernameLabel">Username</span>
                                    </div>
                                    <input type="text"
                                           class="form-control <?= FormExtensions::printInputValidationResult('username', $pageVariables) ?>"
                                           id="username" name="username" placeholder="Username"
                                           aria-labelledby="usernameLabel" value="<?= $_POST['username'] ?? '' ?>">
                                    <?= FormExtensions::printValidationResult('username', $pageVariables) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="passwordLabel">Password</span>
                                    </div>
                                    <input type="password"
                                           class="form-control <?= FormExtensions::printInputValidationResult('password', $pageVariables) ?>"
                                           id="password" name="password" placeholder="Password"
                                           aria-labelledby="passwordLabel" value="<?= $_POST['password'] ?? '' ?>">
                                    <?= FormExtensions::printValidationResult('password', $pageVariables) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="login">
                    <input type="hidden" id="loginCsrf" name="loginCsrf" value="<?= $pageVariables['csrf'] ?>">
                    <button type="submit" class="btn btn-primary" id="submit">Login</button>
                    <a class="btn btn-primary" href="register.php">Register</a>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
<script src="js/login.js"></script>
</body>
</html>