<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0"><i class="bi bi-pencil me-2"></i><?= lang('App.editCustomer') ?></h1>
            <a href="<?= base_url('customers/view/' . $customer['id']) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('customers/update/' . $customer['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label required"><?= lang('App.customerName') ?></label>
                        <input type="text" class="form-control" name="name" 
                               value="<?= old('name', $customer['name']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label required"><?= lang('App.customerPhone') ?></label>
                        <input type="text" class="form-control" name="phone" 
                               value="<?= old('phone', $customer['phone']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.customerEmail') ?></label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= old('email', $customer['email']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.customerAddress') ?></label>
                        <textarea class="form-control" name="address" rows="3"><?= old('address', $customer['address']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.customerTaxId') ?></label>
                        <input type="text" class="form-control" name="tax_id" 
                               value="<?= old('tax_id', $customer['tax_id']) ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><?= lang('App.notes') ?></label>
                        <textarea class="form-control" name="notes" rows="2"><?= old('notes', $customer['notes']) ?></textarea>
                    </div>
                    
                    <!-- Credit Settings -->
                    <div class="border-top pt-3 mt-3">
                        <h6 class="mb-3"><i class="bi bi-credit-card me-2"></i>Credit Settings</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Credit Limit (฿)</label>
                                <input type="number" class="form-control" name="credit_limit" 
                                       value="<?= old('credit_limit', $customer['credit_limit'] ?? 0) ?>" step="0.01" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Credit Used (฿)</label>
                                <input type="number" class="form-control" readonly
                                       value="<?= number_format($customer['credit_used'] ?? 0, 2) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Payment Terms (Days)</label>
                                <input type="number" class="form-control" name="credit_terms" 
                                       value="<?= old('credit_terms', $customer['credit_terms'] ?? 30) ?>" min="0">
                            </div>
                        </div>
                        <?php 
                        $available = ($customer['credit_limit'] ?? 0) - ($customer['credit_used'] ?? 0);
                        $availableClass = $available > 0 ? 'text-success' : ($available < 0 ? 'text-danger' : 'text-muted');
                        ?>
                        <p class="mb-0">
                            <small class="text-muted">Available Credit:</small>
                            <strong class="<?= $availableClass ?>">฿<?= number_format($available, 2) ?></strong>
                        </p>
                    </div>
                    
                    <div class="mb-4 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                   <?= old('is_active', $customer['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">
                                <?= lang('App.active') ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i><?= lang('App.update') ?>
                        </button>
                        <a href="<?= base_url('customers/view/' . $customer['id']) ?>" class="btn btn-outline-secondary">
                            <?= lang('App.cancel') ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

