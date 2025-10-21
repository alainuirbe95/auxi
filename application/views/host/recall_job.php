<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Recall Job for Admin Review
                    </h2>
                    <p class="page-subtitle">Report issues with this job for admin review and potential legal action</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card job-info-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Job Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>Job Title:</label>
                                <p><?php echo htmlspecialchars($job->title); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Description:</label>
                                <p><?php echo htmlspecialchars($job->description); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Address:</label>
                                <p><?php echo htmlspecialchars($job->address . ', ' . $job->city . ', ' . $job->state); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>Scheduled Date:</label>
                                <p><?php echo date('M j, Y g:i A', strtotime($job->scheduled_date . ' ' . $job->scheduled_time)); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Cleaner:</label>
                                <p><?php echo htmlspecialchars($cleaner_name); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Job Price:</label>
                                <p class="price-highlight">$<?php echo number_format($job->final_price ?: $job->accepted_price ?: $job->suggested_price, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recall Form -->
    <div class="row">
        <div class="col-12">
            <div class="card recall-form-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Recall Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> 
                        <?php if ($job->status === 'completed'): ?>
                            Recalling this job will still release payment to the cleaner, but will notify the admin for review and potential legal action.
                        <?php else: ?>
                            Recalling this past job will notify the admin for review and potential legal action. This action cannot be undone.
                        <?php endif; ?>
                    </div>

                    <form id="recallJobForm" method="POST" action="<?php echo base_url('host/process_recall_job'); ?>">
                        <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                        <input type="hidden" name="recall_type" value="<?php echo $job->status === 'completed' ? 'completed' : 'past'; ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="recall_reason" class="form-label">Reason for Recall <span class="text-danger">*</span></label>
                                    <select class="form-select" id="recall_reason" name="recall_reason" required>
                                        <option value="">Select a reason...</option>
                                        <option value="poor_quality">Poor Quality Work</option>
                                        <option value="incomplete_service">Incomplete Service</option>
                                        <option value="damage_caused">Damage to Property</option>
                                        <option value="unprofessional_behavior">Unprofessional Behavior</option>
                                        <option value="safety_concerns">Safety Concerns</option>
                                        <option value="contract_violation">Contract Violation</option>
                                        <option value="other">Other (specify in details)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="severity" class="form-label">Severity Level <span class="text-danger">*</span></label>
                                    <select class="form-select" id="severity" name="severity" required>
                                        <option value="">Select severity...</option>
                                        <option value="low">Low - Minor issues</option>
                                        <option value="medium">Medium - Significant issues</option>
                                        <option value="high">High - Serious issues</option>
                                        <option value="critical">Critical - Major problems</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="recall_details" class="form-label">Detailed Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="recall_details" name="recall_details" rows="6" 
                                      placeholder="Please provide a detailed description of the issues encountered. Include specific examples, photos if available, and any relevant information that will help the admin review this case." 
                                      required></textarea>
                            <div class="form-text">
                                <span id="detailsCount">0</span>/1000 characters
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="evidence_notes" class="form-label">Evidence & Supporting Information</label>
                            <textarea class="form-control" id="evidence_notes" name="evidence_notes" rows="4" 
                                      placeholder="Describe any evidence you have (photos, videos, witnesses, etc.) that supports your recall. This information will be crucial for the admin review."></textarea>
                            <div class="form-text">
                                <span id="evidenceCount">0</span>/500 characters
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="desired_resolution" class="form-label">Desired Resolution</label>
                            <select class="form-select" id="desired_resolution" name="desired_resolution">
                                <option value="">Select desired resolution...</option>
                                <option value="refund">Full or partial refund</option>
                                <option value="rework">Job to be redone</option>
                                <option value="compensation">Compensation for damages</option>
                                <option value="cleaner_review">Cleaner account review</option>
                                <option value="other">Other (specify in details)</option>
                            </select>
                        </div>

                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> False or malicious recalls may result in account suspension. Please ensure your recall is legitimate and well-documented. All recalls are reviewed by admin and may result in legal action.
                        </div>

                        <div class="form-actions">
                            <a href="<?php echo $back_url; ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-danger" id="submitRecall">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Submit Recall
                            </button>
                        </div>
                    </form>
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

/* Cards */
.job-info-card, .recall-form-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
}

.card-title {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.card-body {
    padding: 2rem;
}

/* Info Items */
.info-item {
    margin-bottom: 1.5rem;
}

.info-item label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.info-item p {
    margin: 0;
    color: #6c757d;
    font-size: 1rem;
}

.price-highlight {
    color: #28a745 !important;
    font-weight: 600;
    font-size: 1.1rem;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.form-select, .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Alerts */
.alert {
    border: none;
    border-radius: 10px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #ffc107;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
    margin-top: 2rem;
}

.btn {
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    border: none;
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
$(document).ready(function() {
    // Character counters
    $('#recall_details').on('input', function() {
        const count = $(this).val().length;
        $('#detailsCount').text(count);
        
        if (count > 1000) {
            $(this).val($(this).val().substring(0, 1000));
            $('#detailsCount').text('1000');
        }
    });

    $('#evidence_notes').on('input', function() {
        const count = $(this).val().length;
        $('#evidenceCount').text(count);
        
        if (count > 500) {
            $(this).val($(this).val().substring(0, 500));
            $('#evidenceCount').text('500');
        }
    });

    // Form submission
    $('#recallJobForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $('#submitRecall');
        
        // Validate form
        if (!$('#recall_reason').val()) {
            alert('Please select a reason for the recall.');
            return;
        }
        
        if (!$('#severity').val()) {
            alert('Please select a severity level.');
            return;
        }
        
        if (!$('#recall_details').val().trim()) {
            alert('Please provide detailed description of the issues.');
            return;
        }
        
        if ($('#recall_details').val().length < 20) {
            alert('Please provide a more detailed description (at least 20 characters).');
            return;
        }
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Submitting...');
        
        // Submit form
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect;
                } else {
                    alert('Error: ' + response.message);
                    $submitBtn.prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $submitBtn.prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
            }
        });
    });
});
</script>
