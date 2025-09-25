<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        My Disputed Jobs
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning"><?= count($disputed_jobs) ?> Disputed</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($disputed_jobs)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>No Disputed Jobs!</strong> All your jobs are currently resolved.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Dispute Status:</strong> Your disputed jobs are under review by our moderators. You will be notified once a resolution is reached.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Details</th>
                                        <th>Cleaner Information</th>
                                        <th>Dispute Information</th>
                                        <th>Financial Impact</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($disputed_jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <div class="job-info">
                                                    <strong><?= htmlspecialchars($job->title) ?></strong><br>
                                                    <small class="text-muted">Job ID: #<?= $job->id ?></small><br>
                                                    <small class="text-muted">Scheduled: <?= date('M j, Y', strtotime($job->scheduled_date)) ?> at <?= date('g:i A', strtotime($job->scheduled_time)) ?></small><br>
                                                    <small class="text-muted">Address: <?= htmlspecialchars($job->address) ?>, <?= htmlspecialchars($job->city) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="cleaner-info">
                                                    <strong><i class="fas fa-broom text-success"></i> <?= htmlspecialchars($job->cleaner_name) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($job->cleaner_email) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dispute-info">
                                                    <strong>Issues Reported:</strong><br>
                                                    <?php
                                                    // Parse dispute reason to show only issues (not notes)
                                                    $dispute_lines = explode("\n", $job->dispute_reason);
                                                    $issues = array_filter($dispute_lines, function($line) {
                                                        return !empty(trim($line)) && !str_starts_with(trim($line), 'Additional Notes:');
                                                    });
                                                    ?>
                                                    <ul class="mb-2">
                                                        <?php foreach ($issues as $issue): ?>
                                                            <li><small><?= htmlspecialchars(trim($issue)) ?></small></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <small class="text-muted">
                                                        <strong>Disputed:</strong> <?= date('M j, Y g:i A', strtotime($job->disputed_at)) ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="financial-info">
                                                    <strong>Original Amount:</strong><br>
                                                    <span class="text-primary font-weight-bold">$<?= number_format($job->final_price ?: $job->accepted_price, 2) ?></span><br><br>
                                                    <strong>Expected Refund:</strong><br>
                                                    <span class="text-warning">Pending Resolution</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="status-info">
                                                    <span class="badge badge-warning badge-lg">
                                                        <i class="fas fa-clock"></i> Under Review
                                                    </span><br><br>
                                                    <small class="text-muted">
                                                        A moderator will review your dispute and determine a fair resolution.
                                                    </small>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h6><i class="fas fa-question-circle"></i> What Happens Next?</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-search fa-2x text-primary mb-2"></i>
                                            <h6>Review Process</h6>
                                            <p class="small">Our moderators review all evidence and documentation from both parties to make a fair decision.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-gavel fa-2x text-warning mb-2"></i>
                                            <h6>Resolution</h6>
                                            <p class="small">You'll be notified of the resolution including any refund amount based on the severity of issues.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                            <h6>Payment Processing</h6>
                                            <p class="small">Refunds are processed within 1-2 business days after resolution.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-info, .cleaner-info, .dispute-info, .financial-info, .status-info {
    font-size: 0.9em;
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

.dispute-info ul {
    margin-bottom: 0.5rem;
}

.dispute-info li {
    margin-bottom: 0.25rem;
}
</style>
