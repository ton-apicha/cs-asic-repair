# Mobile Responsive Design Guide

## Overview
This document explains the mobile responsive design implementation for the ASIC Repair Management System.

---

## Bootstrap Breakpoints Used

| Breakpoint | Size | Class Prefix | Description |
|------------|------|--------------|-------------|
| Extra Small | < 576px | (none) | Mobile phones (portrait) |
| Small | ≥ 576px | `sm` | Mobile phones (landscape) |
| Medium | ≥ 768px | `md` | Tablets |
| Large | ≥ 992px | `lg` | Desktops |
| Extra Large | ≥ 1200px | `xl` | Large desktops |

---

## Customers Page Mobile Implementation

### Problem
The customers table had too many columns for mobile screens, causing:
- Horizontal scrolling
- Unreadable text
- Poor user experience

### Solution
Implemented adaptive column hiding with data relocation:

#### Desktop View (≥ 768px)
Shows all columns in table format:
```
| Name | Phone | Email | Created At | Actions |
```

#### Mobile View (< 768px)
Shows essential columns only:
```
| Name          | Phone         | Actions |
| Customer Name | 0812345678    | [👁️][✏️][+] |
| 📧 email@...  | 📅 01/12/2025 |         |
```

### Implementation Code

**Table Headers**:
```php
<thead>
    <tr>
        <th><?= lang('App.name') ?></th>
        <th><?= lang('App.phone') ?></th>
        <!-- Hidden on mobile (< 768px) -->
        <th class="d-none d-md-table-cell"><?= lang('App.email') ?></th>
        <!-- Hidden on mobile and tablet (< 992px) -->
        <th class="d-none d-lg-table-cell"><?= lang('App.createdAt') ?></th>
        <th><?= lang('App.actions') ?></th>
    </tr>
</thead>
```

**Table Body**:
```php
<tbody>
    <?php foreach ($customers as $customer): ?>
        <tr>
            <!-- Name Column -->
            <td>
                <a href="..."><?= esc($customer['name']) ?></a>
                
                <!-- Email shown below name on mobile only -->
                <div class="d-md-none small text-muted mt-1">
                    <?php if ($customer['email']): ?>
                        <i class="bi bi-envelope me-1"></i>
                        <?= esc($customer['email']) ?>
                    <?php endif; ?>
                </div>
            </td>
            
            <!-- Phone Column -->
            <td>
                <?= esc($customer['phone']) ?>
                
                <!-- Date shown below phone on mobile only -->
                <div class="d-lg-none small text-muted mt-1">
                    <i class="bi bi-calendar me-1"></i>
                    <?= date('d/m/Y', strtotime($customer['created_at'])) ?>
                </div>
            </td>
            
            <!-- Email Column (hidden on mobile) -->
            <td class="d-none d-md-table-cell">
                <?= $customer['email'] ? esc($customer['email']) : '-' ?>
            </td>
            
            <!-- Date Column (hidden on mobile/tablet) -->
            <td class="d-none d-lg-table-cell">
                <?= date('d/m/Y', strtotime($customer['created_at'])) ?>
            </td>
            
            <!-- Actions Column -->
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="..." class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="..." class="btn btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="..." class="btn btn-outline-success">
                        <i class="bi bi-plus"></i>
                    </a>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
```

---

## Bootstrap Responsive Classes Used

### Display Classes
- `d-none` - Hide element
- `d-md-none` - Hide on medium screens and up (show on mobile only)
- `d-md-table-cell` - Show as table cell on medium screens and up
- `d-lg-none` - Hide on large screens and up
- `d-lg-table-cell` - Show as table cell on large screens and up

### Utility Classes
- `small` - Smaller font size
- `text-muted` - Gray text color
- `mt-1` - Margin top (spacing)
- `me-1` - Margin end/right (icon spacing)

---

## Testing Mobile Responsive

### Browser DevTools
1. Open Chrome DevTools (F12)
2. Click "Toggle Device Toolbar" (Ctrl+Shift+M)
3. Select device or set custom dimensions:
   - Mobile: 375x667 (iPhone SE)
   - Tablet: 768x1024 (iPad)
   - Desktop: 1920x1080

### Real Device Testing
Test on actual devices:
- ✅ iPhone (Safari)
- ✅ Android (Chrome)
- ✅ iPad (Safari)

### What to Check
- [ ] Columns hide/show at correct breakpoints
- [ ] Email appears below name on mobile
- [ ] Date appears below phone on mobile
- [ ] Icons display correctly
- [ ] Text is readable (not too small)
- [ ] No horizontal scrolling
- [ ] Action buttons are tappable (not too small)

---

## Best Practices for Future Pages

### 1. Use Responsive Tables
```php
<div class="table-responsive">
    <table class="table">
        <!-- table content -->
    </table>
</div>
```

### 2. Hide Non-Essential Columns on Mobile
```php
<th class="d-none d-md-table-cell">Optional Column</th>
```

### 3. Show Important Data Below Main Content
```php
<td>
    Main Content
    <div class="d-md-none small text-muted mt-1">
        <i class="bi bi-icon"></i> Additional Info
    </div>
</td>
```

### 4. Use Icon Buttons for Actions
```php
<div class="btn-group btn-group-sm">
    <a href="..." class="btn btn-outline-primary" title="View">
        <i class="bi bi-eye"></i>
    </a>
</div>
```

### 5. Test on Multiple Screen Sizes
- Mobile: < 768px
- Tablet: 768px - 991px
- Desktop: ≥ 992px

---

## Common Issues and Solutions

### Issue: Table Too Wide on Mobile
**Solution**: Use `table-responsive` wrapper and hide columns

### Issue: Text Too Small on Mobile
**Solution**: Use appropriate font sizes, avoid `small` class on important text

### Issue: Buttons Too Small to Tap
**Solution**: Use `btn-sm` for desktop, regular `btn` for mobile

### Issue: Horizontal Scrolling
**Solution**: Ensure all content uses responsive classes

---

## Files Modified for Mobile Responsive

1. `app/Views/customers/index.php` - Customers list page
   - Added responsive column hiding
   - Added mobile-friendly data display

2. (Future) Other table views should follow the same pattern

---

## References

- [Bootstrap 5 Display Utilities](https://getbootstrap.com/docs/5.3/utilities/display/)
- [Bootstrap 5 Breakpoints](https://getbootstrap.com/docs/5.3/layout/breakpoints/)
- [Bootstrap 5 Tables](https://getbootstrap.com/docs/5.3/content/tables/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)
