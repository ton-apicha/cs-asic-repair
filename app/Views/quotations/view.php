<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i>
            <?= esc($quotation['quotation_no']) ?>
        </h1>
        <?php
        $statusClass = match($quotation['status']) {
            'draft' => 'bg-secondary',
            'sent' => 'bg-info',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'expired' => 'bg-warning',
            'converted' => 'bg-primary',
            default => 'bg-secondary'
        };
        ?>
        <span class="badge <?= $statusClass ?> mt-1"><?= ucfirst($quotation['status']) ?></span>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('quotations/export-pdf/' . $quotation['id']) ?>" 
           class="btn btn-outline-info" target="_blank">
            <i class="bi bi-file-pdf me-1"></i>PDF
        </a>
        <?php if ($quotation['status'] === 'draft'): ?>
        <a href="<?= base_url('quotations/edit/' . $quotation['id']) ?>" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <button type="button" class="btn btn-success" onclick="updateStatus('approved')">
            <i class="bi bi-check-lg me-1"></i>Approve
        </button>
        <?php endif; ?>
        <?php if ($quotation['status'] === 'approved'): ?>
        <a href="<?= base_url('quotations/convert/' . $quotation['id']) ?>" class="btn btn-primary">
            <i class="bi bi-arrow-right-circle me-1"></i>Convert to Job
        </a>
        <?php endif; ?>
        <a href="<?= base_url('quotations') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Customer Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person me-1"></i>Customer Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Customer Name</p>
                        <p class="fw-semibold"><?= esc($customer['name']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Phone</p>
                        <p class="fw-semibold"><?= esc($customer['phone']) ?></p>
                    </div>
                    <?php if (!empty($quotation['description'])): ?>
                    <div class="col-12">
                        <p class="mb-1 text-muted">Description</p>
                        <p class="mb-0"><?= esc($quotation['description']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Items -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-list-check me-1"></i>Items
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-center" width="80">Qty</th>
                            <th class="text-end" width="120">Unit Price</th>
                            <th class="text-end" width="120">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quotation['items'] as $item): ?>
                        <tr>
                            <td>
                                <strong><?= esc($item['name']) ?></strong>
                                <?php if (!empty($item['description'])): ?>
                                <br><small class="text-muted"><?= esc($item['description']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end">฿<?= number_format($item['unit_price'], 2) ?></td>
                            <td class="text-end">฿<?= number_format($item['total'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end">Subtotal:</td>
                            <td class="text-end">฿<?= number_format($quotation['subtotal'], 2) ?></td>
                        </tr>
                        <?php if ($quotation['include_vat']): ?>
                        <tr>
                            <td colspan="3" class="text-end">VAT (7%):</td>
                            <td class="text-end">฿<?= number_format($quotation['vat_amount'], 2) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Grand Total:</td>
                            <td class="text-end text-primary fs-5">฿<?= number_format($quotation['grand_total'], 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Details -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle me-1"></i>Details
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <span class="text-muted">Created:</span><br>
                    <?= date('d M Y, H:i', strtotime($quotation['created_at'])) ?>
                </p>
                <p class="mb-2">
                    <span class="text-muted">Valid Until:</span><br>
                    <?php if ($quotation['valid_until']): ?>
                        <?php
                        $validUntil = strtotime($quotation['valid_until']);
                        $isExpired = $validUntil < time();
                        ?>
                        <span class="<?= $isExpired ? 'text-danger' : '' ?>">
                            <?= date('d M Y', $validUntil) ?>
                            <?= $isExpired ? '(Expired)' : '' ?>
                        </span>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </p>
                <?php if ($quotation['converted_job_id']): ?>
                <p class="mb-0">
                    <span class="text-muted">Converted to Job:</span><br>
                    <a href="<?= base_url('jobs/view/' . $quotation['converted_job_id']) ?>" class="text-primary">
                        View Job Card →
                    </a>
                </p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (!empty($quotation['notes'])): ?>
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-sticky me-1"></i>Notes
            </div>
            <div class="card-body">
                <p class="mb-0"><?= nl2br(esc($quotation['notes'])) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Are you sure you want to update the status?')) {
        fetch('<?= base_url('quotations/status/' . $quotation['id']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'status=' + status + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error updating status');
            }
        });
    }
}
</script>
<?= $this->endSection() ?>

