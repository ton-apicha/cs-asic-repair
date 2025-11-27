<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i><?= lang('App.editJobCard') ?></h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
        </a>
    </div>
</div>

<form action="<?= base_url('jobs/update/' . $job['id']) ?>" method="POST" id="jobForm">
    <?= csrf_field() ?>
    
    <div class="row g-4">
        <!-- Job Info -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clipboard-check me-2"></i><?= lang('App.jobDetails') ?></span>
                    <span class="badge bg-primary"><?= esc($job['job_id']) ?></span>
                </div>
                <div class="card-body">
                    <!-- Customer & Asset Info (Read-only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><?= lang('App.customer') ?></small>
                                <strong><?= esc($customer['name']) ?></strong>
                                <div class="small text-muted"><?= esc($customer['phone']) ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><?= lang('App.asset') ?></small>
                                <strong><?= esc($asset['brand_model']) ?></strong>
                                <div class="small"><code><?= esc($asset['serial_number']) ?></code></div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Editable Fields -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.jobStatus') ?></label>
                            <select class="form-select" name="status">
                                <option value="new_checkin" <?= $job['status'] === 'new_checkin' ? 'selected' : '' ?>><?= lang('App.statusNewCheckin') ?></option>
                                <option value="pending_repair" <?= $job['status'] === 'pending_repair' ? 'selected' : '' ?>><?= lang('App.statusPendingRepair') ?></option>
                                <option value="in_progress" <?= $job['status'] === 'in_progress' ? 'selected' : '' ?>><?= lang('App.statusInProgress') ?></option>
                                <option value="repair_done" <?= $job['status'] === 'repair_done' ? 'selected' : '' ?>><?= lang('App.statusRepairDone') ?></option>
                                <option value="ready_handover" <?= $job['status'] === 'ready_handover' ? 'selected' : '' ?>><?= lang('App.statusReadyHandover') ?></option>
                                <option value="delivered" <?= $job['status'] === 'delivered' ? 'selected' : '' ?>><?= lang('App.statusDelivered') ?></option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.jobTechnician') ?></label>
                            <select class="form-select" name="technician_id">
                                <option value="">-- ยังไม่มอบหมาย --</option>
                                <?php foreach ($technicians ?? [] as $tech): ?>
                                    <option value="<?= $tech['id'] ?>" <?= $job['technician_id'] == $tech['id'] ? 'selected' : '' ?>>
                                        <?= esc($tech['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required"><?= lang('App.jobSymptom') ?></label>
                        <textarea class="form-control" name="symptom" rows="3" required><?= old('symptom', $job['symptom']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.jobDiagnosis') ?></label>
                        <textarea class="form-control" name="diagnosis" rows="3"><?= old('diagnosis', $job['diagnosis']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.jobSolution') ?></label>
                        <textarea class="form-control" name="solution" rows="3"><?= old('solution', $job['solution']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.notes') ?></label>
                        <textarea class="form-control" name="notes" rows="2"><?= old('notes', $job['notes']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pricing & Warranty -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-currency-dollar me-2"></i><?= lang('App.grandTotal') ?>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.laborCost') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">฿</span>
                            <input type="number" class="form-control" name="labor_cost" 
                                   value="<?= old('labor_cost', $job['labor_cost']) ?>" min="0" step="0.01" id="laborCost">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.partsCost') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">฿</span>
                            <input type="text" class="form-control" value="<?= number_format($job['parts_cost'], 2) ?>" readonly disabled>
                        </div>
                        <div class="form-text">Edit parts in Job View page</div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.subtotal') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">฿</span>
                            <input type="text" class="form-control" id="subtotal" 
                                   value="<?= number_format($job['subtotal'], 2) ?>" readonly disabled>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="include_vat" id="includeVat" value="1"
                               <?= $job['vat_amount'] > 0 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="includeVat">
                            <?= lang('App.vatInclusive') ?>
                        </label>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.vatAmount') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">฿</span>
                            <input type="text" class="form-control" id="vatAmount" 
                                   value="<?= number_format($job['vat_amount'], 2) ?>" readonly disabled>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-success bg-opacity-10 rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><?= lang('App.grandTotal') ?></strong>
                            <span class="fs-4 fw-bold text-success">฿<?= number_format($job['grand_total'], 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Warranty Claim -->
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-shield-check me-2"></i><?= lang('App.warrantyClaim') ?>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_warranty_claim" id="isWarrantyClaim" value="1"
                               <?= $job['is_warranty_claim'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="isWarrantyClaim">
                            <?= lang('App.isWarrantyClaim') ?>
                        </label>
                    </div>
                    
                    <div class="<?= $job['is_warranty_claim'] ? '' : 'd-none' ?>" id="originalJobContainer">
                        <label class="form-label"><?= lang('App.originalJobId') ?></label>
                        <input type="text" class="form-control" name="original_job_id" 
                               value="<?= old('original_job_id', $job['original_job_id']) ?>" placeholder="เลขที่ใบงานเดิม">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-lg me-2"></i><?= lang('App.update') ?>
        </button>
        <a href="<?= base_url('jobs/view/' . $job['id']) ?>" class="btn btn-outline-secondary btn-lg">
            <?= lang('App.cancel') ?>
        </a>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Warranty claim toggle
    $('#isWarrantyClaim').on('change', function() {
        if ($(this).is(':checked')) {
            $('#originalJobContainer').removeClass('d-none');
        } else {
            $('#originalJobContainer').addClass('d-none');
        }
    });
    
    // Calculate totals
    function calculateTotals() {
        var laborCost = parseFloat($('#laborCost').val()) || 0;
        var partsCost = <?= $job['parts_cost'] ?>;
        var subtotal = laborCost + partsCost;
        
        $('#subtotal').val(subtotal.toFixed(2));
        
        if ($('#includeVat').is(':checked')) {
            var vat = subtotal * 0.07;
            $('#vatAmount').val(vat.toFixed(2));
        } else {
            $('#vatAmount').val('0.00');
        }
    }
    
    $('#laborCost, #includeVat').on('change input', calculateTotals);
});
</script>
<?= $this->endSection() ?>

