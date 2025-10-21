<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Search Header -->
            <div class="search-header">
                <h2><i class="fas fa-search"></i> Find Cleaners</h2>
                <p class="text-muted">Discover trusted cleaners in your area</p>
                </div>

                        <div class="row">
                <!-- Search Filters -->
                            <div class="col-md-3">
                    <div class="search-filters">
                        <h4><i class="fas fa-filter"></i> Filters</h4>
                        
                        <form method="GET" action="<?php echo base_url('userprofile/search'); ?>">
                            <!-- Search Term -->
                            <div class="filter-group">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($search_term ?? ''); ?>" 
                                       placeholder="Name or username">
                            </div>
                            
                            <!-- Location -->
                            <div class="filter-group">
                                <label class="form-label">Service Area</label>
                                <select class="form-control" name="location">
                                        <option value="">All Areas</option>
                                    <option value="downtown" <?php echo ($location == 'downtown') ? 'selected' : ''; ?>>Downtown</option>
                                    <option value="suburbs" <?php echo ($location == 'suburbs') ? 'selected' : ''; ?>>Suburbs</option>
                                    <option value="north_side" <?php echo ($location == 'north_side') ? 'selected' : ''; ?>>North Side</option>
                                    <option value="south_side" <?php echo ($location == 'south_side') ? 'selected' : ''; ?>>South Side</option>
                                    <option value="east_side" <?php echo ($location == 'east_side') ? 'selected' : ''; ?>>East Side</option>
                                    <option value="west_side" <?php echo ($location == 'west_side') ? 'selected' : ''; ?>>West Side</option>
                                    </select>
                            </div>
                            
                            <!-- Minimum Rating -->
                            <div class="filter-group">
                                <label class="form-label">Minimum Rating</label>
                                <select class="form-control" name="min_rating">
                                        <option value="">Any Rating</option>
                                    <option value="4" <?php echo ($min_rating == '4') ? 'selected' : ''; ?>>4+ Stars</option>
                                        <option value="4.5" <?php echo ($min_rating == '4.5') ? 'selected' : ''; ?>>4.5+ Stars</option>
                                    <option value="5" <?php echo ($min_rating == '5') ? 'selected' : ''; ?>>5 Stars</option>
                                    </select>
                            </div>
                            
                            <!-- Verification Status -->
                            <div class="filter-group">
                                <label class="form-label">Verification</label>
                                <select class="form-control" name="verification_status">
                                        <option value="">All Users</option>
                                    <option value="verified" <?php echo ($verification_status == 'verified') ? 'selected' : ''; ?>>Verified Only</option>
                                    </select>
                            </div>
                            
                            <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                            
                            <!-- Clear Filters -->
                            <a href="<?php echo base_url('userprofile/search'); ?>" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                    </form>
                </div>
            </div>

            <!-- Search Results -->
                <div class="col-md-9">
                    <!-- Results Header -->
                    <div class="results-header">
                    <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4>Search Results</h4>
                                <p class="text-muted mb-0">
                                    <?php echo $total_profiles; ?> cleaner<?php echo $total_profiles != 1 ? 's' : ''; ?> found
                                    <?php if ($search_term): ?>
                                        for "<?php echo htmlspecialchars($search_term); ?>"
                            <?php endif; ?>
                                </p>
                            </div>
                        <div class="sort-options">
                                <select class="form-control" id="sortSelect">
                                <option value="rating">Sort by Rating</option>
                                <option value="reviews">Sort by Reviews</option>
                                    <option value="name">Sort by Name</option>
                            </select>
                            </div>
                        </div>
                    </div>

                    <!-- Profiles Grid -->
                    <?php if (!empty($profiles)): ?>
                        <div class="profiles-grid">
                            <?php foreach ($profiles as $profile): ?>
                                <div class="profile-card">
                                <!-- Profile Photo -->
                                        <div class="profile-photo">
                                            <?php if ($profile->profile_picture_url): ?>
                                        <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile Picture">
                                            <?php else: ?>
                                        <div class="default-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                    
                                    <!-- Verification Badge -->
                                    <?php if ($profile->verification_status == 'verified'): ?>
                                        <div class="verification-badge">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Profile Info -->
                                        <div class="profile-info">
                                    <h5 class="profile-name">
                                        <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>">
                                                <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
                                        </a>
                                    </h5>
                                    <p class="username">@<?php echo htmlspecialchars($profile->username); ?></p>
                                            
                                            <!-- Rating -->
                                            <div class="profile-rating">
                                                <div class="stars">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?php echo ($i <= floor($profile->average_rating ?? 0)) ? 'filled' : ''; ?>">★</span>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="rating-text">
                                            <?php echo number_format($profile->average_rating ?? 0, 1); ?> 
                                            (<?php echo $profile->total_reviews; ?> reviews)
                                                </span>
                                            </div>
                                    
                                    <!-- Quick Stats -->
                                    <div class="quick-stats">
                                        <div class="stat">
                                            <i class="fas fa-briefcase"></i>
                                            <span><?php echo $profile->total_jobs_completed; ?> jobs</span>
                                        </div>
                                        <div class="stat">
                                            <i class="fas fa-percentage"></i>
                                            <span><?php echo $profile->completion_rate; ?>% completion</span>
                                        </div>
                                    </div>
                                        
                                        <!-- Specialties Preview -->
                                        <?php if ($profile->specialties): ?>
                                        <div class="specialties-preview">
                                            <?php 
                                            $specialties = json_decode($profile->specialties, true);
                                        $specialty_labels = array(
                                            'residential' => 'Residential',
                                            'office' => 'Office',
                                            'deep_cleaning' => 'Deep Clean',
                                            'move_in_out' => 'Move Clean',
                                            'post_construction' => 'Construction',
                                            'green_cleaning' => 'Eco-Friendly',
                                            'window_cleaning' => 'Windows',
                                            'carpet_cleaning' => 'Carpet'
                                        );
                                        
                                        if ($specialties && count($specialties) > 0):
                                            $displayed = 0;
                                            foreach ($specialties as $specialty):
                                                if (isset($specialty_labels[$specialty]) && $displayed < 3):
                                                    echo '<span class="specialty-tag">' . htmlspecialchars($specialty_labels[$specialty]) . '</span>';
                                                    $displayed++;
                                                endif;
                                                endforeach;
                                            
                                                if (count($specialties) > 3):
                                                echo '<span class="specialty-tag more">+' . (count($specialties) - 3) . ' more</span>';
                                            endif;
                                        endif;
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Action Buttons -->
                                    <div class="profile-actions">
                                        <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> View Profile
                                        </a>
                                        <a href="<?php echo base_url('jobs/create'); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Hire
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <nav aria-label="Search results pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                        <a class="page-link" href="<?php echo base_url('userprofile/search?' . http_build_query(array_merge($_GET, array('page' => $current_page - 1)))); ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo base_url('userprofile/search?' . http_build_query(array_merge($_GET, array('page' => $i)))); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php endfor; ?>
                                
                                <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                        <a class="page-link" href="<?php echo base_url('userprofile/search?' . http_build_query(array_merge($_GET, array('page' => $current_page + 1)))); ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-results">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No cleaners found</h5>
                            <p class="text-muted">
                                    <?php if ($search_term || $location || $min_rating || $verification_status): ?>
                                        Try adjusting your search filters to find more cleaners.
                                <?php else: ?>
                                        No cleaners are currently available. Check back later!
                                <?php endif; ?>
                            </p>
                                <a href="<?php echo base_url('userprofile/search'); ?>" class="btn btn-primary">
                                    <i class="fas fa-refresh"></i> Clear Filters
                            </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.search-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 0;
    border-radius: 10px;
    margin-bottom: 30px;
    text-align: center;
}

.search-filters {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 20px;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.results-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.profiles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.profile-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    text-align: center;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.profile-photo {
    position: relative;
    margin-bottom: 15px;
}

.profile-photo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e9ecef;
}

.default-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 3px solid #e9ecef;
}

.default-avatar i {
    font-size: 2rem;
    color: #6c757d;
}

.verification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.profile-name {
    margin-bottom: 5px;
}

.profile-name a {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}

.profile-name a:hover {
    text-decoration: underline;
}

.username {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.profile-rating {
    margin-bottom: 15px;
}

.stars {
    display: inline-block;
    font-size: 1.2rem;
    margin-right: 8px;
}

.star {
    color: #ddd;
}

.star.filled {
    color: #ffc107;
}

.rating-text {
    font-size: 0.9rem;
    color: #6c757d;
}

.quick-stats {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 15px;
}

.stat {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    color: #6c757d;
}

.stat i {
    color: #007bff;
}

.specialties-preview {
    margin-bottom: 15px;
}

.specialty-tag {
    display: inline-block;
    background: #e7f3ff;
    color: #007bff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    margin: 2px;
    border: 1px solid #b3d7ff;
}

.specialty-tag.more {
    background: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
}

.profile-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.profile-actions .btn {
    flex: 1;
    font-size: 0.9rem;
    padding: 8px 12px;
}

.no-results {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    color: #007bff;
    border-color: #dee2e6;
}

.page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn {
    font-weight: 500;
}

.form-control {
    border-radius: 6px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>

<script>
$(document).ready(function() {
    // Sort functionality
    $('#sortSelect').on('change', function() {
        const sortValue = $(this).val();
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort', sortValue);
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    });
    
    // Auto-submit form on filter change
    $('.search-filters select').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>

<?php $this->load->view('template/footer'); ?>