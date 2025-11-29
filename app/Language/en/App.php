<?php

/**
 * English Language File
 * ASIC Repair Management System
 */

return [
    // ========================================================================
    // Authentication
    // ========================================================================
    'login'              => 'Login',
    'logout'             => 'Logout',
    'username'           => 'Username',
    'password'           => 'Password',
    'enterUsername'      => 'Enter your username',
    'enterPassword'      => 'Enter your password',
    'rememberMe'         => 'Remember me',
    'invalidCredentials' => 'Invalid username or password',
    'pleaseLogin'        => 'Please login to continue',
    'loggedOut'          => 'You have been logged out successfully',
    'welcomeBack'        => 'Welcome back, {0}!',
    'accessDenied'       => 'Access denied. You do not have permission to access this page.',

    // ========================================================================
    // Navigation
    // ========================================================================
    'dashboard'      => 'Dashboard',
    'management'     => 'Management',
    'jobs'           => 'Jobs',
    'allJobs'        => 'All Jobs',
    'kanbanBoard'    => 'Kanban Board',
    'newJob'         => 'New Job',
    'customers'      => 'Customers',
    'assets'         => 'Assets',
    'inventory'      => 'Inventory',
    'reports'        => 'Reports',
    'settings'       => 'Settings',
    'systemSettings' => 'System Settings',
    'branches'       => 'Branches',
    'users'          => 'Users',

    // ========================================================================
    // Reports
    // ========================================================================
    'salesReport'    => 'Sales Report',
    'profitReport'   => 'Profit Report',
    'warrantyReport' => 'Warranty Report',
    'wipReport'      => 'Work in Progress',
    'kpiReport'      => 'KPI Dashboard',

    // ========================================================================
    // Common Actions
    // ========================================================================
    'create'   => 'Create',
    'edit'     => 'Edit',
    'update'   => 'Update',
    'delete'   => 'Delete',
    'save'     => 'Save',
    'cancel'   => 'Cancel',
    'search'   => 'Search',
    'filter'   => 'Filter',
    'export'   => 'Export',
    'print'    => 'Print',
    'view'     => 'View',
    'back'     => 'Back',
    'close'    => 'Close',
    'confirm'  => 'Confirm',
    'actions'  => 'Actions',
    'submit'   => 'Submit',
    'reset'    => 'Reset',
    'refresh'  => 'Refresh',
    'add'      => 'Add',
    'remove'   => 'Remove',

    // ========================================================================
    // Common Labels
    // ========================================================================
    'id'          => 'ID',
    'name'        => 'Name',
    'email'       => 'Email',
    'phone'       => 'Phone',
    'address'     => 'Address',
    'status'      => 'Status',
    'date'        => 'Date',
    'createdAt'   => 'Created At',
    'updatedAt'   => 'Updated At',
    'createdBy'   => 'Created By',
    'description' => 'Description',
    'notes'       => 'Notes',
    'total'       => 'Total',
    'amount'      => 'Amount',
    'quantity'    => 'Quantity',
    'price'       => 'Price',
    'type'        => 'Type',
    'all'         => 'All',
    'active'      => 'Active',
    'inactive'    => 'Inactive',
    'yes'         => 'Yes',
    'no'          => 'No',

    // ========================================================================
    // Customer
    // ========================================================================
    'customer'          => 'Customer',
    'customerName'      => 'Customer Name',
    'customerPhone'     => 'Phone Number',
    'customerEmail'     => 'Email Address',
    'customerAddress'   => 'Address',
    'customerTaxId'     => 'Tax ID',
    'newCustomer'       => 'New Customer',
    'editCustomer'      => 'Edit Customer',
    'customerDetails'   => 'Customer Details',
    'customerHistory'   => 'Customer History',
    'searchCustomer'    => 'Search customer by name or phone...',
    'noCustomersFound'  => 'No customers found',
    'customerCreated'   => 'Customer created successfully',
    'customerUpdated'   => 'Customer updated successfully',
    'customerDeleted'   => 'Customer deleted successfully',

    // ========================================================================
    // Asset
    // ========================================================================
    'asset'             => 'Asset',
    'assetBrandModel'   => 'Brand/Model',
    'assetSerialNumber' => 'Serial Number',
    'assetMacAddress'   => 'MAC Address',
    'assetHashRate'     => 'Hash Rate (TH/s)',
    'assetCondition'    => 'External Condition',
    'assetStatus'       => 'Asset Status',
    'newAsset'          => 'New Asset',
    'editAsset'         => 'Edit Asset',
    'assetDetails'      => 'Asset Details',
    'assetHistory'      => 'Repair History',
    'searchAsset'       => 'Search by serial number...',
    'noAssetsFound'     => 'No assets found',
    'assetCreated'      => 'Asset created successfully',
    'assetUpdated'      => 'Asset updated successfully',
    'assetDeleted'      => 'Asset deleted successfully',
    'createJobFromAsset' => 'Create Job for this Asset',

    // Asset Statuses
    'assetStatusStored'    => 'Stored',
    'assetStatusRepairing' => 'Repairing',
    'assetStatusRepaired'  => 'Repaired',
    'assetStatusReturned'  => 'Returned',

    // ========================================================================
    // Job Card
    // ========================================================================
    'job'              => 'Job',
    'jobCard'          => 'Job Card',
    'jobId'            => 'Job ID',
    'jobDate'          => 'Date',
    'jobSymptom'       => 'Symptoms',
    'jobDiagnosis'     => 'Diagnosis',
    'jobSolution'      => 'Solution',
    'jobNotes'         => 'Notes',
    'jobStatus'        => 'Status',
    'jobTechnician'    => 'Technician',
    'jobBranch'        => 'Branch',
    'newJobCard'       => 'New Job Card',
    'editJobCard'      => 'Edit Job Card',
    'jobDetails'       => 'Job Details',
    'jobParts'         => 'Parts Used',
    'jobPayments'      => 'Payments',
    'searchJob'        => 'Search by Job ID...',
    'noJobsFound'      => 'No jobs found',
    'jobCreated'       => 'Job created successfully',
    'jobUpdated'       => 'Job updated successfully',
    'jobCancelled'     => 'Job cancelled',
    'printCheckinSlip' => 'Print Check-in Slip',
    'printLabel'       => 'Print Label (A4)',
    'symptomPlaceholder' => 'Describe the problem or symptoms...',

    // Warranty Claim
    'warrantyClaim'    => 'Warranty Claim',
    'isWarrantyClaim'  => 'This is a warranty claim',
    'originalJobId'    => 'Original Job ID',
    'viewOriginalJob'  => 'View Original Job',

    // Job Statuses
    'statusNewCheckin'    => 'New Check-in',
    'statusPendingRepair' => 'Pending Repair',
    'statusInProgress'    => 'In Progress',
    'statusRepairDone'    => 'Repair Done',
    'statusReadyHandover' => 'Ready for Handover',
    'statusDelivered'     => 'Delivered/Closed',
    'statusCancelled'     => 'Cancelled',

    // ========================================================================
    // Inventory
    // ========================================================================
    'part'              => 'Part',
    'partCode'          => 'Part Code',
    'partName'          => 'Part Name',
    'partDescription'   => 'Description',
    'partCostPrice'     => 'Cost Price',
    'partSellPrice'     => 'Sell Price',
    'partQuantity'      => 'Quantity',
    'partLocation'      => 'Location',
    'partSerialNumber'  => 'Serial Number',
    'reorderPoint'      => 'Reorder Point',
    'newPart'           => 'New Part',
    'editPart'          => 'Edit Part',
    'partDetails'       => 'Part Details',
    'stockLevel'        => 'Stock Level',
    'lowStock'          => 'Low Stock',
    'outOfStock'        => 'Out of Stock',
    'inStock'           => 'In Stock',
    'searchPart'        => 'Search by code or name...',
    'noPartsFound'      => 'No parts found',
    'partCreated'       => 'Part created successfully',
    'partUpdated'       => 'Part updated successfully',
    'partDeleted'       => 'Part deleted successfully',
    'stockTransaction'  => 'Stock Transactions',
    'lowStockAlert'     => 'Low Stock Alert',

    // ========================================================================
    // Payment
    // ========================================================================
    'payment'           => 'Payment',
    'paymentMethod'     => 'Payment Method',
    'paymentCash'       => 'Cash',
    'paymentTransfer'   => 'Bank Transfer / QR Code',
    'paymentReference'  => 'Reference Number',
    'paymentDate'       => 'Payment Date',
    'paymentAmount'     => 'Amount',
    'laborCost'         => 'Labor Cost',
    'partsCost'         => 'Parts Cost',
    'subtotal'          => 'Subtotal',
    'vatAmount'         => 'VAT (7%)',
    'grandTotal'        => 'Grand Total',
    'amountPaid'        => 'Amount Paid',
    'amountDue'         => 'Amount Due',
    'paymentReceived'   => 'Payment received successfully',
    'insufficientPayment' => 'Insufficient payment amount',

    // ========================================================================
    // Settings
    // ========================================================================
    'branch'            => 'Branch',
    'branchName'        => 'Branch Name',
    'branchAddress'     => 'Branch Address',
    'newBranch'         => 'New Branch',
    'editBranch'        => 'Edit Branch',
    'branchCreated'     => 'Branch created successfully',
    'branchUpdated'     => 'Branch updated successfully',
    'branchDeleted'     => 'Branch deleted successfully',

    'user'              => 'User',
    'userRole'          => 'Role',
    'roleAdmin'         => 'Administrator',
    'roleTechnician'    => 'Technician',
    'roleSuperAdmin'    => 'Super Admin',
    'newUser'           => 'New User',
    'allBranches'       => 'All Branches',
    'filterByBranch'    => 'Filter by Branch',
    'superAdminNote'    => 'Super Admin can access all branches',
    'superAdminNoBranch'=> 'Super Admin is not assigned to any branch',
    'adminNote'         => 'Branch Admin manages one branch',
    'branchRequired'    => 'Select a branch for this user',
    'editUser'          => 'Edit User',
    'userCreated'       => 'User created successfully',
    'userUpdated'       => 'User updated successfully',
    'userDeleted'       => 'User deleted successfully',
    'confirmPassword'   => 'Confirm Password',
    'passwordMismatch'  => 'Passwords do not match',
    'lastLogin'         => 'Last Login',

    'vatType'           => 'VAT Type',
    'vatInclusive'      => 'VAT Inclusive (7%)',
    'vatNone'           => 'No VAT',
    'settingsSaved'     => 'Settings saved successfully',

    // ========================================================================
    // Dashboard
    // ========================================================================
    'todayJobs'         => 'Today\'s Jobs',
    'monthlyJobs'       => 'Monthly Jobs',
    'pendingJobs'       => 'Pending Jobs',
    'completedJobs'     => 'Completed Jobs',
    'todayRevenue'      => 'Today\'s Revenue',
    'monthlyRevenue'    => 'Monthly Revenue',
    'lowStockItems'     => 'Low Stock Items',
    'recentJobs'        => 'Recent Jobs',
    'revenueChart'      => 'Revenue (Last 7 Days)',
    'jobsByStatus'      => 'Jobs by Status',

    // ========================================================================
    // Validation & Messages
    // ========================================================================
    'required'          => 'This field is required',
    'invalidEmail'      => 'Please enter a valid email address',
    'invalidPhone'      => 'Please enter a valid phone number',
    'minLength'         => 'Minimum {0} characters required',
    'maxLength'         => 'Maximum {0} characters allowed',
    'confirmDelete'     => 'Are you sure you want to delete this item?',
    'confirmCancel'     => 'Are you sure you want to cancel this job?',
    'operationSuccess'  => 'Operation completed successfully',
    'operationFailed'   => 'Operation failed. Please try again.',
    'recordNotFound'    => 'Record not found',
    'noRecords'         => 'No records found',
    'loadingData'       => 'Loading data...',
    'processingRequest' => 'Processing request...',

    // ========================================================================
    // Quotation
    // ========================================================================
    'quotation'          => 'Quotation',
    'quotations'         => 'Quotations',
    'quotationNo'        => 'Quotation No',
    'newQuotation'       => 'New Quotation',
    'editQuotation'      => 'Edit Quotation',
    'quotationDetails'   => 'Quotation Details',
    'validUntil'         => 'Valid Until',
    'proposedSolution'   => 'Proposed Solution',
    'issueDescription'   => 'Issue Description',
    'quotationCreated'   => 'Quotation created successfully',
    'quotationUpdated'   => 'Quotation updated successfully',
    'quotationApproved'  => 'Quotation approved successfully',
    'quotationRejected'  => 'Quotation rejected',
    'convertToJob'       => 'Convert to Job',
    'quotationStatus'    => 'Quotation Status',
    'statusDraft'        => 'Draft',
    'statusSent'         => 'Sent',
    'statusApproved'     => 'Approved',
    'statusRejected'     => 'Rejected',
    'statusConverted'    => 'Converted',
    'statusExpired'      => 'Expired',
    'searchQuotation'    => 'Search quotation or customer...',
    'noQuotationsFound'  => 'No quotations found',
    'allStatus'          => 'All Status',
    'approveQuotation'   => 'Approve Quotation',
    'rejectQuotation'    => 'Reject Quotation',
    'quotationTerms'     => 'Terms & Conditions',
    'quotationValidDays' => 'This quotation is valid for 30 days from the issue date.',
    'quotationPaymentTerms' => 'Payment: 50% deposit, 50% upon completion.',
    'quotationWarranty'  => 'Warranty: 30 days for parts, 7 days for labor.',
    'priceSubjectChange' => 'Prices subject to change based on actual repair requirements.',

    // ========================================================================
    // Customer Portal
    // ========================================================================
    'trackRepair'        => 'Track Your Repair',
    'trackYourStatus'    => 'Track Your Repair Status',
    'enterJobId'         => 'Enter your Job ID',
    'trackNow'           => 'Track Now',
    'jobNotFound'        => 'Job not found',
    'checkJobIdTryAgain' => 'Please check your Job ID and try again.',
    'deviceInfo'         => 'Device Information',
    'checkInDate'        => 'Check-in Date',
    'estimatedCost'      => 'Estimated Cost',
    'problemDescription' => 'Problem Description',
    'progressTimeline'   => 'Progress Timeline',
    'deviceReady'        => 'Your device is ready for pickup!',
    'bringIdToCollect'   => 'Please bring your ID and this Job ID when you come to collect.',
    'repairHistory'      => 'Repair History',
    'enterPhone'         => 'Enter your phone number',
    'viewHistory'        => 'View History',
    'noRepairHistory'    => 'No repair history found',
    'staffLogin'         => 'Staff Login',
    'needHelp'           => 'Need help?',
    'contactUs'          => 'Contact us at',
    'model'              => 'Model',
    'serialNumber'       => 'Serial Number',
    'checkedIn'          => 'Checked In',
    'awaitingRepair'     => 'Awaiting Repair',
    'repairInProgress'   => 'Repair In Progress',
    'repairComplete'     => 'Repair Complete',
    'readyForPickup'     => 'Ready for Pickup',
    'delivered'          => 'Delivered',
    'current'            => 'Current',
    'professionalService' => 'Professional Mining Hardware Service',

    // ========================================================================
    // Backup & Restore
    // ========================================================================
    'backup'             => 'Backup',
    'restore'            => 'Restore',
    'backupRestore'      => 'Backup & Restore',
    'createBackup'       => 'Create Backup',
    'createBackupNow'    => 'Create Backup Now',
    'restoreBackup'      => 'Restore Backup',
    'availableBackups'   => 'Available Backups',
    'uploadBackupFile'   => 'Upload Backup File',
    'noBackupsFound'     => 'No backups found',
    'backupCreated'      => 'Backup created successfully',
    'backupRestored'     => 'Backup restored successfully',
    'backupDeleted'      => 'Backup deleted successfully',
    'confirmRestore'     => 'Are you sure you want to restore this backup?',
    'restoreWarning'     => 'Warning: This will overwrite all current data!',
    'backupTips'         => 'Backup Tips',
    'filename'           => 'Filename',
    'fileSize'           => 'Size',
    'created'            => 'Created',
    'download'           => 'Download',
    'backupDescription'  => 'Create a complete backup of your database including all customers, jobs, inventory, and settings.',
    'restoreDescription' => 'Restore your database from a backup file.',
    'backupTip1'         => 'Create backups regularly, especially before making major changes.',
    'backupTip2'         => 'Download and store backups in a safe location (cloud storage, external drive).',
    'backupTip3'         => 'Compressed (.gz) backups use less storage space.',
    'backupTip4'         => 'Test your backups periodically by restoring to a test environment.',
    'backupTip5'         => 'Keep at least 3 recent backups at all times.',
    'backToSettings'     => 'Back to Settings',

    // ========================================================================
    // Credit System
    // ========================================================================
    'creditSettings'     => 'Credit Settings',
    'creditLimit'        => 'Credit Limit',
    'creditUsed'         => 'Credit Used',
    'creditAvailable'    => 'Credit Available',
    'creditTerms'        => 'Credit Terms',
    'creditBalance'      => 'Credit Balance',
    'outstandingBalance' => 'Outstanding Balance',

    // ========================================================================
    // UI/Theme
    // ========================================================================
    'darkMode'           => 'Dark Mode',
    'lightMode'          => 'Light Mode',
    'theme'              => 'Theme',
    'switchTheme'        => 'Switch Theme',
    'language'           => 'Language',
    'selectLanguage'     => 'Select Language',
    'english'            => 'English',
    'thai'               => 'Thai',
    'chinese'            => 'Chinese',

    // ========================================================================
    // Settings Extended
    // ========================================================================
    'companyInfo'        => 'Company Information',
    'companyName'        => 'Company Name',
    'companyLogo'        => 'Company Logo',
    'taxId'              => 'Tax ID',
    'taxSettings'        => 'Tax Settings',
    'vatRate'            => 'VAT Rate',
    'systemConfig'       => 'System Configuration',
    'warrantyDays'       => 'Warranty Period',
    'days'               => 'days',
    'jobIdPrefix'        => 'Job ID Prefix',
    'jobIdPrefixHint'    => 'e.g., JOB- will display as JOB-001',
    'currency'           => 'Currency',
    'defaultLanguage'    => 'Default Language',
    'uploadLogo'         => 'Upload Logo',
    'uploadNewLogo'      => 'Upload New Logo',
    'deleteLogo'         => 'Delete Logo',
    'logoUploaded'       => 'Logo uploaded successfully',
    'logoDeleted'        => 'Logo deleted successfully',
    'noLogoUploaded'     => 'No logo uploaded',
    'logoHint'           => 'Supports: JPG, PNG, GIF, WebP (max 2MB)',
    'uploadFailed'       => 'Upload failed',
    'invalidFileType'    => 'Invalid file type',
    'fileTooLarge'       => 'File is too large',
    'quickLinks'         => 'Quick Links',
    'branchManagement'   => 'Branch Management',
    'userManagement'     => 'User Management',
    'addBranch'          => 'Add Branch',
    'usernameCannotChange' => 'Username cannot be changed',
    'leaveBlankPassword' => 'Leave blank to keep current password',
    'userStatusNote'     => 'Inactive users cannot log in',
];

