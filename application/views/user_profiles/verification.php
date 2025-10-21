<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt"></i> Account Verification
                    </h4>
                    <p class="text-muted mb-0">Get verified to build trust and attract more clients</p>
                </div>
                <div class="card-body">
                    
                    <!-- Current Verification Status -->
                    <div class="verification-status">
                        <div class="status-card <?php echo $profile->verification_status ?? 'not-started'; ?>">
                            <div class="status-icon">
                                <?php if (($profile->verification_status ?? 'not-started') == 'verified'): ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php elseif (($profile->verification_status ?? 'not-started') == 'pending'): ?>
                                    <i class="fas fa-clock"></i>
                                <?php elseif (($profile->verification_status ?? 'not-started') == 'rejected'): ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php else: ?>
                                    <i class="fas fa-exclamation-triangle"></i>
                                <?php endif; ?>
                            </div>
                            <div class="status-info">
                                <h5>
                                    <?php 
                                    switch ($profile->verification_status ?? 'not-started') {
                                        case 'verified':
                                            echo 'Account Verified';
                                            break;
                                        case 'pending':
                                            echo 'Verification Pending';
                                            break;
                                        case 'rejected':
                                            echo 'Verification Rejected';
                                            break;
                                        default:
                                            echo 'Not Verified';
                                    }
                                    ?>
                                </h5>
                                <p class="status-description">
                                    <?php 
                                    switch ($profile->verification_status ?? 'not-started') {
                                        case 'verified':
                                            echo 'Your account has been successfully verified. You now have a verified badge on your profile.';
                                            break;
                                        case 'pending':
                                            echo 'Your verification documents are being reviewed. This usually takes 2-3 business days.';
                                            break;
                                        case 'rejected':
                                            echo 'Your verification was rejected. Please review the requirements and submit new documents.';
                                            break;
                                        default:
                                            echo 'Complete the verification process to get a verified badge and build trust with potential clients.';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if (($profile->verification_status ?? 'not-started') != 'verified'): ?>
                    <!-- Benefits Section -->
                    <div class="verification-benefits">
                        <h5><i class="fas fa-star"></i> Benefits of Verification</h5>
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Trust Badge</h6>
                                    <p>Display a verified badge on your profile to build trust with clients</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Higher Visibility</h6>
                                    <p>Verified profiles appear higher in search results and get more views</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>More Bookings</h6>
                                    <p>Verified cleaners typically receive 40% more job requests</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="benefit-content">
                                    <h6>Premium Status</h6>
                                    <p>Access to premium features and priority customer support</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Requirements -->
                    <div class="verification-requirements">
                        <h5><i class="fas fa-list-check"></i> Verification Requirements</h5>
                        <div class="requirements-list">
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="requirement-content">
                                    <h6>Government ID</h6>
                                    <p>Upload a clear photo of your driver's license, passport, or state ID</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="requirement-content">
                                    <h6>Proof of Address</h6>
                                    <p>Upload a utility bill, bank statement, or lease agreement (must be less than 3 months old)</p>
                                </div>
                            </div>
                            <div class="requirement-item">
                                <div class="requirement-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div class="requirement-content">
                                    <h6>Complete Profile</h6>
                                    <p>Ensure your profile is complete with bio, specialties, and service areas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Form -->
                    <?php if (($profile->verification_status ?? 'not-started') != 'pending'): ?>
                    <div class="verification-form">
                        <h5><i class="fas fa-upload"></i> Submit Documents</h5>
                        
                        <form id="verificationForm" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_document" class="form-label required">Government ID</label>
                                        <div class="file-upload-area" id="idUploadArea">
                                            <input type="file" id="id_document" name="id_document" accept="image/*,.pdf" class="d-none">
                                            <div class="upload-content">
                                                <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                                <p>Click to upload ID document</p>
                                                <small>JPG, PNG, or PDF (Max 5MB)</small>
                                            </div>
                                        </div>
                                        <div class="file-preview" id="idPreview" style="display: none;">
                                            <img src="" alt="Preview" class="preview-image">
                                            <button type="button" class="btn btn-sm btn-danger remove-file">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="proof_of_address" class="form-label required">Proof of Address</label>
                                        <div class="file-upload-area" id="addressUploadArea">
                                            <input type="file" id="proof_of_address" name="proof_of_address" accept="image/*,.pdf" class="d-none">
                                            <div class="upload-content">
                                                <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                                <p>Click to upload address proof</p>
                                                <small>JPG, PNG, or PDF (Max 5MB)</small>
                                            </div>
                                        </div>
                                        <div class="file-preview" id="addressPreview" style="display: none;">
                                            <img src="" alt="Preview" class="preview-image">
                                            <button type="button" class="btn btn-sm btn-danger remove-file">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                                    <label class="form-check-label" for="terms_agreement">
                                        I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="document_accuracy" name="document_accuracy" required>
                                    <label class="form-check-label" for="document_accuracy">
                                        I confirm that the documents provided are accurate and belong to me
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit for Verification
                                </button>
                                <a href="<?php echo base_url('userprofile/my_profile'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Profile
                                </a>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>

                    <?php if (($profile->verification_status ?? 'not-started') == 'rejected'): ?>
                    <!-- Rejection Information -->
                    <div class="verification-rejection">
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Verification Rejected</h6>
                            <p>Your verification was rejected. Common reasons include:</p>
                            <ul>
                                <li>Documents are unclear or blurry</li>
                                <li>Documents don't match your profile information</li>
                                <li>Documents are expired or invalid</li>
                                <li>Missing required information</li>
                            </ul>
                            <p>Please review the requirements and submit new documents.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>

                    <!-- Help Section -->
                    <div class="verification-help">
                        <h5><i class="fas fa-question-circle"></i> Need Help?</h5>
                        <div class="help-content">
                            <p>If you have questions about the verification process, please contact our support team:</p>
                            <div class="help-contacts">
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span>support@easyclean.com</span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span>1-800-EASY-CLEAN</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.verification-status {
    margin-bottom: 30px;
}

.status-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 10px;
    border: 2px solid;
}

.status-card.verified {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.status-card.pending {
    background: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}

.status-card.rejected {
    background: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

.status-card.not-started {
    background: #e2e3e5;
    border-color: #6c757d;
    color: #383d41;
}

.status-icon {
    font-size: 3rem;
    margin-right: 20px;
}

.status-info h5 {
    margin-bottom: 5px;
    font-weight: 600;
}

.status-description {
    margin-bottom: 0;
    opacity: 0.9;
}

.verification-benefits {
    margin-bottom: 30px;
}

.verification-benefits h5 {
    color: #007bff;
    margin-bottom: 20px;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.benefit-icon {
    font-size: 1.5rem;
    color: #007bff;
    margin-right: 15px;
    margin-top: 5px;
}

.benefit-content h6 {
    margin-bottom: 5px;
    color: #333;
}

.benefit-content p {
    margin-bottom: 0;
    color: #666;
    font-size: 0.9rem;
}

.verification-requirements {
    margin-bottom: 30px;
}

.verification-requirements h5 {
    color: #007bff;
    margin-bottom: 20px;
}

.requirements-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.requirement-item {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.requirement-icon {
    font-size: 1.5rem;
    color: #28a745;
    margin-right: 15px;
    margin-top: 5px;
}

.requirement-content h6 {
    margin-bottom: 5px;
    color: #333;
}

.requirement-content p {
    margin-bottom: 0;
    color: #666;
    font-size: 0.9rem;
}

.verification-form {
    margin-bottom: 30px;
}

.verification-form h5 {
    color: #007bff;
    margin-bottom: 20px;
}

.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.file-upload-area:hover {
    border-color: #007bff;
    background: #e7f3ff;
}

.upload-content i {
    color: #6c757d;
    margin-bottom: 10px;
}

.upload-content p {
    margin-bottom: 5px;
    color: #333;
    font-weight: 500;
}

.upload-content small {
    color: #6c757d;
}

.file-preview {
    position: relative;
    display: inline-block;
    margin-top: 10px;
}

.preview-image {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.remove-file {
    position: absolute;
    top: -10px;
    right: -10px;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.verification-help {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #17a2b8;
}

.verification-help h5 {
    color: #17a2b8;
    margin-bottom: 15px;
}

.help-content p {
    margin-bottom: 15px;
}

.help-contacts {
    display: flex;
    gap: 30px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
}

.contact-item i {
    color: #17a2b8;
}

.required::after {
    content: " *";
    color: #dc3545;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn {
    font-weight: 500;
    padding: 12px 24px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.alert {
    border-radius: 8px;
}

.alert ul {
    margin-bottom: 0;
}
</style>

<script>
$(document).ready(function() {
    // File upload functionality
    $('#idUploadArea, #addressUploadArea').on('click', function() {
        const inputId = $(this).attr('id').replace('UploadArea', '_document').replace('addressUploadArea', 'proof_of_address');
        $('#' + inputId).click();
    });
    
    // File preview functionality
    $('#id_document, #proof_of_address').on('change', function() {
        const file = this.files[0];
        const previewId = $(this).attr('id').replace('_document', 'Preview').replace('proof_of_address', 'addressPreview');
        const uploadAreaId = $(this).attr('id').replace('_document', 'UploadArea').replace('proof_of_address', 'addressUploadArea');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId).html('<img src="' + e.target.result + '" alt="Preview" class="preview-image"><button type="button" class="btn btn-sm btn-danger remove-file"><i class="fas fa-times"></i></button>').show();
                $('#' + uploadAreaId).hide();
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove file functionality
    $(document).on('click', '.remove-file', function() {
        const preview = $(this).closest('.file-preview');
        const inputId = preview.attr('id').replace('Preview', '_document').replace('addressPreview', 'proof_of_address');
        const uploadAreaId = preview.attr('id').replace('Preview', 'UploadArea').replace('addressPreview', 'addressUploadArea');
        
        $('#' + inputId).val('');
        preview.hide();
        $('#' + uploadAreaId).show();
    });
    
    // Form submission
    $('#verificationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required files
        const idFile = $('#id_document')[0].files[0];
        const addressFile = $('#proof_of_address')[0].files[0];
        
        if (!idFile || !addressFile) {
            alert('Please upload both ID document and proof of address.');
            return;
        }
        
        // Show loading state
        const submitBtn = $('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Submitting...').prop('disabled', true);
        
        // Submit form
        $.ajax({
            url: '<?php echo base_url("userprofile/submit_verification"); ?>',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>

<?php $this->load->view('template/footer'); ?>
