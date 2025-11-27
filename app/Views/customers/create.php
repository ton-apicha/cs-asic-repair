<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0"><i class="bi bi-person-plus me-2"></i><?= lang('App.newCustomer') ?></h1>
                <a href="<?= base_url('customers') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('customers/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label required"><?= lang('App.customerName') ?></label>
                            <input type="text" class="form-control" name="name" value="<?= old('name') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required"><?= lang('App.customerPhone') ?></label>
                            <input type="text" class="form-control" name="phone" value="<?= old('phone') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.customerEmail') ?></label>
                            <input type="email" class="form-control" name="email" value="<?= old('email') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.customerAddress') ?></label>
                            <textarea class="form-control" name="address" rows="3"><?= old('address') ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.customerTaxId') ?></label>
                            <input type="text" class="form-control" name="tax_id" value="<?= old('tax_id') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.notes') ?></label>
                            <textarea class="form-control" name="notes" rows="2"><?= old('notes') ?></textarea>
                        </div>
                        
                        <!-- Credit Settings -->
                        <div class="border-top pt-3 mt-3">
                            <h6 class="mb-3"><i class="bi bi-credit-card me-2"></i>Credit Settings</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Credit Limit (฿)</label>
                                    <input type="number" class="form-control" name="credit_limit" 
                                           value="<?= old('credit_limit', 0) ?>" step="0.01" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Terms (Days)</label>
                                    <input type="number" class="form-control" name="credit_terms" 
                                           value="<?= old('credit_terms', 30) ?>" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i><?= lang('App.save') ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

