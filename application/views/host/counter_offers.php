<style>
/* Dashboard Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-card.pending {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card.approved {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card.escalated {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-card.disputed {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    opacity: 0.8;
}

/* Filter Section */
.filter-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filter-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
    font-size: 0.9rem;
}

.filter-group input,
.filter-group select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.filter-group input:focus,
.filter-group select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.price-range {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 0.5rem;
    align-items: center;
}

/* Table Container */
.table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: visible;
    width: 100%;
}

.table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.sort-controls {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.sort-controls select {
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    background: rgba(255,255,255,0.1);
    color: white;
    backdrop-filter: blur(10px);
}

.sort-controls select option {
    background: #667eea;
    color: white;
}

/* Scrollable Table */
.scrollable-table {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: auto;
    width: 100%;
}

.scrollable-table::-webkit-scrollbar {
    width: 8px;
}

.scrollable-table::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.scrollable-table::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
}

.scrollable-table::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

/* Enhanced Table */
.enhanced-table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
    margin: 0;
}

.enhanced-table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem 0.75rem;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 3px solid #667eea;
    position: sticky;
    top: 0;
    z-index: 10;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.enhanced-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
    overflow: visible;
    position: relative;
}

.enhanced-table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.enhanced-table tbody td {
    padding: 1.25rem 0.75rem;
    vertical-align: middle;
    border: none;
    overflow: visible;
    position: relative;
}

/* Job Details Cell */
.job-details-cell {
    min-width: 250px;
}

.job-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.job-meta {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.job-description {
    font-size: 0.85rem;
    color: #495057;
    margin: 0;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Cleaner Cell */
.cleaner-cell {
    min-width: 150px;
}

.cleaner-name {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.cleaner-username {
    font-size: 0.8rem;
    color: #6c757d;
    font-style: italic;
}

/* Price Cell */
.price-cell {
    text-align: center;
    min-width: 120px;
}

.original-price {
    font-size: 0.9rem;
    color: #6c757d;
    text-decoration: line-through;
    margin-bottom: 0.25rem;
}

.proposed-price {
    font-size: 1.1rem;
    font-weight: 600;
    color: #28a745;
}

.price-difference {
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

/* Status Badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.status-pending {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #d68910;
    border: 1px solid #f4d03f;
}

.status-approved {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #27ae60;
    border: 1px solid #58d68d;
}

.status-escalated {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    color: #8e44ad;
    border: 1px solid #bb8fce;
}

.status-disputed {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    color: #e91e63;
    border: 1px solid #f06292;
}

/* Expiry Cell */
.expiry-cell {
    text-align: center;
    min-width: 120px;
}

.expiry-date {
    font-size: 0.85rem;
    color: #495057;
    margin-bottom: 0.25rem;
}

.time-remaining {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-weight: 500;
}

.time-remaining.warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.time-remaining.danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.time-remaining.info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    min-width: 200px;
}

.action-btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 80px;
}

.action-btn.accept {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(86, 171, 47, 0.3);
}

.action-btn.accept:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(86, 171, 47, 0.4);
}

.action-btn.dispute {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(240, 147, 251, 0.3);
}

.action-btn.dispute:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
}

/* Help Text */
.help-text {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
    text-align: center;
    font-style: italic;
}

/* Dispute Form */
.dispute-form {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffeaa7;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(255, 193, 7, 0.2);
    position: relative;
    z-index: 1000;
    min-width: 300px;
    overflow: visible;
}

.dispute-form-content {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    overflow: visible;
    position: relative;
    z-index: 1001;
}

.dispute-form .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.dispute-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
    background-color: white;
    color: #495057;
    font-size: 0.9rem;
    line-height: 1.4;
}

.dispute-form .form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
}

.dispute-form select.form-control {
    background-color: white;
    background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.dispute-form select.form-control option {
    background-color: white;
    color: #495057;
    padding: 0.5rem;
    font-size: 0.9rem;
    line-height: 1.4;
    white-space: normal;
    word-wrap: break-word;
    max-width: 100%;
}

.dispute-reason-select {
    min-width: 100%;
    width: 100%;
    max-width: none;
    position: relative;
    z-index: 1002;
}

.dispute-reason-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    outline: none;
    z-index: 1003;
}

/* Ensure dropdown options are visible */
.dispute-reason-select option {
    background-color: white !important;
    color: #495057 !important;
    padding: 0.75rem !important;
    font-size: 0.9rem !important;
    line-height: 1.4 !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    max-width: none !important;
    width: 100% !important;
    display: block !important;
    border: 1px solid #e9ecef !important;
    margin-bottom: 1px !important;
}

.dispute-form-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.dispute-form-actions .btn {
    min-width: 120px;
    flex-shrink: 0;
}

/* Make container wider */
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

/* Custom Dropdown */
.custom-dropdown {
    position: relative;
    width: 100%;
    z-index: 1000;
}

.dropdown-selected {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    background-color: white;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    min-height: 48px;
}

.dropdown-selected:hover {
    border-color: #ffc107;
}

.dropdown-selected.active {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.dropdown-selected .selected-text {
    color: #495057;
    font-size: 0.9rem;
    line-height: 1.4;
    flex: 1;
    padding-right: 1rem;
}

.dropdown-selected .selected-text.placeholder {
    color: #6c757d;
}

.dropdown-arrow {
    color: #6c757d;
    transition: transform 0.3s ease;
}

.dropdown-selected.active .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-options {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #e9ecef;
    border-top: none;
    border-radius: 0 0 8px 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1001;
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-option {
    padding: 0.75rem;
    color: #495057;
    font-size: 0.9rem;
    line-height: 1.4;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.2s ease;
    white-space: normal;
    word-wrap: break-word;
}

.dropdown-option:last-child {
    border-bottom: none;
}

.dropdown-option:hover {
    background-color: #f8f9fa;
    color: #2c3e50;
}

.dropdown-option.selected {
    background-color: #e3f2fd;
    color: #1565c0;
    font-weight: 600;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h4 {
    margin-bottom: 1rem;
    color: #495057;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .filter-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .price-range {
        grid-template-columns: 1fr;
    }
    
    .dispute-form {
        min-width: 250px;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .scrollable-table {
        max-height: 400px;
    }
    
    .enhanced-table {
        font-size: 0.85rem;
        min-width: 800px;
    }
    
    .action-buttons {
        flex-direction: column;
        min-width: 120px;
    }
    
    .dispute-form {
        min-width: 200px;
        padding: 0.75rem;
    }
    
    .dispute-form-content {
        padding: 0.75rem;
    }
    
    .dispute-form-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .dispute-form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container-fluid">
    <!-- Dashboard Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value"><?= $stats->total_requests ?></div>
            <div class="stat-label">Total Requests</div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value"><?= $stats->pending_requests ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
        
        <div class="stat-card approved">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value"><?= $stats->approved_requests ?></div>
            <div class="stat-label">Approved</div>
        </div>
        
        <div class="stat-card escalated">
            <div class="stat-icon">
                <i class="fas fa-gavel"></i>
            </div>
            <div class="stat-value"><?= $stats->escalated_requests ?></div>
            <div class="stat-label">Under Review</div>
        </div>
        
        <div class="stat-card disputed">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-value"><?= $stats->disputed_requests ?></div>
            <div class="stat-label">Disputed</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form id="filterForm" method="GET">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                           placeholder="Search by job title, cleaner, or reason...">
                </div>
                
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= ($filters['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="escalated" <?= ($filters['status'] ?? '') == 'escalated' ? 'selected' : '' ?>>Escalated</option>
                        <option value="disputed" <?= ($filters['status'] ?? '') == 'disputed' ? 'selected' : '' ?>>Disputed</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="price_min">Min Price</label>
                    <input type="number" id="price_min" name="price_min" 
                           value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>"
                           placeholder="0" step="0.01" min="0">
                </div>
                
                <div class="filter-group">
                    <label for="price_max">Max Price</label>
                    <input type="number" id="price_max" name="price_max" 
                           value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>"
                           placeholder="1000" step="0.01" min="0">
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-header">
            <h3>
                <i class="fas fa-dollar-sign"></i>
                Price Adjustment Requests
            </h3>
            
            <div class="sort-controls">
                <form id="sortForm" method="GET">
                    <input type="hidden" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($filters['status'] ?? '') ?>">
                    <input type="hidden" name="price_min" value="<?= htmlspecialchars($filters['price_min'] ?? '') ?>">
                    <input type="hidden" name="price_max" value="<?= htmlspecialchars($filters['price_max'] ?? '') ?>">
                    
                    <select name="sort" onchange="this.form.submit()">
                        <option value="created_at" <?= ($filters['sort_by'] ?? '') == 'created_at' ? 'selected' : '' ?>>Sort by Date</option>
                        <option value="proposed_price" <?= ($filters['sort_by'] ?? '') == 'proposed_price' ? 'selected' : '' ?>>Sort by Price</option>
                        <option value="expires_at" <?= ($filters['sort_by'] ?? '') == 'expires_at' ? 'selected' : '' ?>>Sort by Expiry</option>
                        <option value="status" <?= ($filters['sort_by'] ?? '') == 'status' ? 'selected' : '' ?>>Sort by Status</option>
                    </select>
                    
                    <select name="order" onchange="this.form.submit()">
                        <option value="desc" <?= ($filters['sort_order'] ?? '') == 'desc' ? 'selected' : '' ?>>Newest First</option>
                        <option value="asc" <?= ($filters['sort_order'] ?? '') == 'asc' ? 'selected' : '' ?>>Oldest First</option>
                    </select>
                </form>
            </div>
        </div>
        
        <?php if (empty($counter_offers)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h4>No Price Adjustment Requests Found</h4>
                <p>Try adjusting your search criteria or check back later for new requests.</p>
            </div>
        <?php else: ?>
            <div class="scrollable-table">
                <table class="enhanced-table">
                    <thead>
                        <tr>
                            <th>Job Details</th>
                            <th>Cleaner</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($counter_offers as $offer): ?>
                            <tr>
                                <td>
                                    <div class="job-details-cell">
                                        <div class="job-title"><?= htmlspecialchars($offer->title) ?></div>
                                        <div class="job-meta">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= htmlspecialchars($offer->address) ?>
                                        </div>
                                        <div class="job-meta">
                                            <i class="fas fa-calendar"></i>
                                            <?= date('M j, Y', strtotime($offer->scheduled_date)) ?>
                                        </div>
                                        <?php if ($offer->reason): ?>
                                            <div class="job-description" title="<?= htmlspecialchars($offer->reason) ?>">
                                                <?= htmlspecialchars(substr($offer->reason, 0, 80)) ?><?= strlen($offer->reason) > 80 ? '...' : '' ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="cleaner-cell">
                                        <div class="cleaner-name">
                                            <?= htmlspecialchars($offer->cleaner_first_name . ' ' . $offer->cleaner_last_name) ?>
                                        </div>
                                        <div class="cleaner-username">
                                            @<?= htmlspecialchars($offer->cleaner_username) ?>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="price-cell">
                                        <div class="original-price">
                                            $<?= number_format($offer->original_price, 2) ?>
                                        </div>
                                        <div class="proposed-price">
                                            $<?= number_format($offer->proposed_price, 2) ?>
                                        </div>
                                        <?php 
                                        $difference = $offer->proposed_price - $offer->original_price;
                                        $difference_text = $difference > 0 ? "+$" . number_format($difference, 2) : "-$" . number_format(abs($difference), 2);
                                        $badge_class = $difference > 0 ? 'text-success' : 'text-danger';
                                        ?>
                                        <div class="price-difference <?= $badge_class ?>">
                                            <?= $difference_text ?>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <span class="status-badge status-<?= $offer->status ?>">
                                        <?= ucfirst($offer->status) ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <div class="expiry-cell">
                                        <div class="expiry-date">
                                            <?= date('M j, Y g:i A', strtotime($offer->expires_at)) ?>
                                        </div>
                                        <?php 
                                        $time_left = strtotime($offer->expires_at) - time();
                                        if ($time_left > 0) {
                                            $hours = floor($time_left / 3600);
                                            $minutes = floor(($time_left % 3600) / 60);
                                            
                                            if ($hours > 24) {
                                                $days = floor($hours / 24);
                                                $time_text = "{$days}d " . ($hours % 24) . "h";
                                                $class = 'info';
                                            } elseif ($hours > 0) {
                                                $time_text = "{$hours}h {$minutes}m";
                                                $class = $hours < 6 ? 'warning' : 'info';
                                            } else {
                                                $time_text = "{$minutes}m";
                                                $class = 'danger';
                                            }
                                            
                                            echo '<div class="time-remaining ' . $class . '">' . $time_text . ' left</div>';
                                        } else {
                                            echo '<div class="time-remaining danger">Expired</div>';
                                        }
                                        ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <?php if ($offer->status == 'pending'): ?>
                                        <div class="action-buttons">
                                            <button type="button" class="action-btn accept" 
                                                    onclick="acceptPriceAdjustment(<?= $offer->counter_offer_id ?>)">
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                            <button type="button" class="action-btn dispute" 
                                                    onclick="toggleDisputeForm(<?= $offer->counter_offer_id ?>)">
                                                <i class="fas fa-gavel"></i> Dispute
                                            </button>
                                        </div>
                                        <div class="help-text">
                                            Accept or dispute for moderator review
                                        </div>
                                        
                                        <!-- Inline Dispute Form -->
                                        <div id="dispute-form-<?= $offer->counter_offer_id ?>" class="dispute-form" style="display: none;">
                                            <div class="dispute-form-content">
                                                <h6 class="mb-3"><i class="fas fa-exclamation-triangle text-warning"></i> Dispute Price Adjustment</h6>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="dispute_reason_<?= $offer->counter_offer_id ?>" class="form-label">Why do you dispute this price adjustment?</label>
                                                    
                                                    <!-- Custom Dropdown -->
                                                    <div class="custom-dropdown" data-offer-id="<?= $offer->counter_offer_id ?>">
                                                        <div class="dropdown-selected" onclick="toggleCustomDropdown(<?= $offer->counter_offer_id ?>)">
                                                            <span class="selected-text">Select a reason...</span>
                                                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                                                        </div>
                                                        <div class="dropdown-options" id="dropdown-options-<?= $offer->counter_offer_id ?>" style="display: none;">
                                                            <div class="dropdown-option" data-value="unexpected_work" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'unexpected_work', 'The cleaner is requesting payment for work that wasn\'t agreed upon')">
                                                                The cleaner is requesting payment for work that wasn't agreed upon
                                                            </div>
                                                            <div class="dropdown-option" data-value="overpriced" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'overpriced', 'The additional work doesn\'t justify this price increase')">
                                                                The additional work doesn't justify this price increase
                                                            </div>
                                                            <div class="dropdown-option" data-value="misunderstanding" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'misunderstanding', 'There seems to be a misunderstanding about the scope of work')">
                                                                There seems to be a misunderstanding about the scope of work
                                                            </div>
                                                            <div class="dropdown-option" data-value="missing_details" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'missing_details', 'Important details about the job were not provided initially')">
                                                                Important details about the job were not provided initially
                                                            </div>
                                                            <div class="dropdown-option" data-value="quality_issues" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'quality_issues', 'The work quality does not meet expectations for the additional cost')">
                                                                The work quality does not meet expectations for the additional cost
                                                            </div>
                                                            <div class="dropdown-option" data-value="time_exceeded" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'time_exceeded', 'The cleaner took much longer than expected without prior notice')">
                                                                The cleaner took much longer than expected without prior notice
                                                            </div>
                                                            <div class="dropdown-option" data-value="damage_caused" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'damage_caused', 'The cleaner caused damage that needs to be addressed before payment')">
                                                                The cleaner caused damage that needs to be addressed before payment
                                                            </div>
                                                            <div class="dropdown-option" data-value="incomplete_work" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'incomplete_work', 'The additional work was not completed as requested')">
                                                                The additional work was not completed as requested
                                                            </div>
                                                            <div class="dropdown-option" data-value="equipment_damage" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'equipment_damage', 'The cleaner damaged equipment or furniture during the additional work')">
                                                                The cleaner damaged equipment or furniture during the additional work
                                                            </div>
                                                            <div class="dropdown-option" data-value="unauthorized_purchases" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'unauthorized_purchases', 'The cleaner made purchases without permission and wants reimbursement')">
                                                                The cleaner made purchases without permission and wants reimbursement
                                                            </div>
                                                            <div class="dropdown-option" data-value="schedule_disruption" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'schedule_disruption', 'The additional work significantly disrupted the agreed schedule')">
                                                                The additional work significantly disrupted the agreed schedule
                                                            </div>
                                                            <div class="dropdown-option" data-value="safety_concerns" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'safety_concerns', 'The additional work was performed unsafely or improperly')">
                                                                The additional work was performed unsafely or improperly
                                                            </div>
                                                            <div class="dropdown-option" data-value="cleanup_required" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'cleanup_required', 'The cleaner left a mess that requires additional cleanup')">
                                                                The cleaner left a mess that requires additional cleanup
                                                            </div>
                                                            <div class="dropdown-option" data-value="communication_issues" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'communication_issues', 'The cleaner failed to communicate about additional work before starting')">
                                                                The cleaner failed to communicate about additional work before starting
                                                            </div>
                                                            <div class="dropdown-option" data-value="other" onclick="selectDropdownOption(<?= $offer->counter_offer_id ?>, 'other', 'Other reason (please explain below)')">
                                                                Other reason (please explain below)
                                                            </div>
                                                        </div>
                                                        <input type="hidden" id="dispute_reason_<?= $offer->counter_offer_id ?>" name="dispute_reason" value="" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group mb-3">
                                                    <label for="dispute_details_<?= $offer->counter_offer_id ?>" class="form-label">Provide additional details (Required)</label>
                                                    <textarea class="form-control" id="dispute_details_<?= $offer->counter_offer_id ?>" rows="3" 
                                                              placeholder="Please explain why you dispute this price adjustment and provide any relevant context..." required></textarea>
                                                </div>
                                                
                                                <div class="alert alert-warning mb-3">
                                                    <i class="fas fa-info-circle"></i>
                                                    <strong>Note:</strong> When you dispute a price adjustment, the cleaner will be notified and a moderator will review both perspectives for a fair resolution.
                                                </div>
                                                
                                                <div class="dispute-form-actions">
                                                    <button type="button" class="btn btn-warning btn-sm me-2" 
                                                            onclick="submitDispute(<?= $offer->counter_offer_id ?>)">
                                                        <i class="fas fa-gavel"></i> Submit Dispute
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm" 
                                                            onclick="toggleDisputeForm(<?= $offer->counter_offer_id ?>)">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    <?php elseif ($offer->status == 'approved'): ?>
                                        <div class="text-center">
                                            <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                                            <div class="text-success mt-1">Approved</div>
                                        </div>
                                    <?php elseif ($offer->status == 'escalated'): ?>
                                        <div class="text-center">
                                            <i class="fas fa-gavel text-warning" style="font-size: 1.5rem;"></i>
                                            <div class="text-warning mt-1">Under Review</div>
                                        </div>
                                    <?php elseif ($offer->status == 'disputed'): ?>
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 1.5rem;"></i>
                                            <div class="text-danger mt-1">Disputed</div>
                                            <div class="text-muted small mt-1">Awaiting moderator review</div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">Respond to Price Adjustment Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="responseForm">
                    <input type="hidden" id="offerId" name="counter_offer_id">
                    <input type="hidden" id="actionType" name="action_type">
                    
                    <!-- Price Adjustment Details -->
                    <div class="alert alert-light border">
                        <h6 class="mb-2"><i class="fas fa-info-circle text-info"></i> Price Adjustment Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Original Price:</strong> $<span id="originalPrice"></span>
                            </div>
                            <div class="col-md-6">
                                <strong>Proposed Price:</strong> $<span id="proposedPrice"></span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Cleaner's Reason:</strong>
                            <p class="mb-0 text-muted" id="cleanerReason">No reason provided</p>
                        </div>
                    </div>
                    
                    <!-- Host Response Section -->
                    <div id="disputeSection" style="display: none;">
                        <div class="form-group">
                            <label for="hostDisputeReason">Why do you dispute this price adjustment?</label>
                            <select class="form-control" id="hostDisputeReason" name="dispute_reason" required>
                                <option value="">Select a reason...</option>
                                <option value="unexpected_work">The cleaner is requesting payment for work that wasn't agreed upon</option>
                                <option value="overpriced">The additional work doesn't justify this price increase</option>
                                <option value="misunderstanding">There seems to be a misunderstanding about the scope of work</option>
                                <option value="missing_details">Important details about the job were not provided initially</option>
                                <option value="other">Other reason (please explain below)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="hostDisputeDetails">Provide additional details (Required)</label>
                            <textarea class="form-control" id="hostDisputeDetails" name="dispute_details" rows="4" 
                                      placeholder="Please explain why you dispute this price adjustment and provide any relevant context..." required></textarea>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Dispute Process:</strong> When you dispute a price adjustment, a moderator will review both perspectives (yours and the cleaner's) and make a fair decision based on the evidence provided.
                        </div>
                    </div>
                    
                    <div id="acceptSection" style="display: none;">
                        <div class="form-group">
                            <label for="acceptComments">Comments (Optional)</label>
                            <textarea class="form-control" id="acceptComments" name="accept_comments" rows="3" 
                                      placeholder="Add any comments about accepting this price adjustment..."></textarea>
                        </div>
                        
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Acceptance:</strong> By accepting this price adjustment, you agree to pay the proposed amount. The cleaner will be notified immediately.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAccept" style="display: none;">
                    <i class="fas fa-check"></i> Accept Price Adjustment
                </button>
                <button type="button" class="btn btn-warning" id="confirmDispute" style="display: none;">
                    <i class="fas fa-gavel"></i> Submit Dispute
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function acceptPriceAdjustment(offerId) {
    // Get offer details from the table row
    const row = $(`button[onclick*="${offerId}"]`).closest('tr');
    const originalPrice = row.find('.original-price').text().replace('$', '').replace(',', '');
    const proposedPrice = row.find('.proposed-price').text().replace('$', '').replace(',', '');
    const reason = row.find('.job-description').text();
    
    $('#offerId').val(offerId);
    $('#actionType').val('accept');
    $('#originalPrice').text(parseFloat(originalPrice).toFixed(2));
    $('#proposedPrice').text(parseFloat(proposedPrice).toFixed(2));
    $('#cleanerReason').text(reason || 'No reason provided');
    
    $('#disputeSection').hide();
    $('#acceptSection').show();
    $('#confirmAccept').show();
    $('#confirmDispute').hide();
    
    $('#responseModalLabel').text('Accept Price Adjustment');
    $('#responseModal').modal('show');
}

function toggleDisputeForm(offerId) {
    const form = $(`#dispute-form-${offerId}`);
    if (form.is(':visible')) {
        form.slideUp();
    } else {
        // Hide all other dispute forms first
        $('.dispute-form').slideUp();
        // Show this one
        form.slideDown();
    }
}

function submitDispute(offerId) {
    const disputeReason = $(`#dispute_reason_${offerId}`).val();
    const disputeDetails = $(`#dispute_details_${offerId}`).val();
    
    if (!disputeReason || !disputeDetails.trim()) {
        alert('Please select a dispute reason and provide details.');
        return;
    }
    
    const formData = {
        counter_offer_id: offerId,
        action_type: 'dispute',
        dispute_reason: disputeReason,
        dispute_details: disputeDetails
    };
    
    $.ajax({
        url: '<?= base_url('counter-offers/respond') ?>',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Price adjustment disputed successfully! The cleaner has been notified.');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while submitting the dispute.');
        }
    });
}

// Custom dropdown functions
function toggleCustomDropdown(offerId) {
    const dropdown = $(`.custom-dropdown[data-offer-id="${offerId}"]`);
    const options = $(`#dropdown-options-${offerId}`);
    const selected = dropdown.find('.dropdown-selected');
    
    // Close all other dropdowns first
    $('.dropdown-options').not(options).slideUp();
    $('.dropdown-selected').removeClass('active');
    
    // Toggle current dropdown
    if (options.is(':visible')) {
        options.slideUp();
        selected.removeClass('active');
    } else {
        options.slideDown();
        selected.addClass('active');
    }
}

function selectDropdownOption(offerId, value, text) {
    const dropdown = $(`.custom-dropdown[data-offer-id="${offerId}"]`);
    const selectedText = dropdown.find('.selected-text');
    const hiddenInput = $(`#dispute_reason_${offerId}`);
    const options = $(`#dropdown-options-${offerId}`);
    const selected = dropdown.find('.dropdown-selected');
    
    // Update display
    selectedText.text(text).removeClass('placeholder');
    hiddenInput.val(value);
    
    // Update option selection
    dropdown.find('.dropdown-option').removeClass('selected');
    dropdown.find(`[data-value="${value}"]`).addClass('selected');
    
    // Close dropdown
    options.slideUp();
    selected.removeClass('active');
}

// Close dropdown when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('.custom-dropdown').length) {
        $('.dropdown-options').slideUp();
        $('.dropdown-selected').removeClass('active');
    }
});

// Accept button handler
$('#confirmAccept').click(function() {
    const formData = {
        counter_offer_id: $('#offerId').val(),
        action_type: 'accept',
        accept_comments: $('#acceptComments').val()
    };
    
    $.ajax({
        url: '<?= base_url('counter-offers/respond') ?>',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#responseModal').modal('hide');
                alert('Price adjustment accepted successfully!');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while accepting the price adjustment.');
        }
    });
});


// Reset form when modal is closed
$('#responseModal').on('hidden.bs.modal', function () {
    $('#responseForm')[0].reset();
    $('#disputeSection').hide();
    $('#acceptSection').hide();
    $('#confirmAccept').hide();
    $('#confirmDispute').hide();
});

// Auto-submit filter form on search input change (with debounce)
let searchTimeout;
$('#search').on('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        $('#filterForm').submit();
    }, 500);
});

// Auto-submit on filter changes
$('#status, #price_min, #price_max').on('change', function() {
    $('#filterForm').submit();
});

</script>