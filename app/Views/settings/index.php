<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-4"><i class="bi bi-gear me-2"></i><?= lang('App.systemSettings') ?></h1>

            <form action="<?= base_url('settings/update') ?>" method="POST">
                <?= csrf_field() ?>
                
                <!-- Company Info -->
                <div class="card mb-4">
                    <div class="card-header">ข้อมูลบริษัท</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">ชื่อบริษัท</label>
                            <input type="text" class="form-control" name="company_name" value="<?= esc($settings['company_name'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ที่อยู่</label>
                            <textarea class="form-control" name="company_address" rows="2"><?= esc($settings['company_address'] ?? '') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control" name="company_phone" value="<?= esc($settings['company_phone'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" class="form-control" name="company_email" value="<?= esc($settings['company_email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เลขประจำตัวผู้เสียภาษี</label>
                            <input type="text" class="form-control" name="company_tax_id" value="<?= esc($settings['company_tax_id'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- VAT Settings -->
                <div class="card mb-4">
                    <div class="card-header">การตั้งค่าภาษี</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.vatType') ?></label>
                            <select class="form-select" name="vat_type">
                                <option value="inclusive" <?= ($settings['vat_type'] ?? '') === 'inclusive' ? 'selected' : '' ?>><?= lang('App.vatInclusive') ?></option>
                                <option value="none" <?= ($settings['vat_type'] ?? '') === 'none' ? 'selected' : '' ?>><?= lang('App.vatNone') ?></option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อัตรา VAT (%)</label>
                            <input type="number" class="form-control" name="vat_rate" value="<?= esc($settings['vat_rate'] ?? 7) ?>" min="0" max="100" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Other Settings -->
                <div class="card mb-4">
                    <div class="card-header">การตั้งค่าอื่นๆ</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">ระยะเวลารับประกัน (วัน)</label>
                            <input type="number" class="form-control" name="warranty_days" value="<?= esc($settings['warranty_days'] ?? 30) ?>" min="0">
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
<?= $this->endSection() ?>

