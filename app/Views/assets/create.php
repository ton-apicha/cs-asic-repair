<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0"><i class="bi bi-hdd-rack me-2"></i><?= lang('App.newAsset') ?></h1>
            <a href="<?= base_url('machines') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('machines/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.customer') ?></label>
                            <input type="text" class="form-control" id="customerSearch" 
                                   placeholder="<?= lang('App.searchCustomer') ?>" autocomplete="off" required>
                            <input type="hidden" name="customer_id" id="customerId" value="<?= old('customer_id') ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.assetBrandModel') ?></label>
                            <input type="text" class="form-control" name="brand_model" 
                                   value="<?= old('brand_model') ?>" placeholder="e.g., Antminer S19 Pro" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.assetSerialNumber') ?></label>
                            <input type="text" class="form-control" name="serial_number" id="serialNumber"
                                   value="<?= old('serial_number') ?>" required>
                            <div class="form-text" id="serialCheck"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.assetMacAddress') ?></label>
                            <input type="text" class="form-control" name="mac_address" 
                                   value="<?= old('mac_address') ?>" placeholder="e.g., 00:11:22:33:44:55">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.assetHashRate') ?></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="hash_rate" 
                                       value="<?= old('hash_rate') ?>" step="0.01" min="0">
                                <span class="input-group-text">TH/s</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.assetStatus') ?></label>
                            <select class="form-select" name="status">
                                <option value="stored" <?= old('status') === 'stored' ? 'selected' : '' ?>><?= lang('App.assetStatusStored') ?></option>
                                <option value="repairing" <?= old('status') === 'repairing' ? 'selected' : '' ?>><?= lang('App.assetStatusRepairing') ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.assetCondition') ?></label>
                        <textarea class="form-control" name="external_condition" rows="2" 
                                  placeholder="<?= lang('App.description') ?>"><?= old('external_condition') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.notes') ?></label>
                        <textarea class="form-control" name="notes" rows="2"><?= old('notes') ?></textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i><?= lang('App.save') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    // Customer autocomplete
    $('#customerSearch').autocomplete({
        source: '<?= base_url('customers/search') ?>',
        minLength: 2,
        select: function(event, ui) {
            $('#customerId').val(ui.item.id);
            $('#customerSearch').val(ui.item.label);
            return false;
        }
    });
    
    // Check serial number uniqueness
    let serialTimeout;
    $('#serialNumber').on('input', function() {
        clearTimeout(serialTimeout);
        const serial = $(this).val();
        if (serial.length >= 3) {
            serialTimeout = setTimeout(function() {
                $.get('<?= base_url('machines/check-serial') ?>', { serial: serial }, function(response) {
                    if (response.exists) {
                        $('#serialCheck').html('<span class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>This serial number already exists</span>');
                    } else {
                        $('#serialCheck').html('<span class="text-success"><i class="bi bi-check-circle me-1"></i>Serial number available</span>');
                    }
                });
            }, 500);
        }
    });
});
</script>
<?= $this->endSection() ?>

