<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-plus-circle text-primary me-2"></i>
        Create Quotation
    </h1>
    <a href="<?= base_url('quotations') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<form action="<?= base_url('quotations/store') ?>" method="POST" id="quotationForm">
    <?= csrf_field() ?>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Customer & Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person me-1"></i>Customer Information
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">Select Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id'] ?>">
                                    <?= esc($customer['name']) ?> - <?= esc($customer['phone']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Asset (Optional)</label>
                            <input type="text" class="form-control" id="assetSearch" placeholder="Search asset by S/N...">
                            <input type="hidden" name="asset_id" id="assetId">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" 
                                      placeholder="Brief description of work to be done..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Items -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-check me-1"></i>Items</span>
                    <button type="button" class="btn btn-sm btn-primary" id="addItem">
                        <i class="bi bi-plus me-1"></i>Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div id="itemsContainer">
                        <!-- Item rows will be added here -->
                    </div>
                    
                    <div id="noItems" class="text-center py-4 text-muted">
                        <i class="bi bi-cart fs-1 d-block mb-2 opacity-50"></i>
                        <p>No items added yet. Click "Add Item" to start.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-gear me-1"></i>Options
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="include_vat" value="1" class="form-check-input" id="includeVat">
                        <label class="form-check-label" for="includeVat">Include VAT (7%)</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="Internal notes..."></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Totals -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-calculator me-1"></i>Summary
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">฿0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" id="vatRow" style="display: none !important;">
                        <span>VAT (7%):</span>
                        <span id="vatAmount">฿0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Grand Total:</span>
                        <span id="grandTotal" class="text-primary">฿0.00</span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-lg me-1"></i>Create Quotation
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Item Template -->
<template id="itemTemplate">
    <div class="item-row border rounded p-3 mb-2">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="items[__INDEX__][name]" class="form-control item-name" 
                       placeholder="Item name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[__INDEX__][quantity]" class="form-control item-qty" 
                       value="1" min="1" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="items[__INDEX__][unit_price]" class="form-control item-price" 
                       step="0.01" placeholder="Price" required>
            </div>
            <div class="col-md-2">
                <span class="form-control-plaintext item-total text-end">฿0.00</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="col-12">
                <input type="text" name="items[__INDEX__][description]" class="form-control form-control-sm" 
                       placeholder="Description (optional)">
            </div>
            <input type="hidden" name="items[__INDEX__][type]" value="service">
        </div>
    </div>
</template>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    let itemIndex = 0;
    
    // Add item
    $('#addItem').on('click', function() {
        const template = $('#itemTemplate').html().replace(/__INDEX__/g, itemIndex);
        $('#itemsContainer').append(template);
        $('#noItems').hide();
        itemIndex++;
        recalculate();
    });
    
    // Remove item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        if ($('.item-row').length === 0) {
            $('#noItems').show();
        }
        recalculate();
    });
    
    // Recalculate on input change
    $(document).on('input', '.item-qty, .item-price', function() {
        const row = $(this).closest('.item-row');
        const qty = parseFloat(row.find('.item-qty').val()) || 0;
        const price = parseFloat(row.find('.item-price').val()) || 0;
        const total = qty * price;
        row.find('.item-total').text('฿' + total.toLocaleString('en-US', {minimumFractionDigits: 2}));
        recalculate();
    });
    
    // VAT toggle
    $('#includeVat').on('change', function() {
        if ($(this).is(':checked')) {
            $('#vatRow').show();
        } else {
            $('#vatRow').hide();
        }
        recalculate();
    });
    
    function recalculate() {
        let subtotal = 0;
        $('.item-row').each(function() {
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const price = parseFloat($(this).find('.item-price').val()) || 0;
            subtotal += qty * price;
        });
        
        const includeVat = $('#includeVat').is(':checked');
        const vat = includeVat ? subtotal * 0.07 : 0;
        const grandTotal = subtotal + vat;
        
        $('#subtotal').text('฿' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#vatAmount').text('฿' + vat.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#grandTotal').text('฿' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2}));
    }
    
    // Add first item by default
    $('#addItem').click();
});
</script>
<?= $this->endSection() ?>

