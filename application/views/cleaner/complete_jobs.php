<style>
/* Container styling for wider desktop view */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
}

@media (min-width: 1200px) {
    .container-fluid {
        max-width: 97% !important;
    }
}

@media (min-width: 1400px) {
    .container-fluid {
        max-width: 98% !important;
    }
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks text-primary"></i>
                        Jobs in Progress
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($jobs)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No jobs are currently in progress.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Host</th>
                                        <th>Scheduled Date</th>
                                        <th>Started At</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($job->title) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($job->description) ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name) ?>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($job->host_phone) ?></small>
                                            </td>
                                            <td>
                                                <?= date('M j, Y', strtotime($job->scheduled_date)) ?>
                                                <?php if ($job->scheduled_time): ?>
                                                    <br><small class="text-muted"><?= date('g:i A', strtotime($job->scheduled_time)) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= date('M j, Y g:i A', strtotime($job->started_at)) ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">In Progress</span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('cleaner/jobs-in-progress/complete/' . $job->id) ?>" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Complete Job
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
