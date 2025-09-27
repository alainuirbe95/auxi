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
<form id="inconsistencyForm">
    <input type="hidden" name="job_id" value="<?= $job->id ?>">
    
    <div class="form-group">
        <label for="inconsistency_type">Issue Type</label>
        <select class="form-control" id="inconsistency_type" name="inconsistency_type" required>
            <option value="">Select issue type...</option>
            <option value="property_damage">Property Damage</option>
            <option value="missing_items">Missing Items</option>
            <option value="unexpected_conditions">Unexpected Conditions</option>
            <option value="access_issues">Access Issues</option>
            <option value="safety_concerns">Safety Concerns</option>
            <option value="other">Other</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="severity">Severity Level</label>
        <select class="form-control" id="severity" name="severity" required>
            <option value="">Select severity...</option>
            <option value="low">Low - Minor issue, service can continue</option>
            <option value="medium">Medium - Noticeable issue, may affect service quality</option>
            <option value="high">High - Significant issue, impacts service delivery</option>
            <option value="critical">Critical - Major issue, service may need to be stopped</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" 
                  id="description" 
                  name="description" 
                  rows="4" 
                  maxlength="1000"
                  required
                  placeholder="Please describe the issue in detail..."></textarea>
        <small class="form-text text-muted">
            <span id="descCount">0</span>/1000 characters
        </small>
    </div>
    
    <div class="form-group">
        <label for="photos">Photos (Optional)</label>
        <div class="custom-file">
            <input type="file" 
                   class="custom-file-input" 
                   id="photos" 
                   name="photos[]" 
                   multiple 
                   accept="image/*">
            <label class="custom-file-label" for="photos">Choose photos...</label>
        </div>
        <small class="form-text text-muted">
            Upload photos to document the issue (max 2MB each, up to 5 photos)
        </small>
        <div id="photoPreview" class="mt-2"></div>
    </div>
    
    <div class="alert alert-warning">
        <i class="fas fa-info-circle"></i>
        <strong>Note:</strong> Reporting an issue will notify the host immediately. Make sure to provide accurate information.
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-exclamation-triangle"></i> Report Issue
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancel
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Character counter for description
    $('#description').on('input', function() {
        var length = $(this).val().length;
        $('#descCount').text(length);
    });
    
    // File input label update
    $('#photos').on('change', function() {
        var files = $(this)[0].files;
        var label = $(this).next('.custom-file-label');
        
        if (files.length > 0) {
            if (files.length === 1) {
                label.text(files[0].name);
            } else {
                label.text(files.length + ' files selected');
            }
            
            // Preview images
            previewImages(files);
        } else {
            label.text('Choose photos...');
            $('#photoPreview').empty();
        }
    });
    
    // Form submission
    $('#inconsistencyForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        // First upload photos if any
        var photos = $('#photos')[0].files;
        if (photos.length > 0) {
            uploadPhotos(formData);
        } else {
            submitInconsistency(formData);
        }
    });
});

function previewImages(files) {
    var preview = $('#photoPreview');
    preview.empty();
    
    Array.from(files).forEach(function(file) {
        if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.append(
                    '<div class="d-inline-block mr-2 mb-2">' +
                    '<img src="' + e.target.result + '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">' +
                    '</div>'
                );
            };
            reader.readAsDataURL(file);
        }
    });
}

function uploadPhotos(formData) {
    $.ajax({
        url: '<?= base_url("cleaner/jobs-in-progress/upload-photos") ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Add photo URLs to form data
                var photos = response.files.map(function(file) {
                    return file.url;
                });
                formData.append('photos', JSON.stringify(photos));
                
                // Submit the inconsistency report
                submitInconsistency(formData);
            } else {
                alert('Photo upload failed: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while uploading photos.');
        }
    });
}

function submitInconsistency(formData) {
    // Remove file inputs from FormData for the inconsistency submission
    formData.delete('photos[]');
    
    $.ajax({
        url: '<?= base_url("cleaner/jobs-in-progress/report-inconsistency") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(response.message);
                $('#inconsistencyModal').modal('hide');
                // Reload the page to show the new inconsistency
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while reporting the issue.');
        }
    });
}
</script>
</div>
