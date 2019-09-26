<?php
include_once __DIR__ . '/config.php';
$pageVariables = (new \Controller\DoctorAddScheduleController())->doAction();
$pageVariables['navigation'] = 'doctorAddSchedule';
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 pt-5 pb-5 pl-2 pr-2">
    <div class="container">
        <div class="row">
            <div class="col-md-6 background-white rounded p-2">
                <?php if (isset($pageVariables['alert'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p><?= $pageVariables['alert'] ?></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <h1 class="display-5">Doctor Schedule Creation</h1>
                <p class="lead">Add New Schedule Form</p>
                <hr class="my-4">
                <form id="AppointmentForm" method="post" action="doctorAddSchedule.php" novalidate>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="venue">Venue</label>
                                    </div>
                                    <select id="venue" name="venue"
                                            class="custom-select <?= isset($_POST['venue']) ? (isset($pageVariables['venue']) ? 'is-invalid' : 'is-valid') : '' ?>">
                                        <?php foreach ($pageVariables['venue_list'] as $row): ?>
                                            <option value="<?= $row['venue_id'] ?>"><?= $row['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($_POST['venue'])): ?>
                                        <div id="validation-venue"
                                             class="<?= isset($pageVariables['venue']) ? 'invalid' : 'valid' ?>-feedback"><?= $pageVariables['venue'] ?? 'Looks good! (*^▽^*)' ?></div>
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
                                        <span class="input-group-text" id="dateLabel">Date</span>
                                    </div>
                                    <input type="date"
                                           class="form-control <?= isset($_POST['date']) ? (isset($pageVariables['date']) ? 'is-invalid' : 'is-valid') : '' ?>"
                                           id="date" name="date" aria-labelledby="dateLabel"
                                           value="<?= $_POST['date'] ?? sprintf('%s-%s-%s', date('Y'), date('m'), date('d') + 1) ?>"
                                           min="<?= date('Y') ?>-<?= date('m') ?>-<?= date('d') + 1 ?>">
                                    <?php if (isset($_POST['date'])): ?>
                                        <div id="validation-date"
                                             class="<?= isset($pageVariables['date']) ? 'invalid' : 'valid' ?>-feedback"><?= $pageVariables['date'] ?? 'Looks good! (*^▽^*)' ?></div>
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
                                        <label class="input-group-text" for="startTime">Time Available</label>
                                    </div>
                                    <select id="startTime" name="startTime"
                                            class="custom-select <?= isset($_POST['startTime']) ? (isset($pageVariables['startTime']) ? 'is-invalid' : 'is-valid') : '' ?>">
                                        <option value="09:00:00">09:00 - 10:00</option>
                                        <option value="10:00:00">10:00 - 11:00</option>
                                        <option value="11:00:00">11:00 - 12:00</option>
                                        <option value="12:00:00">12:00 - 13:00</option>
                                        <option value="13:00:00">13:00 - 14:00</option>
                                        <option value="14:00:00">14:00 - 15:00</option>
                                        <option value="15:00:00">15:00 - 16:00</option>
                                        <option value="16:00:00">16:00 - 17:00</option>
                                    </select>
                                    <?php if (isset($_POST['startTime'])): ?>
                                        <div id="validation-startTime"
                                             class="<?= isset($pageVariables['startTime']) ? 'invalid' : 'valid' ?>-feedback"><?= $pageVariables['startTime'] ?? 'Looks good! (*^▽^*)' ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="createAppointmentCsrf" name="createAppointmentCsrf"
                           value="<?= CsrfExtensions::GenerateCsrfToken('createAppointmentCsrf') ?>">
                    <input type="hidden" id="action" name="action" value="createAppointment">
                    <button type="submit" class="btn btn-primary btn-block" id="submit">Create My Appointment Slot
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
<script src="js/doctorAddSchedule.js"></script>
</body>
</html>