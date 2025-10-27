<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
  <!-- Modern Header Section -->
  <div class="modern-header-section">
    <div class="header-content">
      <h1 class="page-title">
        <i class="fas fa-dollar-sign mr-3"></i>
        Pricing Settings
      </h1>
      <p class="page-subtitle">
        Configure pricing parameters for job payments
      </p>
    </div>
  </div>

  <?php if (!$table_exists): ?>
    <div class="alert alert-warning">
      <h5><i class="fas fa-exclamation-triangle me-2"></i>Pricing Settings Table Not Found</h5>
      <p>Please run the SQL migration file <code>create_pricing_settings_table.sql</code> to create the pricing settings table.</p>
    </div>
  <?php endif; ?>

  <!-- Pricing Settings Form -->
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-cog mr-2"></i>
            Payment Structure Configuration
          </h3>
        </div>
        <div class="modern-card-body">
          <?php if (validation_errors() || $this->session->flashdata('text')): ?>
            <div class="alert alert-<?php echo $this->session->flashdata('type') ?: 'danger'; ?>">
              <?php echo $this->session->flashdata('text') ?: validation_errors(); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="<?php echo base_url('admin/pricing_settings'); ?>">
            <div class="form-section">
              <h6 class="section-title">
                <i class="fas fa-info-circle me-2"></i>
                Pricing Parameters
              </h6>

              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="base_charge" class="form-label">Base Charge ($) *</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control form-control-modern" id="base_charge" name="base_charge" 
                           value="<?php echo number_format($settings['base_charge'], 2, '.', ''); ?>" 
                           step="0.01" min="0" required>
                  </div>
                  <div class="form-text">Fixed amount charged on all jobs</div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="tax_percent" class="form-label">Tax Rate (%) *</label>
                  <div class="input-group">
                    <input type="number" class="form-control form-control-modern" id="tax_percent" name="tax_percent" 
                           value="<?php echo number_format($settings['tax_percent'], 2, '.', ''); ?>" 
                           step="0.01" min="0" max="100" required>
                    <span class="input-group-text">%</span>
                  </div>
                  <div class="form-text">Percentage of suggested price</div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="app_percent" class="form-label">App Fee (%) *</label>
                  <div class="input-group">
                    <input type="number" class="form-control form-control-modern" id="app_percent" name="app_percent" 
                           value="<?php echo number_format($settings['app_percent'], 2, '.', ''); ?>" 
                           step="0.01" min="0" max="100" required>
                    <span class="input-group-text">%</span>
                  </div>
                  <div class="form-text">Application fee percentage</div>
                </div>
              </div>
            </div>

            <!-- Pricing Example -->
            <div class="form-section">
              <h6 class="section-title">
                <i class="fas fa-calculator me-2"></i>
                Payment Calculator Preview
              </h6>

              <div class="mb-3">
                <label for="example_price" class="form-label">Test Suggested Price ($)</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control form-control-modern" id="example_price" 
                         value="300" step="0.01" min="0">
                </div>
                <div class="form-text">Enter any amount to see the breakdown calculation</div>
              </div>

              <div class="pricing-example-box">
                <p class="mb-3"><strong>Payment Breakdown:</strong></p>
                
                <div class="breakdown-item">
                  <span class="breakdown-label">Suggested Price:</span>
                  <span class="breakdown-value">$<span id="example-price-display">300.00</span></span>
                </div>
                <div class="breakdown-item breakdown-charge">
                  <span class="breakdown-label">- Base Charge:</span>
                  <span class="breakdown-value">-$<span id="example-base"><?php echo number_format($settings['base_charge'], 2); ?></span></span>
                </div>
                <div class="breakdown-item breakdown-charge">
                  <span class="breakdown-label">- Tax (<span id="example-tax-pct"><?php echo number_format($settings['tax_percent'], 2); ?></span>%):</span>
                  <span class="breakdown-value">-$<span id="example-tax"><?php echo number_format(300 * $settings['tax_percent'] / 100, 2); ?></span></span>
                </div>
                <div class="breakdown-item breakdown-charge">
                  <span class="breakdown-label">- App Fee (<span id="example-app-pct"><?php echo number_format($settings['app_percent'], 2); ?></span>%):</span>
                  <span class="breakdown-value">-$<span id="example-app"><?php echo number_format(300 * $settings['app_percent'] / 100, 2); ?></span></span>
                </div>
                <div class="breakdown-item breakdown-total">
                  <span class="breakdown-label"><strong>Cleaner Payout:</strong></span>
                  <span class="breakdown-value"><strong>$<span id="example-payout"><?php echo number_format(300 - $settings['base_charge'] - (300 * $settings['tax_percent'] / 100) - (300 * $settings['app_percent'] / 100), 2); ?></span></strong></span>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
              <button type="submit" class="btn btn-modern btn-primary">
                <i class="fas fa-save me-2"></i>
                Save Settings
              </button>
              <a href="<?php echo base_url('admin/dashboard'); ?>" class="btn btn-modern btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Dashboard
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Modern Header Section */
.modern-header-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
  padding: 2.5rem 2rem;
  margin-bottom: 2rem;
  color: white;
  position: relative;
  overflow: hidden;
}

.modern-header-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  opacity: 0.3;
}

.header-content {
  position: relative;
  z-index: 1;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.page-subtitle {
  font-size: 1rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
}

/* Modern Card */
.modern-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  margin-bottom: 2rem;
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #e9ecef;
}

.modern-card-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: #495057;
}

.modern-card-body {
  padding: 2rem;
}

/* Form Section */
.form-section {
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #e9ecef;
}

.form-section:last-child {
  border-bottom: none;
}

.section-title {
  color: #495057;
  font-weight: 600;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #667eea;
  display: inline-block;
}

/* Form Controls */
.form-control-modern {
  border: 2px solid #e9ecef;
  border-radius: 8px;
  padding: 0.5rem 0.75rem;
  transition: all 0.2s ease;
}

.form-control-modern:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

.form-text {
  font-size: 0.875rem;
  color: #6c757d;
  margin-top: 0.25rem;
}

.input-group-text {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  color: #495057;
  font-weight: 500;
}

/* Pricing Example Box */
.pricing-example-box {
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  border: 2px solid #667eea;
  border-radius: 12px;
  padding: 1.5rem;
}

.breakdown-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  font-size: 0.9rem;
}

.breakdown-item.breakdown-charge {
  color: #dc3545;
}

.breakdown-item.breakdown-total {
  border-top: 2px solid #667eea;
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  font-size: 1rem;
  color: #667eea;
}

.breakdown-label {
  color: #495057;
}

.breakdown-value {
  font-weight: 600;
  color: inherit;
}

/* Buttons */
.btn-modern {
  border-radius: 8px;
  padding: 0.5rem 1.5rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-modern:hover {
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

.btn-modern.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
}

.btn-modern.btn-secondary {
  background: #6c757d;
  border: none;
  color: white;
}

/* Form Actions */
.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #e9ecef;
  display: flex;
  gap: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .modern-header-section {
    padding: 2rem 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn-modern {
    width: 100%;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time calculation update
    const baseChargeInput = document.getElementById('base_charge');
    const taxPercentInput = document.getElementById('tax_percent');
    const appPercentInput = document.getElementById('app_percent');
    const examplePriceInput = document.getElementById('example_price');
    
    const examplePriceDisplay = document.getElementById('example-price-display');
    const exampleBase = document.getElementById('example-base');
    const exampleTaxPct = document.getElementById('example-tax-pct');
    const exampleTax = document.getElementById('example-tax');
    const exampleAppPct = document.getElementById('example-app-pct');
    const exampleApp = document.getElementById('example-app');
    const examplePayout = document.getElementById('example-payout');
    
    function updateExample() {
        const baseCharge = parseFloat(baseChargeInput.value) || 0;
        const taxPercent = parseFloat(taxPercentInput.value) || 0;
        const appPercent = parseFloat(appPercentInput.value) || 0;
        const suggestedPrice = parseFloat(examplePriceInput.value) || 0;
        
        // Don't calculate if price is invalid
        if (suggestedPrice <= 0) {
            examplePriceDisplay.textContent = 'Failed to load';
            exampleBase.textContent = 'Failed to load';
            exampleTax.textContent = 'Failed to load';
            exampleApp.textContent = 'Failed to load';
            examplePayout.textContent = 'Failed to load';
            return;
        }
        
        const taxAmount = (suggestedPrice * taxPercent) / 100;
        const appAmount = (suggestedPrice * appPercent) / 100;
        const payout = suggestedPrice - baseCharge - taxAmount - appAmount;
        
        // Update display
        examplePriceDisplay.textContent = suggestedPrice.toFixed(2);
        exampleBase.textContent = baseCharge.toFixed(2);
        exampleTaxPct.textContent = taxPercent.toFixed(2);
        exampleTax.textContent = taxAmount.toFixed(2);
        exampleAppPct.textContent = appPercent.toFixed(2);
        exampleApp.textContent = appAmount.toFixed(2);
        examplePayout.textContent = payout.toFixed(2);
    }
    
    // Add event listeners
    baseChargeInput.addEventListener('input', updateExample);
    taxPercentInput.addEventListener('input', updateExample);
    appPercentInput.addEventListener('input', updateExample);
    examplePriceInput.addEventListener('input', updateExample);
    
    // Initialize calculation
    updateExample();
});
</script>

