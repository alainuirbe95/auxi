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
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <strong>Job Disputes:</strong> Some of your completed jobs have been disputed by hosts. Please review the issues reported.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Details</th>
                                        <th>Host Information</th>
                                        <th>Issues Reported</th>
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
                                                <div class="host-info">
                                                    <strong><i class="fas fa-user text-primary"></i> <?= htmlspecialchars($job->host_name) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($job->host_email) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="issues-info">
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
                                                    <?php if ($job->status == 'closed' && $job->dispute_resolution): ?>
                                                        <strong>Payment Amount:</strong><br>
                                                        <span class="text-success font-weight-bold">$<?= number_format($job->payment_amount, 2) ?></span><br>
                                                        <small class="text-muted">(<?= number_format(($job->payment_amount / ($job->final_price ?: $job->accepted_price)) * 100, 1) ?>% of original)</small>
                                                    <?php else: ?>
                                                        <strong>Expected Payment:</strong><br>
                                                        <span class="text-warning">Pending Resolution</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="status-info">
                                                    <?php if ($job->status == 'closed' && $job->dispute_resolution): ?>
                                                        <!-- Dispute Resolved -->
                                                        <span class="badge badge-success badge-lg">
                                                            <i class="fas fa-check-circle"></i> Resolved
                                                        </span><br><br>
                                                        <div class="dispute-resolution-info">
                                                            <small class="text-success">
                                                                <strong>Resolution:</strong> Dispute resolved by moderator<br>
                                                                <strong>Resolved:</strong> <?= date('M j, Y g:i A', strtotime($job->dispute_resolved_at)) ?><br>
                                                                <?php if ($job->dispute_resolution_notes): ?>
                                                                    <strong>Notes:</strong> <?= htmlspecialchars($job->dispute_resolution_notes) ?><br>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>
                                                    <?php else: ?>
                                                        <!-- Under Review -->
                                                        <span class="badge badge-warning badge-lg">
                                                            <i class="fas fa-clock"></i> Under Review
                                                        </span><br><br>
                                                        <small class="text-muted">
                                                            A moderator will review the dispute and determine a fair resolution.
                                                        </small>
                                                    <?php endif; ?>
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
                                            <p class="small">Our moderators review the reported issues and any evidence provided to make a fair decision.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-gavel fa-2x text-warning mb-2"></i>
                                            <h6>Resolution</h6>
                                            <p class="small">You'll be notified of the resolution including your final payment amount based on the severity of issues.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                            <h6>Payment Processing</h6>
                                            <p class="small">Payments are processed within 1-2 business days after resolution.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Tips for Future Jobs:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Always communicate clearly with hosts about any issues or delays</li>
                                <li>Take photos before and after cleaning to document your work</li>
                                <li>Follow the job description and special instructions carefully</li>
                                <li>Report any unexpected issues to the host immediately</li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-info, .host-info, .issues-info, .financial-info, .status-info {
    font-size: 0.9em;
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

.issues-info ul {
    margin-bottom: 0.5rem;
}

.issues-info li {
    margin-bottom: 0.25rem;
}
</style>
