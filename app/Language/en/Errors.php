<?php

/**
 * English Error Messages
 * ASIC Repair Management System
 * 
 * User-friendly error messages without technical details
 */

return [
    // ========================================================================
    // General Errors
    // ========================================================================
    'generalError'          => 'An error occurred. Please try again.',
    'unexpectedError'       => 'An unexpected error occurred. Please contact the administrator.',
    'pageNotFound'          => 'Page not found.',
    'accessDenied'          => 'You do not have permission to access this page.',
    'sessionExpired'        => 'Your session has expired. Please log in again.',
    'unauthorized'          => 'Please log in to continue.',
    'permissionDenied'      => 'You do not have permission to perform this action.',
    'maintenanceMode'       => 'System is under maintenance. Please come back later.',
    'tooManyRequests'       => 'Too many requests. Please wait a moment and try again.',
    'serviceUnavailable'    => 'Service temporarily unavailable. Please try again.',

    // ========================================================================
    // Form Validation Errors
    // ========================================================================
    'fieldRequired'         => 'Please enter {field}.',
    'fieldInvalid'          => '{field} is invalid.',
    'emailInvalid'          => 'Please enter a valid email address.',
    'phoneInvalid'          => 'Please enter a valid phone number.',
    'passwordTooShort'      => 'Password must be at least {0} characters.',
    'passwordMismatch'      => 'Passwords do not match. Please check again.',
    'passwordWeak'          => 'Password is too weak. Please use letters, numbers, and symbols.',
    'usernameExists'        => 'This username is already taken. Please choose another.',
    'emailExists'           => 'This email is already registered.',
    'invalidFormat'         => 'Invalid format.',
    'valueTooLarge'         => '{field} is too large (maximum {max}).',
    'valueTooSmall'         => '{field} is too small (minimum {min}).',
    'textTooLong'           => 'Text is too long (maximum {max} characters).',
    'textTooShort'          => 'Text is too short (minimum {min} characters).',
    'numberOnly'            => 'Please enter numbers only.',
    'positiveNumber'        => 'Please enter a positive number.',
    'dateInvalid'           => 'Please select a valid date.',
    'datePast'              => 'Please select a date in the past.',
    'dateFuture'            => 'Please select a date in the future.',
    'selectOption'          => 'Please select {field}.',

    // ========================================================================
    // Authentication Errors
    // ========================================================================
    'loginFailed'           => 'Unable to log in. Please check your username and password.',
    'accountLocked'         => 'Account is temporarily locked. Please try again in {minutes} minutes.',
    'accountDisabled'       => 'Account has been disabled. Please contact the administrator.',
    'accountNotFound'       => 'Account not found.',
    'wrongPassword'         => 'Incorrect password.',
    'tooManyLoginAttempts'  => 'Too many login attempts. Please wait a moment.',

    // ========================================================================
    // Data Operation Errors
    // ========================================================================
    'createFailed'          => 'Unable to create data. Please try again.',
    'updateFailed'          => 'Unable to update data. Please try again.',
    'deleteFailed'          => 'Unable to delete data. Please try again.',
    'saveFailed'            => 'Unable to save data. Please try again.',
    'dataNotFound'          => 'Data not found.',
    'dataAlreadyExists'     => 'This data already exists in the system.',
    'cannotDeleteInUse'     => 'Cannot delete because this data is still in use.',
    'dataChanged'           => 'Data has been modified by another user. Please refresh the page.',
    'connectionFailed'      => 'Unable to connect to the server. Please check your connection.',

    // ========================================================================
    // Job/Repair Errors
    // ========================================================================
    'jobNotFound'           => 'Job not found.',
    'jobAlreadyClosed'      => 'This job is already closed and cannot be edited.',
    'jobCannotCancel'       => 'Cannot cancel job in the current status.',
    'jobCannotEdit'         => 'Cannot edit job in this status.',
    'invalidStatusChange'   => 'Cannot change status from {from} to {to}.',
    'paymentRequired'       => 'Please complete payment before proceeding.',
    'noPartsAvailable'      => 'Required parts are not available in stock.',
    'jobHasPayments'        => 'Cannot delete a job that has payments.',

    // ========================================================================
    // Inventory Errors
    // ========================================================================
    'insufficientStock'     => 'Insufficient stock (available: {available}, required: {required}).',
    'partNotFound'          => 'Part not found.',
    'partCodeExists'        => 'This part code already exists.',
    'cannotDeletePart'      => 'Cannot delete a part that has stock or is in use.',
    'invalidQuantity'       => 'Invalid quantity.',
    'negativeStock'         => 'Stock cannot be negative.',

    // ========================================================================
    // Customer Errors
    // ========================================================================
    'customerNotFound'      => 'Customer not found.',
    'customerHasJobs'       => 'Cannot delete a customer with repair history.',
    'duplicateCustomer'     => 'This customer already exists in the system.',

    // ========================================================================
    // Asset Errors
    // ========================================================================
    'assetNotFound'         => 'Asset not found.',
    'serialExists'          => 'This serial number already exists in the system.',
    'assetHasActiveJob'     => 'This asset has an incomplete repair job.',

    // ========================================================================
    // Payment Errors
    // ========================================================================
    'paymentFailed'         => 'Payment failed.',
    'invalidAmount'         => 'Invalid amount.',
    'amountExceedsBalance'  => 'Amount exceeds outstanding balance.',
    'refundFailed'          => 'Unable to process refund.',
    'paymentNotFound'       => 'Payment record not found.',

    // ========================================================================
    // Upload Errors
    // ========================================================================
    'uploadFailed'          => 'Upload failed. Please try again.',
    'fileTooLarge'          => 'File is too large (maximum {max}).',
    'invalidFileType'       => 'File type not supported. Please use {types}.',
    'fileCorrupted'         => 'File is corrupted. Please try another file.',
    'noFileSelected'        => 'Please select a file to upload.',
    'uploadDiskFull'        => 'Storage is full. Please contact the administrator.',

    // ========================================================================
    // Backup/Restore Errors
    // ========================================================================
    'backupFailed'          => 'Unable to create backup file.',
    'restoreFailed'         => 'Unable to restore data.',
    'invalidBackupFile'     => 'Backup file is invalid or corrupted.',
    'backupNotFound'        => 'Backup file not found.',
    'restoreWarning'        => 'Restore will replace all current data.',

    // ========================================================================
    // Branch/Settings Errors
    // ========================================================================
    'branchNotFound'        => 'Branch not found.',
    'cannotDeleteBranch'    => 'Cannot delete a branch with users or jobs.',
    'defaultBranchRequired' => 'At least one default branch is required.',
    'settingSaveFailed'     => 'Unable to save settings.',

    // ========================================================================
    // Print/Export Errors
    // ========================================================================
    'printFailed'           => 'Unable to print. Please try again.',
    'exportFailed'          => 'Unable to export file.',
    'noDataToExport'        => 'No data to export.',
    'pdfGenerationFailed'   => 'Unable to generate PDF file.',

    // ========================================================================
    // Network/Connection Errors
    // ========================================================================
    'networkError'          => 'Connection error. Please check your internet.',
    'timeout'               => 'Connection timed out. Please try again.',
    'serverError'           => 'Internal server error.',
    'retryLater'            => 'Please try again later.',

    // ========================================================================
    // Success Messages (for reference)
    // ========================================================================
    'success'               => 'Operation successful.',
    'saved'                 => 'Saved successfully.',
    'deleted'               => 'Deleted successfully.',
    'updated'               => 'Updated successfully.',
    'created'               => 'Created successfully.',

    // ========================================================================
    // Help/Guidance Messages
    // ========================================================================
    'tryAgain'              => 'Please try again.',
    'contactAdmin'          => 'If the problem persists, please contact the administrator.',
    'checkInput'            => 'Please check your input and try again.',
    'refreshPage'           => 'Please refresh the page and try again.',
    'loginAgain'            => 'Please log in again.',
];
