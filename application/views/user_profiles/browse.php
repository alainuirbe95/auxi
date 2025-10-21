<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Browse Header -->
            <div class="browse-header">
                <h2><i class="fas fa-users"></i> Browse All Cleaners</h2>
                <p class="text-muted">Discover talented cleaners in your area</p>
            </div>

            <div class="row">
                <!-- Quick Filters -->
                <div class="col-md-3">
                    <div class="quick-filters">
                        <h4><i class="fas fa-filter"></i> Quick Filters</h4>
                        
                        <!-- Sort Options -->
                        <div class="filter-group">
                            <label class="form-label">Sort By</label>
                            <select class="form-control" id="sortSelect">
                                <option value="rating">Highest Rated</option>
                                <option value="reviews">Most Reviews</option>
                                <option value="recent">Most Recent</option>
                                <option value="verified">Verified First</option>
                            </select>
                        </div>
                        
                        <!-- Quick Filter Buttons -->
                        <div class="filter-group">
                            <label class="form-label">Quick Filters</label>
                            <div class="quick-filter-buttons">
                                <button class="btn btn-outline-primary btn-sm filter-btn" data-filter="verified">
                                    <i class="fas fa-shield-alt"></i> Verified Only
                                </button>
                                <button class="btn btn-outline-success btn-sm filter-btn" data-filter="high-rated">
                                    <i class="fas fa-star"></i> 4+ Stars
                                </button>
                                <button class="btn btn-outline-info btn-sm filter-btn" data-filter="experienced">
                                    <i class="fas fa-briefcase"></i> 10+ Jobs
                                </button>
                            </div>
                        </div>
                        
                        <!-- Service Area Quick Filter -->
                        <div class="filter-group">
                            <label class="form-label">Service Area</label>
                            <select class="form-control" id="areaFilter">
                                <option value="">All Areas</option>
                                <option value="downtown">Downtown</option>
                                <option value="suburbs">Suburbs</option>
                                <option value="north_side">North Side</option>
                                <option value="south_side">South Side</option>
                                <option value="east_side">East Side</option>
                                <option value="west_side">West Side</option>
                            </select>
                        </div>
                        
                        <!-- Specialty Quick Filter -->
                        <div class="filter-group">
                            <label class="form-label">Specialty</label>
                            <select class="form-control" id="specialtyFilter">
                                <option value="">All Specialties</option>
                                <option value="residential">Residential Cleaning</option>
                                <option value="office">Office Cleaning</option>
                                <option value="deep_cleaning">Deep Cleaning</option>
                                <option value="move_in_out">Move In/Out</option>
                                <option value="green_cleaning">Eco-Friendly</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Profiles Grid -->
                <div class="col-md-9">
                    <!-- Results Header -->
                    <div class="results-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4>All Cleaners</h4>
                                <p class="text-muted mb-0">
                                    <?php echo $total_profiles; ?> cleaner<?php echo $total_profiles != 1 ? 's' : ''; ?> available
                                </p>
                            </div>
                            <div class="view-options">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary active" id="gridView">
                                        <i class="fas fa-th"></i> Grid
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="listView">
                                        <i class="fas fa-list"></i> List
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Cleaners Section -->
                    <div class="featured-section">
                        <h5><i class="fas fa-star"></i> Featured Cleaners</h5>
                        <div class="featured-profiles">
                            <?php 
                            $featured_count = 0;
                            foreach ($profiles as $profile): 
                                if ($profile->average_rating >= 4.5 && $profile->total_reviews >= 5 && $featured_count < 3):
                                    $featured_count++;
                            ?>
                            <div class="featured-card">
                                <div class="featured-badge">
                                    <i class="fas fa-crown"></i> Featured
                                </div>
                                <div class="profile-photo">
                                    <?php if ($profile->profile_picture_url): ?>
                                        <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile Picture">
                                    <?php else: ?>
                                        <div class="default-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($profile->verification_status == 'verified'): ?>
                                        <div class="verification-badge">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="profile-info">
                                    <h6 class="profile-name">
                                        <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>">
                                            <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
                                        </a>
                                    </h6>
                                    
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
                                    
                                    <div class="profile-stats">
                                        <span class="stat">
                                            <i class="fas fa-briefcase"></i>
                                            <?php echo $profile->total_jobs_completed; ?> jobs
                                        </span>
                                        <span class="stat">
                                            <i class="fas fa-percentage"></i>
                                            <?php echo $profile->completion_rate; ?>% completion
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="profile-actions">
                                    <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>

                    <!-- All Profiles Grid -->
                    <div class="profiles-grid" id="profilesGrid">
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
                                
                                <!-- Rating Badge -->
                                <?php if ($profile->average_rating >= 4.5): ?>
                                    <div class="rating-badge">
                                        <i class="fas fa-star"></i>
                                        <?php echo number_format($profile->average_rating, 1); ?>
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
                                        <i class="fas fa-eye"></i> View
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
                    <nav aria-label="Browse profiles pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('userprofile/browse?' . http_build_query(array_merge($_GET, array('page' => $current_page - 1)))); ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo base_url('userprofile/browse?' . http_build_query(array_merge($_GET, array('page' => $i)))); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('userprofile/browse?' . http_build_query(array_merge($_GET, array('page' => $current_page + 1)))); ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.browse-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 0;
    border-radius: 10px;
    margin-bottom: 30px;
    text-align: center;
}

.quick-filters {
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

.quick-filter-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-btn {
    font-size: 0.9rem;
    padding: 8px 12px;
    border-radius: 6px;
}

.filter-btn.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.results-header {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.view-options .btn-group .btn {
    padding: 8px 12px;
}

.featured-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.featured-section h5 {
    color: #007bff;
    margin-bottom: 15px;
    font-size: 1.2rem;
}

.featured-profiles {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.featured-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #ffc107;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    position: relative;
    transition: all 0.3s ease;
}

.featured-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.featured-badge {
    position: absolute;
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
    background: #ffc107;
    color: #000;
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.featured-badge i {
    margin-right: 5px;
}

.profiles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.profiles-grid.list-view {
    grid-template-columns: 1fr;
}

.profiles-grid.list-view .profile-card {
    display: flex;
    text-align: left;
    padding: 15px;
}

.profiles-grid.list-view .profile-photo {
    margin-right: 20px;
    margin-bottom: 0;
}

.profiles-grid.list-view .profile-info {
    flex: 1;
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

.rating-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    background: #ffc107;
    color: #000;
    border-radius: 12px;
    padding: 4px 8px;
    font-size: 0.8rem;
    font-weight: bold;
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

.featured-card .profile-actions {
    justify-content: center;
}

.featured-card .profile-actions .btn {
    flex: none;
    min-width: 120px;
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
    
    // Area and specialty filters
    $('#areaFilter, #specialtyFilter').on('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterType = $(this).attr('id') === 'areaFilter' ? 'location' : 'specialty';
        const filterValue = $(this).val();
        
        if (filterValue) {
            urlParams.set(filterType, filterValue);
        } else {
            urlParams.delete(filterType);
        }
        
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    });
    
    // Quick filter buttons
    $('.filter-btn').on('click', function() {
        $(this).toggleClass('active');
        
        // Apply filters based on active buttons
        const urlParams = new URLSearchParams(window.location.search);
        const activeFilters = [];
        
        $('.filter-btn.active').each(function() {
            activeFilters.push($(this).data('filter'));
        });
        
        if (activeFilters.length > 0) {
            urlParams.set('filters', activeFilters.join(','));
        } else {
            urlParams.delete('filters');
        }
        
        window.location.href = window.location.pathname + '?' + urlParams.toString();
    });
    
    // View toggle
    $('#gridView, #listView').on('click', function() {
        $('#gridView, #listView').removeClass('active');
        $(this).addClass('active');
        
        if ($(this).attr('id') === 'listView') {
            $('#profilesGrid').addClass('list-view');
        } else {
            $('#profilesGrid').removeClass('list-view');
        }
    });
});
</script>

<?php $this->load->view('template/footer'); ?>
