<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Recalled Jobs Management
                    </h2>
                    <p class="page-subtitle">Review and manage all recalled jobs from hosts - Take necessary action and mark as settled</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $total_recalls; ?></h3>
                    <p class="card-label">Total Recalls</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $pending_review; ?></h3>
                    <p class="card-label">Pending Review</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $under_investigation; ?></h3>
                    <p class="card-label">Under Investigation</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $resolved; ?></h3>
                    <p class="card-label">Resolved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter text-info me-2"></i>
                        Filters & Search
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body" id="filterBody">
                    <form method="GET" action="<?php echo base_url('admin/recalled-jobs'); ?>" id="filterForm">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Reason</label>
                                <select class="form-select" name="reason">
                                    <option value="">All Reasons</option>
                                    <option value="poor_quality" <?php echo ($filters['reason'] ?? '') === 'poor_quality' ? 'selected' : ''; ?>>Poor Quality Work</option>
                                    <option value="incomplete_service" <?php echo ($filters['reason'] ?? '') === 'incomplete_service' ? 'selected' : ''; ?>>Incomplete Service</option>
                                    <option value="damage_caused" <?php echo ($filters['reason'] ?? '') === 'damage_caused' ? 'selected' : ''; ?>>Damage to Property</option>
                                    <option value="unprofessional_behavior" <?php echo ($filters['reason'] ?? '') === 'unprofessional_behavior' ? 'selected' : ''; ?>>Unprofessional Behavior</option>
                                    <option value="safety_concerns" <?php echo ($filters['reason'] ?? '') === 'safety_concerns' ? 'selected' : ''; ?>>Safety Concerns</option>
                                    <option value="contract_violation" <?php echo ($filters['reason'] ?? '') === 'contract_violation' ? 'selected' : ''; ?>>Contract Violation</option>
                                    <option value="other" <?php echo ($filters['reason'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Severity</label>
                                <select class="form-select" name="severity">
                                    <option value="">All Severities</option>
                                    <option value="low" <?php echo ($filters['severity'] ?? '') === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo ($filters['severity'] ?? '') === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo ($filters['severity'] ?? '') === 'high' ? 'selected' : ''; ?>>High</option>
                                    <option value="critical" <?php echo ($filters['severity'] ?? '') === 'critical' ? 'selected' : ''; ?>>Critical</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="recall_status">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?php echo ($filters['recall_status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="under_investigation" <?php echo ($filters['recall_status'] ?? '') === 'under_investigation' ? 'selected' : ''; ?>>Under Investigation</option>
                                    <option value="resolved" <?php echo ($filters['recall_status'] ?? '') === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Search Jobs</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           name="search"
                                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                           placeholder="Search by title, host, or cleaner name">
                                </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Sort By</label>
                                <select class="form-select" name="sort">
                                    <option value="recalled_at" <?php echo ($filters['sort'] ?? '') === 'recalled_at' ? 'selected' : ''; ?>>Recall Date</option>
                                    <option value="title" <?php echo ($filters['sort'] ?? '') === 'title' ? 'selected' : ''; ?>>Job Title</option>
                                    <option value="severity" <?php echo ($filters['sort'] ?? '') === 'severity' ? 'selected' : ''; ?>>Severity</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter me-1"></i> Apply Filters
                                </button>
                                <a href="<?php echo base_url('host/recalled-jobs'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Clear Filters
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recalled Jobs List -->
    <div class="row">
        <div class="col-12">
            <div class="jobs-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-info me-2"></i>
                        Recalled Jobs
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recalled_jobs)): ?>
                        <div class="recalled-jobs-list">
                            <?php foreach ($recalled_jobs as $job): ?>
                                <div class="recalled-job-item">
                                    <div class="job-header">
                                        <div class="job-info">
                                            <h6 class="job-title"><?php echo htmlspecialchars($job->title); ?></h6>
                                            <div class="job-meta">
                                                <span class="host-info">
                                                    <i class="fas fa-home me-1"></i>
                                                    Host: <?php echo htmlspecialchars($job->host_name); ?>
                                                </span>
                                                <span class="cleaner-info">
                                                    <i class="fas fa-user me-1"></i>
                                                    Cleaner: <?php echo htmlspecialchars($job->cleaner_name); ?>
                                                </span>
                                                <span class="recall-date">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Recalled: <?php echo !empty($job->recalled_at) ? date('M j, Y g:i A', strtotime($job->recalled_at)) : date('M j, Y g:i A', strtotime($job->updated_at)); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="job-status">
                                            <span class="status-badge status-<?php echo $job->recall_status ?? 'pending'; ?>">
                                                <?php echo ucfirst($job->recall_status ?? 'pending'); ?>
                                            </span>
                                        </div>
                                        <div class="job-toggle">
                                            <i class="fas fa-chevron-down toggle-icon"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Expandable Job Details -->
                                    <div class="job-details" id="job-details-<?php echo $job->id; ?>" style="display: none;">
                                        <div class="details-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Job Information</h6>
                                                    <div class="detail-item">
                                                        <label>Description:</label>
                                                        <p><?php echo htmlspecialchars($job->description); ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Full Address:</label>
                                                        <p><?php 
                                                            $full_address = $job->address . ', ' . $job->city . ', ' . $job->state;
                                                            if (!empty($job->zip)) {
                                                                $full_address .= ' ' . $job->zip;
                                                            }
                                                            echo htmlspecialchars($full_address);
                                                        ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Scheduled:</label>
                                                        <p><?php echo date('M j, Y g:i A', strtotime($job->scheduled_date . ' ' . $job->scheduled_time)); ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Completed:</label>
                                                        <p><?php echo $job->completed_at ? date('M j, Y g:i A', strtotime($job->completed_at)) : 'N/A'; ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Duration:</label>
                                                        <p><?php echo htmlspecialchars($job->estimated_duration); ?> hours</p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Rooms:</label>
                                                        <p><?php 
                                                            $rooms = is_string($job->rooms) ? json_decode($job->rooms, true) : $job->rooms;
                                                            echo is_array($rooms) ? htmlspecialchars(implode(', ', $rooms)) : htmlspecialchars($job->rooms);
                                                        ?></p>
                                                    </div>
                                                    <?php if (!empty($job->extras)): ?>
                                                        <div class="detail-item">
                                                            <label>Extras:</label>
                                                            <p><?php 
                                                                $extras = is_string($job->extras) ? json_decode($job->extras, true) : $job->extras;
                                                                echo is_array($extras) ? htmlspecialchars(implode(', ', $extras)) : htmlspecialchars($job->extras);
                                                            ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="detail-item">
                                                        <label>Pets:</label>
                                                        <p><?php echo $job->pets ? 'Yes' : 'No'; ?></p>
                                                    </div>
                                                    <?php if (!empty($job->special_instructions)): ?>
                                                        <div class="detail-item">
                                                            <label>Special Instructions:</label>
                                                            <p><?php echo nl2br(htmlspecialchars($job->special_instructions)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Payment Information</h6>
                                                    <div class="detail-item">
                                                        <label>Suggested Price:</label>
                                                        <p>$<?php echo number_format($job->suggested_price, 2); ?></p>
                                                    </div>
                                                    <?php if ($job->accepted_price): ?>
                                                        <div class="detail-item">
                                                            <label>Accepted Price:</label>
                                                            <p>$<?php echo number_format($job->accepted_price, 2); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($job->final_price): ?>
                                                        <div class="detail-item">
                                                            <label>Final Price:</label>
                                                            <p>$<?php echo number_format($job->final_price, 2); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="detail-item">
                                                        <label>Payment Released:</label>
                                                        <p><?php echo $job->payment_released_at ? date('M j, Y g:i A', strtotime($job->payment_released_at)) : 'Not released'; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Host Information</h6>
                                                    <div class="detail-item">
                                                        <label>Host Name:</label>
                                                        <p><?php echo htmlspecialchars($job->host_name); ?></p>
                                                    </div>
                                                    <?php if (!empty($job->host_phone)): ?>
                                                        <div class="detail-item">
                                                            <label>Host Phone:</label>
                                                            <p>
                                                                <i class="fas fa-phone text-primary me-1"></i>
                                                                <a href="tel:<?php echo htmlspecialchars($job->host_phone); ?>" class="text-decoration-none">
                                                                    <?php echo htmlspecialchars($job->host_phone); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job->host_email)): ?>
                                                        <div class="detail-item">
                                                            <label>Host Email:</label>
                                                            <p>
                                                                <i class="fas fa-envelope text-primary me-1"></i>
                                                                <a href="mailto:<?php echo htmlspecialchars($job->host_email); ?>" class="text-decoration-none">
                                                                    <?php echo htmlspecialchars($job->host_email); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Cleaner Information</h6>
                                                    <div class="detail-item">
                                                        <label>Cleaner Name:</label>
                                                        <p><?php echo htmlspecialchars($job->cleaner_name); ?></p>
                                                    </div>
                                                    <?php if (!empty($job->cleaner_phone)): ?>
                                                        <div class="detail-item">
                                                            <label>Cleaner Phone:</label>
                                                            <p>
                                                                <i class="fas fa-phone text-success me-1"></i>
                                                                <a href="tel:<?php echo htmlspecialchars($job->cleaner_phone); ?>" class="text-decoration-none">
                                                                    <?php echo htmlspecialchars($job->cleaner_phone); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job->cleaner_email)): ?>
                                                        <div class="detail-item">
                                                            <label>Cleaner Email:</label>
                                                            <p>
                                                                <i class="fas fa-envelope text-success me-1"></i>
                                                                <a href="mailto:<?php echo htmlspecialchars($job->cleaner_email); ?>" class="text-decoration-none">
                                                                    <?php echo htmlspecialchars($job->cleaner_email); ?>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job->completion_notes)): ?>
                                                        <div class="detail-item">
                                                            <label>Completion Notes:</label>
                                                            <p><?php echo nl2br(htmlspecialchars($job->completion_notes)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h6 class="details-title">Recall Information</h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php if (!empty($job->recall_reason)): ?>
                                                        <div class="detail-item">
                                                            <label>Reason:</label>
                                                            <p><?php echo ucfirst(str_replace('_', ' ', $job->recall_reason)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($job->recall_severity)): ?>
                                                        <div class="detail-item">
                                                            <label>Severity:</label>
                                                            <p><span class="severity-badge severity-<?php echo $job->recall_severity; ?>">
                                                                <?php echo ucfirst($job->recall_severity); ?>
                                                            </span></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($job->desired_resolution)): ?>
                                                        <div class="detail-item">
                                                            <label>Desired Resolution:</label>
                                                            <p><?php echo ucfirst(str_replace('_', ' ', $job->desired_resolution)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (empty($job->recall_reason) && empty($job->recall_severity) && empty($job->desired_resolution)): ?>
                                                        <div class="detail-item">
                                                            <p class="text-muted">
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                Recall details are stored in admin notifications.
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php if (!empty($job->recalled_at)): ?>
                                                        <div class="detail-item">
                                                            <label>Recalled At:</label>
                                                            <p><?php echo date('M j, Y g:i A', strtotime($job->recalled_at)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="detail-item">
                                                        <label>Recall Status:</label>
                                                        <p><span class="status-badge status-<?php echo $job->recall_status ?? 'pending'; ?>">
                                                            <?php echo ucfirst(str_replace('_', ' ', $job->recall_status ?? 'pending')); ?>
                                                        </span></p>
                                                    </div>
                                                    <?php if (!empty($job->recall_settled_at)): ?>
                                                        <div class="detail-item">
                                                            <label>Settled At:</label>
                                                            <p><?php echo date('M j, Y g:i A', strtotime($job->recall_settled_at)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($job->recall_details) || !empty($job->evidence_notes)): ?>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <?php if (!empty($job->recall_details)): ?>
                                                            <h6 class="details-title">Recall Details</h6>
                                                            <div class="recall-details">
                                                                <p><?php echo nl2br(htmlspecialchars($job->recall_details)); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($job->evidence_notes)): ?>
                                                            <h6 class="details-title mt-3">Evidence & Supporting Information</h6>
                                                            <div class="evidence-details">
                                                                <p><?php echo nl2br(htmlspecialchars($job->evidence_notes)); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Admin Actions & Settlement Form -->
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <h6 class="details-title">Admin Actions</h6>
                                                    
                                                    <!-- Settlement Form -->
                                                    <div class="settlement-form mb-3 p-3 border rounded bg-light">
                                                        <h6 class="mb-3">
                                                            <i class="fas fa-gavel me-2"></i>
                                                            Settle This Recall
                                                        </h6>
                                                        <form class="settle-recall-form" data-job-id="<?php echo $job->id; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="admin_decision_<?php echo $job->id; ?>" class="form-label">
                                                                        Admin Decision <span class="text-danger">*</span>
                                                                    </label>
                                                                    <select class="form-select form-select-sm admin-decision" 
                                                                            id="admin_decision_<?php echo $job->id; ?>" 
                                                                            name="admin_decision" 
                                                                            required>
                                                                        <option value="">Select decision...</option>
                                                                        <option value="favor_host">In Favor of Host</option>
                                                                        <option value="favor_cleaner">In Favor of Cleaner</option>
                                                                        <option value="mutual_agreement">Mutual Agreement Reached</option>
                                                                        <option value="no_action">No Action Required</option>
                                                                        <option value="mediation_required">Mediation Required</option>
                                                                        <option value="escalated_legal">Escalated to Legal</option>
                                                                        <option value="insufficient_evidence">Insufficient Evidence</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label for="resolution_type_<?php echo $job->id; ?>" class="form-label">
                                                                        Resolution Type
                                                                    </label>
                                                                    <select class="form-select form-select-sm" 
                                                                            id="resolution_type_<?php echo $job->id; ?>" 
                                                                            name="resolution_type">
                                                                        <option value="">Select type...</option>
                                                                        <option value="warning_issued">Warning Issued</option>
                                                                        <option value="verbal_warning">Verbal Warning</option>
                                                                        <option value="written_warning">Written Warning</option>
                                                                        <option value="account_suspended">Account Suspended</option>
                                                                        <option value="temporary_suspension">Temporary Suspension</option>
                                                                        <option value="resolved_amicably">Resolved Amicably</option>
                                                                        <option value="both_parties_counseled">Both Parties Counseled</option>
                                                                        <option value="re_training_required">Re-training Required</option>
                                                                        <option value="no_resolution_needed">No Resolution Needed</option>
                                                                        <option value="case_closed">Case Closed</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="admin_notes_<?php echo $job->id; ?>" class="form-label">
                                                                    Admin Notes / Resolution Details <span class="text-danger">*</span>
                                                                </label>
                                                                <textarea class="form-control form-control-sm admin-notes" 
                                                                          id="admin_notes_<?php echo $job->id; ?>" 
                                                                          name="admin_notes" 
                                                                          rows="4" 
                                                                          placeholder="Provide detailed notes about your decision, actions taken, and resolution details..."
                                                                          required></textarea>
                                                                <small class="text-muted">These notes will be visible to the host.</small>
                                                            </div>
                                                            
                                                            <div class="d-flex gap-2 justify-content-end">
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                    Mark as Settled
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    
                                                    <!-- Quick Actions -->
                                                    <div class="admin-actions">
                                                        <a href="<?php echo base_url('host/public-profile/' . $job->host_id); ?>" 
                                                           class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-user me-1"></i>
                                                            Host Profile
                                                        </a>
                                                        <a href="<?php echo base_url('cleaner/public-profile/' . $job->assigned_cleaner_id); ?>" 
                                                           class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-user-shield me-1"></i>
                                                            Cleaner Profile
                                                        </a>
                                                        <a href="<?php echo base_url('admin/ban_user/' . $job->host_id); ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Are you sure you want to ban this host?');"
                                                           title="Ban Host">
                                                            <i class="fas fa-ban me-1"></i>
                                                            Ban Host
                                                        </a>
                                                        <a href="<?php echo base_url('admin/ban_user/' . $job->assigned_cleaner_id); ?>" 
                                                           class="btn btn-danger btn-sm"
                                                           onclick="return confirm('Are you sure you want to ban this cleaner?');"
                                                           title="Ban Cleaner">
                                                            <i class="fas fa-ban me-1"></i>
                                                            Ban Cleaner
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                            <h5>No Recalled Jobs Found</h5>
                            <p class="text-muted">
                                <?php if (!empty(array_filter($filters))): ?>
                                    No recalled jobs match your current filters. Try adjusting your search criteria.
                                <?php else: ?>
                                    No recalled jobs at this time. Recalled jobs from hosts will appear here for admin review.
                                <?php endif; ?>
                            </p>
                            <?php if (!empty(array_filter($filters))): ?>
                                <a href="<?php echo base_url('admin/recalled-jobs'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Clear Filters
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
}

.header-content {
    text-align: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Summary Cards */
.summary-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-5px);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card-content {
    flex: 1;
}

.card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin: 0;
}

.card-label {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.filter-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-card .card-title {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.filter-card .card-body {
    padding: 2rem;
}

/* Jobs Card */
.jobs-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.jobs-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
}

.jobs-card .card-title {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.jobs-card .card-body {
    padding: 2rem;
}

/* Recalled Job Items */
.recalled-job-item {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.recalled-job-item:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.recalled-job-item.expanded {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.job-header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    gap: 1rem;
}

.job-info {
    flex: 1;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.job-meta {
    display: flex;
    gap: 1.5rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.job-status {
    display: flex;
    align-items: center;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-under_investigation {
    background: #d1ecf1;
    color: #0c5460;
}

.status-resolved {
    background: #d4edda;
    color: #155724;
}

.job-toggle {
    color: #6c757d;
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.recalled-job-item.expanded .job-toggle {
    transform: rotate(180deg);
}

/* Job Details */
.job-details {
    border-top: 1px solid #dee2e6;
    background: white;
    border-radius: 0 0 12px 12px;
}

.details-content {
    padding: 2rem;
}

.details-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-item label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.detail-item p {
    margin: 0;
    color: #6c757d;
}

.severity-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.severity-low {
    background: #d4edda;
    color: #155724;
}

.severity-medium {
    background: #fff3cd;
    color: #856404;
}

.severity-high {
    background: #f8d7da;
    color: #721c24;
}

.severity-critical {
    background: #f5c6cb;
    color: #721c24;
}

.recall-details, .evidence-details {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

/* Job Actions & Admin Actions */
.job-actions, .admin-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    padding: 1rem 0;
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.status-info {
    color: #6c757d;
    font-size: 0.9rem;
    font-style: italic;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-state i {
    margin-bottom: 1rem;
}

.empty-state h5 {
    color: #495057;
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .summary-card {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .job-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .job-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .details-content {
        padding: 1.5rem;
    }
    
    .job-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Toggle filters visibility
    $('#toggleFilters').click(function() {
        $('#filterBody').slideToggle();
        const icon = $(this).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Auto-submit form on filter change
    $('#filterForm select').change(function() {
        $('#filterForm').submit();
    });
    
    // Search with debounce
    let searchTimeout;
    $('#filterForm input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });

    // Toggle job details
    $('.recalled-job-item').on('click', function(e) {
        // Don't toggle if clicking on buttons, links, or form elements
        if ($(e.target).closest('.admin-actions').length || 
            $(e.target).closest('.settlement-form').length ||
            $(e.target).is('a') || 
            $(e.target).is('button') || 
            $(e.target).is('input') || 
            $(e.target).is('select') || 
            $(e.target).is('textarea')) {
            return;
        }
        const jobId = $(this).find('.job-details').attr('id').replace('job-details-', '');
        toggleJobDetails(jobId);
    });
    
    // Prevent clicks inside the settlement form from collapsing the dropdown
    $('.settlement-form').on('click', function(e) {
        e.stopPropagation();
    });

    // Settle recall inline form submission
    $('.settle-recall-form').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $form = $(this);
        const $button = $form.find('button[type="submit"]');
        const jobId = $form.data('job-id');
        
        // Get form values
        const adminDecision = $form.find('.admin-decision').val();
        const adminNotes = $form.find('.admin-notes').val();
        const resolutionType = $form.find('[name="resolution_type"]').val();
        
        // Validate required fields
        if (!adminDecision) {
            alert('Please select an admin decision.');
            return;
        }
        
        if (!adminNotes.trim()) {
            alert('Please provide admin notes about the resolution.');
            return;
        }
        
        if (confirm('Are you sure you want to mark this recall as settled? This action will close the recall and notify the host.')) {
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Settling...');
            
            $.ajax({
                url: '<?php echo base_url("admin/settle_recall"); ?>',
                type: 'POST',
                data: {
                    job_id: jobId,
                    admin_decision: adminDecision,
                    resolution_type: resolutionType,
                    admin_notes: adminNotes
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        $button.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i>Mark as Settled');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i>Mark as Settled');
                }
            });
        } else {
            return false;
        }
    });

});

function toggleJobDetails(jobId) {
    const details = $('#job-details-' + jobId);
    const jobItem = details.closest('.recalled-job-item');
    
    if (details.is(':visible')) {
        details.slideUp();
        jobItem.removeClass('expanded');
    } else {
        details.slideDown();
        jobItem.addClass('expanded');
    }
}
</script>
