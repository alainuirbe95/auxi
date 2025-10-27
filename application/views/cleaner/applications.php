<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-file-invoice mr-2"></i>My Job Applications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <?php if (isset($crumb['active']) && $crumb['active']): ?>
                            <li class="breadcrumb-item active"><?php echo $crumb['title']; ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url($crumb['url']); ?>"><?php echo $crumb['title']; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo count($pending_offers ?? []); ?></h3>
                        <p>Pending Review</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo count($accepted_offers ?? []); ?></h3>
                        <p>Accepted</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo count($declined_offers ?? []); ?></h3>
                        <p>Not Selected</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filters</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('cleaner/applications'); ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date From</label>
                                <input type="date" name="date_from" class="form-control" value="<?php echo $this->input->get('date_from'); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" name="date_to" class="form-control" value="<?php echo $this->input->get('date_to'); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Applications</option>
                                    <option value="pending" <?php echo $this->input->get('status') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="accepted" <?php echo $this->input->get('status') == 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                    <option value="declined" <?php echo $this->input->get('status') == 'declined' ? 'selected' : ''; ?>>Not Selected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Applications List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-2"></i>All Applications</h3>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($offers)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job</th>
                                <th>Host</th>
                                <th>Your Offer</th>
                                <th>Job Price</th>
                                <th>Job Status</th>
                                <th>Application Status</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offers as $offer): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($offer->job_title); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php 
                                        // Only show city/state for privacy - full address shown after assignment
                                        $location_parts = [];
                                        if (!empty($offer->city)) $location_parts[] = $offer->city;
                                        if (!empty($offer->state)) $location_parts[] = $offer->state;
                                        echo htmlspecialchars(implode(', ', $location_parts));
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($offer->host_first_name . ' ' . $offer->host_last_name); ?></strong>
                                    <br>
                                    <small class="text-muted">@<?php echo htmlspecialchars($offer->host_username); ?></small>
                                </td>
                                <td>
                                    <strong class="text-success">$<?php echo number_format($offer->amount, 2); ?></strong>
                                </td>
                                <td>
                                    <span class="text-muted">$<?php echo number_format($offer->suggested_price, 2); ?></span>
                                </td>
                                <td>
                                    <?php
                                    // Determine if cleaner was selected for this job
                                    $was_selected = $offer->status === 'accepted' || ($offer->status === 'pending' && in_array($offer->job_status, ['assigned', 'in_progress']));
                                    
                                    // Only show job status if selected or still open, otherwise hide for privacy
                                    if ($was_selected):
                                        $job_status_badges = [
                                            'assigned' => 'badge-info',
                                            'in_progress' => 'badge-warning',
                                            'completed' => 'badge-success',
                                            'closed' => 'badge-secondary'
                                        ];
                                        $badge_class = $job_status_badges[$offer->job_status] ?? 'badge-secondary';
                                    ?>
                                        <span class="badge <?php echo $badge_class; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $offer->job_status)); ?>
                                        </span>
                                    <?php elseif ($offer->job_status === 'open'): ?>
                                        <span class="badge badge-primary">Open</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-lock"></i> Not Selected
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($offer->status === 'pending' && $offer->job_status === 'open'): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pending Review
                                        </span>
                                    <?php elseif ($offer->status === 'accepted' || ($offer->status === 'pending' && in_array($offer->job_status, ['assigned', 'in_progress']))): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Accepted
                                        </span>
                                    <?php elseif ($offer->status === 'declined' || ($offer->status === 'pending' && in_array($offer->job_status, ['completed', 'closed']))): ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle"></i> Not Selected
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <?php echo ucfirst($offer->status); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php 
                                        $offer_date = new DateTime($offer->created_at);
                                        echo $offer_date->format('M j, Y');
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('cleaner/job/' . $offer->job_id); ?>" class="btn btn-sm btn-info" title="View Job">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">You haven't applied to any jobs yet</p>
                    <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn btn-primary">
                        <i class="fas fa-search mr-2"></i>Browse Jobs
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</section>

