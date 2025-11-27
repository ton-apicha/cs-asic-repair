<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-plus-circle me-2"></i><?= lang('App.newJobCard') ?></h1>
        <a href="<?= base_url('jobs') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
        </a>
    </div>

    <form action="<?= base_url('jobs/store') ?>" method="POST" id="jobForm">
        <?= csrf_field() ?>
        
        <div class="row g-4">
            <!-- Customer Section -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-person me-2"></i><?= lang('App.customer') ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($customer)): ?>
                            <!-- Pre-selected customer -->
                            <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label"><?= lang('App.customerName') ?></label>
                                <input type="text" class="form-control" value="<?= esc($customer['name']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?= lang('App.customerPhone') ?></label>
                                <input type="text" class="form-control" value="<?= esc($customer['phone']) ?>" readonly>
                            </div>
                        <?php else: ?>
                            <!-- Customer search/select -->
                            <input type="hidden" name="customer_id" id="customer_id">
                            <input type="hidden" name="new_customer" id="new_customer" value="0">
                            
                            <div class="mb-3">
                                <label class="form-label required"><?= lang('App.customerName') ?></label>
                                <input type="text" class="form-control customer-autocomplete" 
                                       id="customer_name" name="customer_name"
                                       data-target="#customer_id"
                                       data-phone-target="#customer_phone"
                                       placeholder="<?= lang('App.searchCustomer') ?>"
                                       required>
                                <div class="form-text">พิมพ์เพื่อค้นหา หรือกรอกชื่อใหม่เพื่อสร้างลูกค้าใหม่</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label required"><?= lang('App.customerPhone') ?></label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><?= lang('App.customerEmail') ?></label>
                                <input type="email" class="form-control" name="customer_email">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Asset Section -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-hdd-rack me-2"></i><?= lang('App.asset') ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($asset)): ?>
                            <!-- Pre-selected asset -->
                            <input type="hidden" name="asset_id" value="<?= $asset['id'] ?>">
                            <input type="hidden" name="serial_number" value="<?= esc($asset['serial_number']) ?>">
                            <input type="hidden" name="brand_model" value="<?= esc($asset['brand_model']) ?>">
                            
                            <div class="mb-3">
                                <label class="form-label"><?= lang('App.assetBrandModel') ?></label>
                                <input type="text" class="form-control" value="<?= esc($asset['brand_model']) ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?= lang('App.assetSerialNumber') ?></label>
                                <input type="text" class="form-control" value="<?= esc($asset['serial_number']) ?>" readonly>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label required"><?= lang('App.assetSerialNumber') ?></label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
                                <div id="serial_status" class="form-text"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label required"><?= lang('App.assetBrandModel') ?></label>
                                <select class="form-select" id="brand_model" name="brand_model" required>
                                    <option value="">-- เลือกรุ่น --</option>
                                    <optgroup label="Bitmain Antminer">
                                        <option value="Bitmain Antminer S19">Antminer S19</option>
                                        <option value="Bitmain Antminer S19 Pro">Antminer S19 Pro</option>
                                        <option value="Bitmain Antminer S19j Pro">Antminer S19j Pro</option>
                                        <option value="Bitmain Antminer S19 XP">Antminer S19 XP</option>
                                        <option value="Bitmain Antminer S21">Antminer S21</option>
                                        <option value="Bitmain Antminer T21">Antminer T21</option>
                                    </optgroup>
                                    <optgroup label="MicroBT Whatsminer">
                                        <option value="MicroBT Whatsminer M30S">Whatsminer M30S</option>
                                        <option value="MicroBT Whatsminer M30S+">Whatsminer M30S+</option>
                                        <option value="MicroBT Whatsminer M50">Whatsminer M50</option>
                                    </optgroup>
                                    <optgroup label="Canaan Avalon">
                                        <option value="Canaan Avalon 1246">Avalon 1246</option>
                                        <option value="Canaan Avalon 1366">Avalon 1366</option>
                                    </optgroup>
                                    <optgroup label="อื่นๆ">
                                        <option value="Other">อื่นๆ (ระบุในหมายเหตุ)</option>
                                    </optgroup>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= lang('App.assetMacAddress') ?></label>
                                    <input type="text" class="form-control" name="mac_address" placeholder="xx:xx:xx:xx:xx:xx">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?= lang('App.assetHashRate') ?></label>
                                    <input type="text" class="form-control" name="hash_rate" placeholder="e.g., 110">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><?= lang('App.assetCondition') ?></label>
                                <textarea class="form-control" name="external_condition" rows="2" placeholder="รอยขีดข่วน, สติ๊กเกอร์พิเศษ ฯลฯ"></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Job Details Section -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-clipboard-check me-2"></i><?= lang('App.jobDetails') ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label required"><?= lang('App.jobSymptom') ?></label>
                                <textarea class="form-control symptom-autocomplete" name="symptom" rows="3" 
                                          placeholder="<?= lang('App.symptomPlaceholder') ?>" required></textarea>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><?= lang('App.jobTechnician') ?></label>
                                    <select class="form-select" name="technician_id">
                                        <option value="">-- ยังไม่มอบหมาย --</option>
                                        <?php foreach ($technicians ?? [] as $tech): ?>
                                            <option value="<?= $tech['id'] ?>"><?= esc($tech['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label"><?= lang('App.laborCost') ?></label>
                                    <div class="input-group">
                                        <span class="input-group-text">฿</span>
                                        <input type="number" class="form-control" name="labor_cost" value="0" min="0" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warranty Claim -->
                        <div class="border-top pt-3 mt-3">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_warranty_claim" id="is_warranty_claim" value="1">
                                <label class="form-check-label" for="is_warranty_claim">
                                    <i class="bi bi-shield-check me-1"></i><?= lang('App.isWarrantyClaim') ?>
                                </label>
                            </div>
                            
                            <div class="mb-3 d-none" id="original_job_container">
                                <label class="form-label"><?= lang('App.originalJobId') ?></label>
                                <input type="text" class="form-control" name="original_job_id" placeholder="เลขที่ใบงานเดิม">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><?= lang('App.notes') ?></label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i><?= lang('App.create') ?> <?= lang('App.jobCard') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Customer autocomplete
    $('#customer_name').on('customer:selected', function(e, item) {
        $('#customer_id').val(item.id);
        $('#customer_phone').val(item.phone);
        $('#new_customer').val('0');
    });

    // If customer not found, mark as new
    $('#customer_name').on('autocompletechange', function() {
        if (!$('#customer_id').val()) {
            $('#new_customer').val('1');
        }
    });

    // Serial number check
    var serialTimer;
    $('#serial_number').on('input', function() {
        clearTimeout(serialTimer);
        var serial = $(this).val();
        
        if (serial.length < 3) {
            $('#serial_status').html('').removeClass('text-success text-warning');
            return;
        }

        serialTimer = setTimeout(function() {
            $.get('<?= base_url('machines/check-serial') ?>', { serial: serial }, function(response) {
                if (response.exists) {
                    $('#serial_status').html('<i class="bi bi-check-circle"></i> พบเครื่องในระบบ - ' + response.asset.brand_model)
                        .removeClass('text-warning').addClass('text-success');
                    $('#brand_model').val(response.asset.brand_model);
                    if (response.asset.customer) {
                        $('#customer_id').val(response.asset.customer_id);
                        $('#customer_name').val(response.asset.customer.name);
                        $('#customer_phone').val(response.asset.customer.phone);
                    }
                } else {
                    $('#serial_status').html('<i class="bi bi-info-circle"></i> เครื่องใหม่ - จะถูกบันทึกเข้าระบบ')
                        .removeClass('text-success').addClass('text-warning');
                }
            });
        }, 500);
    });

    // Warranty claim toggle
    $('#is_warranty_claim').on('change', function() {
        if ($(this).is(':checked')) {
            $('#original_job_container').removeClass('d-none');
        } else {
            $('#original_job_container').addClass('d-none');
        }
    });
});
</script>
<?= $this->endSection() ?>

