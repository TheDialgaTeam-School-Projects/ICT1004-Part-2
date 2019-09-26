<?php
include_once __DIR__ . '/config.php';
$pageVariables = (new \Controller\DoctorHomeController())->doAction();
$index = 1;
$doctorHomeCsrf = CsrfExtensions::GenerateCsrfToken('doctorHomeCsrf');
?>
<!doctype html>
<html lang="en">
<?php include HEADER_INCLUDES ?>
<section class="background-cover2 pt-5 pb-5 pl-2 pr-2">
    <div class="container background-white rounded p-2">
        <?php if (isset($pageVariables['alert'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p><?= $pageVariables['alert'] ?></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (isset($pageVariables['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p><?= $pageVariables['success'] ?></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <h1 class="display-5">Current Doctor Schedule</h1>
        <hr class="my-2">
        <div class="row">
            <div class="col">
                <div class="float-right">
                    <a class="btn btn-primary" href="doctorAddSchedule.php">
                        <i class="fas fa-plus"></i> Add New Schedule
                    </a>
                </div>
            </div>
        </div>
        <div class="row pt-2 pb-2">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Venue</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pageVariables['list'] as $row): ?>
                            <tr>
                                <th scope="row"><?= $index ?></th>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['date'] ?></td>
                                <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>
                                <td>
                                    <?php if (time() > $row['start_time_unix']): ?>
                                        <span class="badge badge-pill badge-success">Session Over</span>
                                    <?php else: ?>
                                        <?php if (!isset($row['book_time']) || !isset($row['status']) || $row['status'] == 'rejected'): ?>
                                            <span class="badge badge-pill badge-success">Available</span>
                                        <?php elseif ($row['status'] == 'booked' && time() >= $row['book_time'] + 60 * 60): ?>
                                            <span class="badge badge-pill badge-danger">Booked</span>
                                        <?php else: ?>
                                            <span class="badge badge-pill badge-warning">Pending <?= sprintf('%.2f', ($row['book_time'] + 60 * 60 - time()) / 60) ?> Minutes</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (time() <= $row['start_time_unix']): ?>
                                        <?php if (!isset($row['book_time']) || !isset($row['status']) || $row['status'] == 'rejected'): ?>
                                            <a class="btn btn-danger"
                                               href="doctorHome.php?action=delete&doctor_id=<?= $_SESSION['user_id'] ?>&doctor_schedule_id=<?= $row['doctor_schedule_id'] ?>&doctorHomeCsrf=<?= $doctorHomeCsrf ?>"
                                               data-toggle="tooltip" data-placement="top" title="Delete Session">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        <?php elseif ($row['status'] == 'booked' && time() > $row['book_time'] + 60 * 60): ?>
                                            <a class="btn btn-danger"
                                               href="doctorHome.php?action=reject&doctor_id=<?= $_SESSION['user_id'] ?>&patient_appointment_id=<?= $row['patient_appointment_id'] ?>&doctorHomeCsrf=<?= $doctorHomeCsrf ?>"
                                               data-toggle="tooltip" data-placement="top" title="Reject Appointment">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <li class="page-item <?= $pageVariables['page'] != 1 ? '' : 'disabled' ?>">
                            <a class="page-link" href="doctorHome.php?page=<?= $pageVariables['page'] - 1 ?>"
                               tabindex="-1">Previous</a>
                        </li>
                        <?php for ($i = 0; $i < $pageVariables['maxPage']; $i++): ?>
                            <?php if ($pageVariables['page'] == $i + 1): ?>
                                <li class="page-item active">
                                    <a class="page-link" href="doctorHome.php?page=<?= $i + 1 ?>"><?= $i + 1 ?>
                                        <span class="sr-only">(current)</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item">
                                    <a class="page-link" href="doctorHome.php?page=<?= $i + 1 ?>"><?= $i + 1 ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <li class="page-item <?= $pageVariables['page'] != $pageVariables['maxPage'] ? '' : 'disabled' ?>">
                            <a class="page-link" href="doctorHome.php?page=<?= $pageVariables['page'] + 1 ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
<?php include FOOTER_INCLUDES ?>
</body>
</html>