<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-clipboard-check me-2"></i><?= lang('App.jobCard') ?> #<?= esc($job['job_id']) ?>
                <?php if ($job['is_warranty_claim']): ?>
                    <span class="badge bg-danger ms-2"><?= lang('App.warrantyClaim') ?></span>
                <?php endif; ?>
                <?php if ($job['is_locked']): ?>
                    <span class="badge bg-secondary ms-2"><i class="bi bi-lock"></i> Locked</span>
                <?php endif; ?>
            </h1>
        </div>
        <div>
            <a href="<?= base_url('jobs/print/' . $job['id']) ?>" class="btn btn-outline-info me-2" target="_blank">
                <i class="bi bi-printer me-1"></i><?= lang('App.printCheckinSlip') ?>
            </a>
            <?php if (!$job['is_locked']): ?>
                <a href="<?= base_url('jobs/edit/' . $job['id']) ?>" class="btn btn-outline-primary me-2">
                    <i class="bi bi-pencil me-1"></i><?= lang('App.edit') ?>
                </a>
            <?php endif; ?>
            <a href="<?= base_url('jobs') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i><?= lang('App.back') ?>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Info -->
        <div class="col-lg-8">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row text-center">
                        <?php
                        $statuses = [
                            'new_checkin' => ['icon' => 'box-arrow-in-down', 'label' => lang('App.statusNewCheckin')],
                            'pending_repair' => ['icon' => 'hourglass-split', 'label' => lang('App.statusPendingRepair')],
                            'in_progress' => ['icon' => 'tools', 'label' => lang('App.statusInProgress')],
                            'repair_done' => ['icon' => 'check-circle', 'label' => lang('App.statusRepairDone')],
                            'ready_handover' => ['icon' => 'box-arrow-right', 'label' => lang('App.statusReadyHandover')],
                            'delivered' => ['icon' => 'check2-all', 'label' => lang('App.statusDelivered')],
                        ];
                        $currentFound = false;
                        ?>
                        <?php foreach ($statuses as $key => $status): ?>
                            <?php
                            $isActive = $job['status'] === $key;
                            $isPast = !$currentFound && !$isActive;
                            if ($isActive) $currentFound = true;
                            $class = $isActive ? 'text-primary fw-bold' : ($isPast ? 'text-success' : 'text-muted');
                            ?>
                            <div class="col">
                                <div class="<?= $class ?>">
                                    <i class="bi bi-<?= $status['icon'] ?> fs-4 d-block mb-1"></i>
                                    <small><?= $status['label'] ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Customer & Asset Info -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header"><i class="bi bi-person me-2"></i><?= lang('App.customer') ?></div>
                        <div class="card-body">
                            <h5 class="mb-2"><?= esc($job['customer_name']) ?></h5>
                            <p class="mb-1"><i class="bi bi-telephone me-2"></i><?= esc($job['customer_phone']) ?></p>
                            <a href="<?= base_url('customers/view/' . $job['customer_id']) ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <?= lang('App.view') ?> <?= lang('App.customerDetails') ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header"><i class="bi bi-hdd-rack me-2"></i><?= lang('App.asset') ?></div>
                        <div class="card-body">
                            <h5 class="mb-2"><?= esc($job['brand_model']) ?></h5>
                            <p class="mb-1"><i class="bi bi-upc me-2"></i>S/N: <?= esc($job['serial_number']) ?></p>
                            <a href="<?= base_url('machines/view/' . $job['asset_id']) ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <?= lang('App.view') ?> <?= lang('App.assetHistory') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Details -->
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i><?= lang('App.jobDetails') ?></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted"><?= lang('App.jobSymptom') ?></label>
                            <p class="mb-0"><?= nl2br(esc($job['symptom'])) ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted"><?= lang('App.jobDiagnosis') ?></label>
                            <p class="mb-0"><?= $job['diagnosis'] ? nl2br(esc($job['diagnosis'])) : '<em class="text-muted">-</em>' ?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted"><?= lang('App.jobSolution') ?></label>
                            <p class="mb-0"><?= $job['solution'] ? nl2br(esc($job['solution'])) : '<em class="text-muted">-</em>' ?></p>
                        </div>
                    </div>
                    <?php if ($job['notes']): ?>
                        <div class="border-top pt-3">
                            <label class="form-label text-muted"><?= lang('App.notes') ?></label>
                            <p class="mb-0"><?= nl2br(esc($job['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Parts Used -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam me-2"></i><?= lang('App.jobParts') ?></span>
                    <?php if (!$job['is_locked']): ?>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPartModal">
                            <i class="bi bi-plus me-1"></i><?= lang('App.add') ?>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><?= lang('App.partCode') ?></th>
                                <th><?= lang('App.partName') ?></th>
                                <th class="text-center"><?= lang('App.quantity') ?></th>
                                <th class="text-end"><?= lang('App.price') ?></th>
                                <th class="text-end"><?= lang('App.total') ?></th>
                                <?php if (!$job['is_locked']): ?>
                                    <th></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($job['parts'])): ?>
                                <tr><td colspan="6" class="text-center text-muted py-3"><?= lang('App.noRecords') ?></td></tr>
                            <?php else: ?>
                                <?php foreach ($job['parts'] as $part): ?>
                                    <tr>
                                        <td><?= esc($part['part_code']) ?></td>
                                        <td><?= esc($part['part_name']) ?></td>
                                        <td class="text-center"><?= $part['quantity'] ?></td>
                                        <td class="text-end">฿<?= number_format($part['unit_price'], 2) ?></td>
                                        <td class="text-end">฿<?= number_format($part['total_price'], 2) ?></td>
                                        <?php if (!$job['is_locked'] && !$part['is_deducted']): ?>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePart(<?= $part['id'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        <?php elseif (!$job['is_locked']): ?>
                                            <td></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Pricing Summary -->
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-calculator me-2"></i><?= lang('App.grandTotal') ?></div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><?= lang('App.laborCost') ?></td>
                            <td class="text-end">฿<?= number_format($job['labor_cost'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('App.partsCost') ?></td>
                            <td class="text-end">฿<?= number_format($job['parts_cost'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('App.subtotal') ?></td>
                            <td class="text-end">฿<?= number_format($job['total_cost'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('App.vatAmount') ?></td>
                            <td class="text-end">฿<?= number_format($job['vat_amount'], 2) ?></td>
                        </tr>
                        <tr class="table-primary fw-bold">
                            <td><?= lang('App.grandTotal') ?></td>
                            <td class="text-end">฿<?= number_format($job['grand_total'], 2) ?></td>
                        </tr>
                        <tr class="table-success">
                            <td><?= lang('App.amountPaid') ?></td>
                            <td class="text-end">฿<?= number_format($job['amount_paid'], 2) ?></td>
                        </tr>
                        <?php if ($job['grand_total'] - $job['amount_paid'] > 0): ?>
                            <tr class="table-danger">
                                <td><?= lang('App.amountDue') ?></td>
                                <td class="text-end">฿<?= number_format($job['grand_total'] - $job['amount_paid'], 2) ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if (!$job['is_locked'] && $job['status'] !== 'cancelled'): ?>
                        <button type="button" class="btn btn-success w-100 mt-3" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="bi bi-cash me-2"></i><?= lang('App.payment') ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Change -->
            <?php if (!$job['is_locked'] && $job['status'] !== 'cancelled'): ?>
                <div class="card mb-4">
                    <div class="card-header"><i class="bi bi-arrow-repeat me-2"></i><?= lang('App.status') ?></div>
                    <div class="card-body">
                        <select class="form-select" id="statusSelect" onchange="updateStatus(this.value)">
                            <option value="new_checkin" <?= $job['status'] === 'new_checkin' ? 'selected' : '' ?>><?= lang('App.statusNewCheckin') ?></option>
                            <option value="pending_repair" <?= $job['status'] === 'pending_repair' ? 'selected' : '' ?>><?= lang('App.statusPendingRepair') ?></option>
                            <option value="in_progress" <?= $job['status'] === 'in_progress' ? 'selected' : '' ?>><?= lang('App.statusInProgress') ?></option>
                            <option value="repair_done" <?= $job['status'] === 'repair_done' ? 'selected' : '' ?>><?= lang('App.statusRepairDone') ?></option>
                            <option value="ready_handover" <?= $job['status'] === 'ready_handover' ? 'selected' : '' ?>><?= lang('App.statusReadyHandover') ?></option>
                            <option value="delivered" <?= $job['status'] === 'delivered' ? 'selected' : '' ?>><?= lang('App.statusDelivered') ?></option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header"><i class="bi bi-clock-history me-2"></i>Timeline</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <small class="text-muted">Check-in:</small><br>
                            <?= $job['checkin_date'] ? date('d/m/Y H:i', strtotime($job['checkin_date'])) : '-' ?>
                        </li>
                        <?php if ($job['repair_start_date']): ?>
                            <li class="mb-2">
                                <small class="text-muted">Repair Started:</small><br>
                                <?= date('d/m/Y H:i', strtotime($job['repair_start_date'])) ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($job['repair_end_date']): ?>
                            <li class="mb-2">
                                <small class="text-muted">Repair Done:</small><br>
                                <?= date('d/m/Y H:i', strtotime($job['repair_end_date'])) ?>
                            </li>
                        <?php endif; ?>
                        <?php if ($job['delivery_date']): ?>
                            <li class="mb-2">
                                <small class="text-muted">Delivered:</small><br>
                                <?= date('d/m/Y H:i', strtotime($job['delivery_date'])) ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Part Modal -->
<div class="modal fade" id="addPartModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('App.add') ?> <?= lang('App.part') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.part') ?></label>
                    <select class="form-select" id="partSelect">
                        <option value="">-- เลือกอะไหล่ --</option>
                        <?php foreach ($parts ?? [] as $part): ?>
                            <option value="<?= $part['id'] ?>" data-price="<?= $part['sell_price'] ?>" data-stock="<?= $part['quantity'] ?>">
                                <?= esc($part['part_code']) ?> - <?= esc($part['name']) ?> (Stock: <?= $part['quantity'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.quantity') ?></label>
                    <input type="number" class="form-control" id="partQuantity" value="1" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('App.cancel') ?></button>
                <button type="button" class="btn btn-primary" onclick="addPart()"><?= lang('App.add') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('App.payment') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.paymentMethod') ?></label>
                    <select class="form-select" id="paymentMethod">
                        <option value="cash"><?= lang('App.paymentCash') ?></option>
                        <option value="transfer"><?= lang('App.paymentTransfer') ?></option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label"><?= lang('App.amount') ?></label>
                    <div class="input-group">
                        <span class="input-group-text">฿</span>
                        <input type="number" class="form-control" id="paymentAmount" value="<?= $job['grand_total'] - $job['amount_paid'] ?>" step="0.01">
                    </div>
                </div>
                <div class="mb-3" id="referenceField" style="display: none;">
                    <label class="form-label"><?= lang('App.paymentReference') ?></label>
                    <input type="text" class="form-control" id="paymentReference" placeholder="เลขที่อ้างอิง/สลิป">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('App.cancel') ?></button>
                <button type="button" class="btn btn-success" onclick="recordPayment()"><?= lang('App.confirm') ?></button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
var jobId = <?= $job['id'] ?>;

function updateStatus(status) {
    $.post('<?= base_url('jobs/update-status/') ?>' + jobId, {
        status: status,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            App.showToast(response.message, 'success');
            location.reload();
        } else {
            App.showToast(response.message, 'danger');
            location.reload();
        }
    });
}

function addPart() {
    var partId = $('#partSelect').val();
    var quantity = $('#partQuantity').val();
    
    if (!partId) {
        App.showToast('กรุณาเลือกอะไหล่', 'warning');
        return;
    }

    $.post('<?= base_url('jobs/add-part/') ?>' + jobId, {
        part_id: partId,
        quantity: quantity,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            App.showToast(response.message, 'success');
            location.reload();
        } else {
            App.showToast(response.message, 'danger');
        }
    });
}

function removePart(jobPartId) {
    if (!confirm('ต้องการลบอะไหล่นี้?')) return;
    
    $.post('<?= base_url('jobs/remove-part/') ?>' + jobId, {
        job_part_id: jobPartId,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            App.showToast(response.message, 'success');
            location.reload();
        } else {
            App.showToast(response.message, 'danger');
        }
    });
}

function recordPayment() {
    var method = $('#paymentMethod').val();
    var amount = $('#paymentAmount').val();
    var reference = $('#paymentReference').val();

    $.post('<?= base_url('payments/store') ?>', {
        job_card_id: jobId,
        payment_method: method,
        amount: amount,
        reference_number: reference,
        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    }, function(response) {
        if (response.success) {
            App.showToast(response.message, 'success');
            location.reload();
        } else {
            App.showToast(response.message, 'danger');
        }
    });
}

$('#paymentMethod').on('change', function() {
    if ($(this).val() === 'transfer') {
        $('#referenceField').show();
    } else {
        $('#referenceField').hide();
    }
});
</script>
<?= $this->endSection() ?>

