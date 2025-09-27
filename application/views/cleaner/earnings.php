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

/* Earnings Dashboard Styles */
.earnings-container {
    padding: 2rem 0;
}

.page-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    text-align: center;
}

.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.page-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Date Range Filter */
.date-filter-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.date-filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
}

.date-filter-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.date-filter-body {
    padding: 2rem;
}

.date-range-form {
    display: flex;
    align-items: end;
    gap: 1rem;
    flex-wrap: wrap;
}

.date-input-group {
    flex: 1;
    min-width: 200px;
}

.date-input-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.date-input-group input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.date-input-group input:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    outline: none;
}

.date-filter-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-filter {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    color: white;
    text-decoration: none;
}

.btn-reset {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-reset:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    color: white;
    text-decoration: none;
}

/* Summary Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.summary-card.total::before {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.summary-card.period::before {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.summary-card.monthly::before {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}

.summary-card.average::before {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.summary-card-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}

.summary-card.total .summary-card-icon {
    color: #007bff;
}

.summary-card.period .summary-card-icon {
    color: #28a745;
}

.summary-card.monthly .summary-card-icon {
    color: #ffc107;
}

.summary-card.average .summary-card-icon {
    color: #17a2b8;
}

.summary-card-title {
    font-size: 0.9rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.summary-card-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.summary-card-subtitle {
    font-size: 0.85rem;
    color: #6c757d;
}

/* Earnings Table */
.earnings-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
}

.table-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.enhanced-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.enhanced-table thead {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.enhanced-table thead th {
    padding: 1rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border: none;
    white-space: nowrap;
}

.enhanced-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.enhanced-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.enhanced-table tbody td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border: none;
}

/* Job Title Styling */
.job-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.job-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-address {
    color: #495057;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Price Styling */
.price-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: #28a745;
}

.price-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Date Styling */
.date-info {
    text-align: center;
}

.date-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.date-value {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

/* Host Info Styling */
.host-info {
    text-align: center;
}

.host-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.host-username {
    color: #6c757d;
    font-size: 0.8rem;
    font-style: italic;
}

/* Status Badge Styles */
.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-completed {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.status-disputed {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.status-closed {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* Scrollable table container */
.scrollable-table {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.scrollable-table::-webkit-scrollbar {
    height: 8px;
}

.scrollable-table::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.scrollable-table::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.scrollable-table::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .date-range-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .date-input-group {
        min-width: auto;
    }
    
    .date-filter-actions {
        justify-content: center;
    }
    
    .summary-cards {
        grid-template-columns: 1fr;
    }
    
    .enhanced-table {
        font-size: 0.8rem;
    }
    
    .enhanced-table thead th,
    .enhanced-table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    .job-title {
        font-size: 1rem;
    }
    
    .price-amount {
        font-size: 1rem;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h2>
                    <i class="fas fa-dollar-sign me-2"></i>
                    My Earnings
                </h2>
                <p>Track your earnings and payment history</p>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="date-filter-card">
                <div class="date-filter-header">
                    <h5>
                        <i class="fas fa-calendar-alt me-2"></i>
                        Date Range Filter
                    </h5>
                </div>
                <div class="date-filter-body">
                    <form method="GET" action="<?php echo base_url('cleaner/earnings'); ?>" class="date-range-form">
                        <div class="date-input-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="<?php echo htmlspecialchars($start_date); ?>"
                                   required>
                        </div>
                        <div class="date-input-group">
                            <label for="end_date">End Date</label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="<?php echo htmlspecialchars($end_date); ?>"
                                   required>
                        </div>
                        <div class="date-filter-actions">
                            <button type="submit" class="btn-filter">
                                <i class="fas fa-filter"></i>
                                Apply Filter
                            </button>
                            <a href="<?php echo base_url('cleaner/earnings'); ?>" class="btn-reset">
                                <i class="fas fa-undo"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="summary-cards">
                <!-- Total Earnings (All Time) -->
                <div class="summary-card total">
                    <div class="summary-card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="summary-card-title">Total Earnings</div>
                    <div class="summary-card-value">$<?php echo number_format($earnings_summary['total_earnings'], 2); ?></div>
                    <div class="summary-card-subtitle">All time (<?php echo $earnings_summary['total_jobs']; ?> jobs)</div>
                </div>

                <!-- Period Earnings -->
                <div class="summary-card period">
                    <div class="summary-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="summary-card-title">Selected Period</div>
                    <div class="summary-card-value">$<?php echo number_format($earnings_data['total_earnings'], 2); ?></div>
                    <div class="summary-card-subtitle"><?php echo $earnings_data['total_jobs']; ?> jobs from <?php echo date('M j', strtotime($start_date)); ?> to <?php echo date('M j, Y', strtotime($end_date)); ?></div>
                </div>

                <!-- This Month -->
                <div class="summary-card monthly">
                    <div class="summary-card-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="summary-card-title">This Month</div>
                    <div class="summary-card-value">$<?php echo number_format($earnings_summary['this_month_earnings'], 2); ?></div>
                    <div class="summary-card-subtitle"><?php echo $earnings_summary['this_month_jobs']; ?> jobs completed</div>
                </div>

                <!-- Average Earnings -->
                <div class="summary-card average">
                    <div class="summary-card-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="summary-card-title">Average per Job</div>
                    <div class="summary-card-value">$<?php echo number_format($earnings_data['average_earnings'], 2); ?></div>
                    <div class="summary-card-subtitle">Selected period average</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Table -->
    <div class="row">
        <div class="col-12">
            <div class="earnings-table-container">
                <div class="table-header">
                    <h5>
                        <i class="fas fa-list-alt me-2"></i>
                        Payment History (<?php echo count($earnings_data['jobs']); ?> jobs)
                    </h5>
                </div>
                
                <?php if (!empty($earnings_data['jobs'])): ?>
                <div class="scrollable-table">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>Job Details</th>
                                <th>Host</th>
                                <th>Amount Earned</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($earnings_data['jobs'] as $job): ?>
                            <tr>
                                <td>
                                    <div class="job-title"><?php echo htmlspecialchars($job->title); ?></div>
                                    <div class="job-description"><?php echo htmlspecialchars($job->description); ?></div>
                                    <div class="job-address">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($job->address . ', ' . $job->city . ', ' . $job->state); ?>
                                    </div>
                                </td>
                                <td class="host-info">
                                    <div class="host-name">
                                        <?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?>
                                    </div>
                                    <div class="host-username">
                                        @<?php echo htmlspecialchars($job->host_username); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-amount">
                                        $<?php echo number_format($job->payment_amount ?: ($job->final_price ?: $job->accepted_price), 2); ?>
                                    </div>
                                    <div class="price-label">Payment Amount</div>
                                </td>
                                <td class="date-info">
                                    <div class="date-label">Paid On</div>
                                    <div class="date-value">
                                        <?php echo date('M j, Y', strtotime($job->payment_released_at)); ?>
                                    </div>
                                    <div class="date-value" style="font-size: 0.8rem; color: #6c757d;">
                                        <?php echo date('g:i A', strtotime($job->payment_released_at)); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $status_class = 'status-completed';
                                    $status_text = 'Completed';
                                    
                                    // Check job status
                                    if ($job->status === 'disputed') {
                                        $status_class = 'status-disputed';
                                        $status_text = 'Disputed';
                                    } elseif ($job->status === 'closed') {
                                        $status_class = 'status-closed';
                                        $status_text = 'Closed';
                                    } elseif ($job->status === 'completed') {
                                        $status_class = 'status-completed';
                                        $status_text = 'Completed';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-dollar-sign"></i>
                    <h4>No Earnings Found</h4>
                    <p>No payments were released for the selected date range. Complete some jobs to start earning!</p>
                    <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-search me-2"></i>
                        Browse Available Jobs
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Set default date range to current month if not set
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('start_date') && !urlParams.has('end_date')) {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
        document.getElementById('end_date').value = today.toISOString().split('T')[0];
    }
    
    console.log('Earnings dashboard loaded');
});
</script>
