<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="h3 mb-4"><i class="bi bi-gear me-2"></i><?= lang('App.systemSettings') ?></h1>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <form action="<?= base_url('settings/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <!-- Company Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="bi bi-building me-1"></i><?= lang('App.companyInfo') ?>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label"><?= lang('App.companyName') ?></label>
                                    <input type="text" class="form-control" name="company_name" 
                                           value="<?= esc($settings['company_name'] ?? '') ?>"
                                           placeholder="<?= lang('App.companyName') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?= lang('App.address') ?></label>
                                    <textarea class="form-control" name="company_address" rows="2"
                                              placeholder="<?= lang('App.address') ?>"><?= esc($settings['company_address'] ?? '') ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.phone') ?></label>
                                        <input type="text" class="form-control" name="company_phone" 
                                               value="<?= esc($settings['company_phone'] ?? '') ?>"
                                               placeholder="02-xxx-xxxx">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.email') ?></label>
                                        <input type="email" class="form-control" name="company_email" 
                                               value="<?= esc($settings['company_email'] ?? '') ?>"
                                               placeholder="email@company.com">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label"><?= lang('App.taxId') ?></label>
                                    <input type="text" class="form-control" name="company_tax_id" 
                                           value="<?= esc($settings['company_tax_id'] ?? '') ?>"
                                           placeholder="0-0000-00000-00-0">
                                </div>
                            </div>
                        </div>

                        <!-- VAT Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="bi bi-percent me-1"></i><?= lang('App.taxSettings') ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.vatType') ?></label>
                                        <select class="form-select" name="vat_type">
                                            <option value="inclusive" <?= ($settings['vat_type'] ?? '') === 'inclusive' ? 'selected' : '' ?>>
                                                <?= lang('App.vatInclusive') ?>
                                            </option>
                                            <option value="none" <?= ($settings['vat_type'] ?? '') === 'none' ? 'selected' : '' ?>>
                                                <?= lang('App.vatNone') ?>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.vatRate') ?> (%)</label>
                                        <input type="number" class="form-control" name="vat_rate" 
                                               value="<?= esc($settings['vat_rate'] ?? 7) ?>" 
                                               min="0" max="100" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="bi bi-sliders me-1"></i><?= lang('App.systemConfig') ?>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.warrantyDays') ?></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="warranty_days" 
                                                   value="<?= esc($settings['warranty_days'] ?? 30) ?>" min="0">
                                            <span class="input-group-text"><?= lang('App.days') ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.jobIdPrefix') ?></label>
                                        <input type="text" class="form-control" name="job_id_prefix" 
                                               value="<?= esc($settings['job_id_prefix'] ?? '') ?>"
                                               placeholder="JOB-">
                                        <div class="form-text"><?= lang('App.jobIdPrefixHint') ?></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.currency') ?></label>
                                        <select class="form-select" name="currency">
                                            <option value="THB" <?= ($settings['currency'] ?? 'THB') === 'THB' ? 'selected' : '' ?>>THB (฿)</option>
                                            <option value="USD" <?= ($settings['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                                            <option value="CNY" <?= ($settings['currency'] ?? '') === 'CNY' ? 'selected' : '' ?>>CNY (¥)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><?= lang('App.defaultLanguage') ?></label>
                                        <select class="form-select" name="default_language">
                                            <option value="th" <?= ($settings['default_language'] ?? 'th') === 'th' ? 'selected' : '' ?>>ไทย</option>
                                            <option value="en" <?= ($settings['default_language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                            <option value="zh" <?= ($settings['default_language'] ?? '') === 'zh' ? 'selected' : '' ?>>简体中文</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i><?= lang('App.save') ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column - Logo Upload -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi bi-image me-1"></i><?= lang('App.companyLogo') ?>
                        </div>
                        <div class="card-body text-center">
                            <?php $logo = $settings['company_logo'] ?? ''; ?>
                            <?php if ($logo && file_exists(FCPATH . 'assets/images/' . $logo)): ?>
                                <div class="mb-3">
                                    <img src="<?= base_url('assets/images/' . $logo) ?>" 
                                         alt="Company Logo" 
                                         class="img-fluid rounded"
                                         style="max-height: 150px;">
                                </div>
                                <form action="<?= base_url('settings/delete-logo') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('<?= lang('App.confirmDelete') ?>')">
                                        <i class="bi bi-trash me-1"></i><?= lang('App.deleteLogo') ?>
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="py-4 text-muted">
                                    <i class="bi bi-image fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0"><?= lang('App.noLogoUploaded') ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <hr>
                            
                            <form action="<?= base_url('settings/upload-logo') ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label class="form-label"><?= lang('App.uploadNewLogo') ?></label>
                                    <input type="file" class="form-control" name="logo" 
                                           accept="image/jpeg,image/png,image/gif,image/webp" required>
                                    <div class="form-text"><?= lang('App.logoHint') ?></div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-upload me-1"></i><?= lang('App.uploadLogo') ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="card">
                        <div class="card-header">
                            <i class="bi bi-lightning me-1"></i><?= lang('App.quickLinks') ?>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="<?= base_url('settings/branches') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-building me-2 text-primary"></i>
                                <?= lang('App.branchManagement') ?>
                                <i class="bi bi-chevron-right ms-auto"></i>
                            </a>
                            <a href="<?= base_url('settings/users') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-people me-2 text-success"></i>
                                <?= lang('App.userManagement') ?>
                                <i class="bi bi-chevron-right ms-auto"></i>
                            </a>
                            <a href="<?= base_url('settings/backup') ?>" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="bi bi-database me-2 text-warning"></i>
                                <?= lang('App.backupRestore') ?>
                                <i class="bi bi-chevron-right ms-auto"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
