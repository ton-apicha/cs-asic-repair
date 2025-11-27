<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i><?= lang('App.editAsset') ?></h1>
            <a href="<?= base_url('machines/view/' . $asset['id']) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('machines/update/' . $asset['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.customer') ?></label>
                            <input type="text" class="form-control" value="<?= esc($customer['name']) ?>" readonly disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.assetBrandModel') ?></label>
                            <input type="text" class="form-control" name="brand_model" 
                                   value="<?= old('brand_model', $asset['brand_model']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.assetSerialNumber') ?></label>
                            <input type="text" class="form-control" name="serial_number" 
                                   value="<?= old('serial_number', $asset['serial_number']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.assetMacAddress') ?></label>
                            <input type="text" class="form-control" name="mac_address" 
                                   value="<?= old('mac_address', $asset['mac_address']) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.assetHashRate') ?></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="hash_rate" 
                                       value="<?= old('hash_rate', $asset['hash_rate']) ?>" step="0.01" min="0">
                                <span class="input-group-text">TH/s</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.assetStatus') ?></label>
                            <select class="form-select" name="status">
                                <option value="stored" <?= old('status', $asset['status']) === 'stored' ? 'selected' : '' ?>><?= lang('App.assetStatusStored') ?></option>
                                <option value="repairing" <?= old('status', $asset['status']) === 'repairing' ? 'selected' : '' ?>><?= lang('App.assetStatusRepairing') ?></option>
                                <option value="repaired" <?= old('status', $asset['status']) === 'repaired' ? 'selected' : '' ?>><?= lang('App.assetStatusRepaired') ?></option>
                                <option value="returned" <?= old('status', $asset['status']) === 'returned' ? 'selected' : '' ?>><?= lang('App.assetStatusReturned') ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.assetCondition') ?></label>
                        <textarea class="form-control" name="external_condition" rows="2"><?= old('external_condition', $asset['external_condition']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.notes') ?></label>
                        <textarea class="form-control" name="notes" rows="2"><?= old('notes', $asset['notes']) ?></textarea>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i><?= lang('App.update') ?>
                        </button>
                        <a href="<?= base_url('machines/view/' . $asset['id']) ?>" class="btn btn-outline-secondary">
                            <?= lang('App.cancel') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

