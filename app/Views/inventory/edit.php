<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i><?= lang('App.editPart') ?></h1>
            <a href="<?= base_url('inventory/view/' . $part['id']) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('inventory/update/' . $part['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.partCode') ?></label>
                            <input type="text" class="form-control" name="part_code" 
                                   value="<?= old('part_code', $part['part_code']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label required"><?= lang('App.partName') ?></label>
                            <input type="text" class="form-control" name="name" 
                                   value="<?= old('name', $part['name']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.partDescription') ?></label>
                        <textarea class="form-control" name="description" rows="2"><?= old('description', $part['description']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required"><?= lang('App.partCostPrice') ?></label>
                            <div class="input-group">
                                <span class="input-group-text">฿</span>
                                <input type="number" class="form-control" name="cost_price" 
                                       value="<?= old('cost_price', $part['cost_price']) ?>" step="0.01" min="0" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label required"><?= lang('App.partSellPrice') ?></label>
                            <div class="input-group">
                                <span class="input-group-text">฿</span>
                                <input type="number" class="form-control" name="sell_price" 
                                       value="<?= old('sell_price', $part['sell_price']) ?>" step="0.01" min="0" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label required"><?= lang('App.partQuantity') ?></label>
                            <input type="number" class="form-control" name="quantity" 
                                   value="<?= old('quantity', $part['quantity']) ?>" min="0" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.partLocation') ?></label>
                            <input type="text" class="form-control" name="location" 
                                   value="<?= old('location', $part['location']) ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= lang('App.reorderPoint') ?></label>
                            <input type="number" class="form-control" name="reorder_point" 
                                   value="<?= old('reorder_point', $part['reorder_point']) ?>" min="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.partSerialNumber') ?></label>
                        <input type="text" class="form-control" name="serial_number" 
                               value="<?= old('serial_number', $part['serial_number']) ?>">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                   <?= old('is_active', $part['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">
                                <?= lang('App.active') ?>
                            </label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i><?= lang('App.update') ?>
                        </button>
                        <a href="<?= base_url('inventory/view/' . $part['id']) ?>" class="btn btn-outline-secondary">
                            <?= lang('App.cancel') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

