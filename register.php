<?php
include_once __DIR__ . '/config.php';
$pageVariables = (new \Controller\RegisterController())->doAction();
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 pt-5 pb-5 pl-2 pr-2" role="main">
    <div class="container">
        <div class="row">
            <div class="col-md-6 background-white rounded p-2">
                <?php if (isset($pageVariables['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p><?= $pageVariables['error'] ?></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <h1 class="display-5">Make appointment today!</h1>
                <p class="lead">Registration Form</p>
                <hr class="my-4">
                <form id="registerForm" method="post" action="register.php" novalidate>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="nameLabel">Name</span>
                                    </div>
                                    <input type="text"
                                           class="form-control <?= FormExtensions::printInputValidationResult('name', $pageVariables) ?>"
                                           id="name" name="name" placeholder="Your Name" aria-labelledby="nameLabel"
                                           value="<?= $_POST['name'] ?? '' ?>">
                                    <?= FormExtensions::printValidationResult('name', $pageVariables) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="emailLabel">Email</span>
                                    </div>
                                    <input type="email"
                                           class="form-control <?= FormExtensions::printInputValidationResult('email', $pageVariables) ?>"
                                           id="email" name="email" placeholder="Your Email"
                                           aria-labelledby="emailLabel" value="<?= $_POST['email'] ?? '' ?>">
                                    <?= FormExtensions::printValidationResult('email', $pageVariables) ?>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <input type="password"
                                           class="form-control <?= isset($_POST['passwordConfirm']) ? (isset($pageVariables['password_error']) || isset($pageVariables['passwordConfirm_error']) ? 'is-invalid' : 'is-valid') : '' ?>"
                                           id="passwordConfirm" name="passwordConfirm" placeholder="Confirm Password"
                                           aria-labelledby="passwordLabel"
                                           value="<?= $_POST['passwordConfirm'] ?? '' ?>">
                                    <?php if (isset($_POST['password']) && isset($_POST['passwordConfirm'])): ?>
                                        <div id="validation-password"
                                             class="<?= (isset($pageVariables['password_error']) || isset($pageVariables['passwordConfirm_error'])) ? 'invalid' : 'valid' ?>-feedback"><?= $pageVariables['password_error'] ?? $pageVariables['passwordConfirm_error'] ?? 'Looks good!' ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dobLabel">Date of birth</span>
                                    </div>
                                    <input type="date"
                                           class="form-control <?= FormExtensions::printInputValidationResult('dob', $pageVariables) ?>"
                                           id="dob" name="dob" aria-labelledby="dobLabel"
                                           value="<?= $_POST['dob'] ?? '' ?>" min="<?= date('Y') - 100 ?>-01-01"
                                           max="<?= date('Y-m-d') ?>">
                                    <?= FormExtensions::printValidationResult('dob', $pageVariables) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="col-form-label-sm pr-1" id="genderLabel">Gender:</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="genderMale" name="gender" class="custom-control-input"
                                       aria-labelledby="genderLabel" value="male" checked>
                                <label class="custom-control-label" for="genderMale">Male</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="genderFemale" name="gender"
                                       class="custom-control-input" aria-labelledby="genderLabel" value="female">
                                <label class="custom-control-label" for="genderFemale">Female</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="register">
                    <input type="hidden" id="registerCsrf" name="registerCsrf" value="<?= $pageVariables['csrf'] ?>">
                    <button type="submit" class="btn btn-primary btn-block" id="submit">Create My Account</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
<script src="js/register.js"></script>
</body>
</html>