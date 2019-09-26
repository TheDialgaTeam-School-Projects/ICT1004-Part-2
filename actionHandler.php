<?php
include_once __DIR__ . '/config.php';
$pageVariables = (new \Controller\ActionHandlerController())->doAction();
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 pt-5 pb-5 pl-2 pr-2">
    <div class="container">
        <div class="row">
            <div class="col-md-6 background-white p-2">
                <h1 class="display-5"><?= $pageVariables['header'] ?></h1>
                <p class="lead"><?= $pageVariables['content'] ?></p>
                <hr class="my-4">
                <a class="btn btn-primary" href="index.php">Back to Home Page</a>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
</body>
</html>