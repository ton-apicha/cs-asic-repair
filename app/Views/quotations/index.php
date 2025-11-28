<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-file-earmark-text text-primary me-2"></i>
        <?= lang('App.quotations') ?>
    </h1>
    <a href="<?= base_url('quotations/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i><?= lang('App.newQuotation') ?>
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" 
                       value="<?= esc($search ?? '') ?>" placeholder="<?= lang('App.searchQuotation') ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value=""><?= lang('App.allStatus') ?></option>
                    <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>><?= lang('App.statusDraft') ?></option>
                    <option value="sent" <?= ($status ?? '') === 'sent' ? 'selected' : '' ?>><?= lang('App.statusSent') ?></option>
                    <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>><?= lang('App.statusApproved') ?></option>
                    <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>><?= lang('App.statusRejected') ?></option>
                    <option value="converted" <?= ($status ?? '') === 'converted' ? 'selected' : '' ?>><?= lang('App.statusConverted') ?></option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search me-1"></i><?= lang('App.filter') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quotations List -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th><?= lang('App.quotationNo') ?></th>
                    <th><?= lang('App.customer') ?></th>
                    <th><?= lang('App.total') ?></th>
                    <th><?= lang('App.status') ?></th>
                    <th><?= lang('App.validUntil') ?></th>
                    <th><?= lang('App.created') ?></th>
                    <th width="120"><?= lang('App.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quotations)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-file-earmark-text fs-1 d-block mb-2 opacity-50"></i>
                        <?= lang('App.noQuotationsFound') ?>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($quotations as $quotation): ?>
                <tr>
                    <td>
                        <a href="<?= base_url('quotations/view/' . $quotation['id']) ?>" class="fw-semibold text-primary">
                            <?= esc($quotation['quotation_no']) ?>
                        </a>
                    </td>
                    <td><?= esc($quotation['customer_name']) ?></td>
                    <td class="fw-semibold">฿<?= number_format($quotation['grand_total'], 2) ?></td>
                    <td>
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
                        $statusText = match($quotation['status']) {
                            'draft' => lang('App.statusDraft'),
                            'sent' => lang('App.statusSent'),
                            'approved' => lang('App.statusApproved'),
                            'rejected' => lang('App.statusRejected'),
                            'expired' => lang('App.statusExpired'),
                            'converted' => lang('App.statusConverted'),
                            default => ucfirst($quotation['status'])
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </td>
                    <td>
                        <?php if ($quotation['valid_until']): ?>
                            <?php
                            $validUntil = strtotime($quotation['valid_until']);
                            $isExpired = $validUntil < time();
                            ?>
                            <span class="<?= $isExpired ? 'text-danger' : '' ?>">
                                <?= date('d M Y', $validUntil) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d M Y', strtotime($quotation['created_at'])) ?></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="<?= base_url('quotations/view/' . $quotation['id']) ?>" 
                               class="btn btn-outline-primary" title="<?= lang('App.view') ?>">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($quotation['status'] === 'draft'): ?>
                            <a href="<?= base_url('quotations/edit/' . $quotation['id']) ?>" 
                               class="btn btn-outline-secondary" title="<?= lang('App.edit') ?>">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <a href="<?= base_url('quotations/export-pdf/' . $quotation['id']) ?>" 
                               class="btn btn-outline-info" title="PDF" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pager): ?>
    <div class="card-footer">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
