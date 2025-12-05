/**
 * ASIC Repair Management System - Main JavaScript
 * R-POS/CRM Application
 */

(function () {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function () {
        App.init();
    });

    // ========================================================================
    // Main Application Object
    // ========================================================================
    const App = {

        // Configuration
        config: {
            sidebarCollapseKey: 'sidebar_collapsed',
            themeKey: 'theme',
            toastDuration: 4000,
            debounceDelay: 300
        },

        /**
         * Initialize application
         */
        init: function () {
            console.log('Initializing ASIC Repair System...');

            this.initTheme();
            this.initSidebar();
            this.initSubmenu();
            this.initTooltips();
            this.initAutocomplete();
            this.initConfirmDialogs();
            this.initKanban();
            this.initFlyout();
            this.initPageTransitions();
            this.initMobileNav();

            console.log('App initialized successfully');
        },

        // ====================================================================
        // Theme (Dark Mode)
        // ====================================================================
        initTheme: function () {
            const savedTheme = localStorage.getItem(this.config.themeKey);
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Apply saved theme or system preference
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (prefersDark) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }

            // Theme toggle button
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', () => this.toggleTheme());
            }

            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem(this.config.themeKey)) {
                    document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
                }
            });
        },

        toggleTheme: function () {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem(this.config.themeKey, newTheme);

            // Save to server (if logged in)
            if (typeof jQuery !== 'undefined') {
                jQuery.post('/settings/theme', { theme: newTheme })
                    .done(function (response) {
                        console.log('Theme preference saved to server');
                    })
                    .fail(function () {
                        // Silently fail - localStorage still has the value
                        console.log('Could not save theme to server');
                    });
            }

            // Animate toggle
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.classList.add('bounce');
                setTimeout(() => themeToggle.classList.remove('bounce'), 600);
            }
        },

        // ====================================================================
        // Page Transitions
        // ====================================================================
        initPageTransitions: function () {
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
                mainContent.classList.add('page-transition');
            }
        },

        // ====================================================================
        // Mobile Navigation
        // ====================================================================
        initMobileNav: function () {
            // Add active state to mobile nav items
            const currentPath = window.location.pathname;
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

            mobileNavLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href.replace(/\//g, ''))) {
                    link.classList.add('active');
                }
            });
        },

        // ====================================================================
        // Sidebar
        // ====================================================================
        initSidebar: function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Check for saved collapse state
            const isCollapsed = localStorage.getItem(this.config.sidebarCollapseKey) === 'true';
            if (isCollapsed && window.innerWidth >= 992) {
                document.body.classList.add('sidebar-collapsed');
            }

            // Mobile: Open sidebar
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.add('show');
                    sidebarBackdrop.classList.add('show');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Mobile: Close sidebar
            if (sidebarClose) {
                sidebarClose.addEventListener('click', function () {
                    App.closeSidebar();
                });
            }

            // Close on backdrop click
            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', function () {
                    App.closeSidebar();
                });
            }

            // Desktop: Collapse/Expand sidebar
            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    const collapsed = document.body.classList.contains('sidebar-collapsed');
                    localStorage.setItem(App.config.sidebarCollapseKey, collapsed);
                });
            }

            // Handle window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    App.closeSidebar();
                }
            });
        },

        closeSidebar: function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            if (sidebar) sidebar.classList.remove('show');
            if (sidebarBackdrop) sidebarBackdrop.classList.remove('show');
            document.body.style.overflow = '';
        },

        // ====================================================================
        // Submenu Toggle
        // ====================================================================
        initSubmenu: function () {
            const submenuToggles = document.querySelectorAll('.submenu-toggle');

            submenuToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();

                    const menuItem = this.closest('.menu-item');
                    const submenu = menuItem.querySelector('.submenu');

                    // Close other open submenus (optional - accordion style)
                    // const otherItems = document.querySelectorAll('.menu-item.open');
                    // otherItems.forEach(function(item) {
                    //     if (item !== menuItem) {
                    //         item.classList.remove('open');
                    //         item.querySelector('.submenu').classList.remove('show');
                    //     }
                    // });

                    // Toggle current submenu
                    menuItem.classList.toggle('open');
                    submenu.classList.toggle('show');
                });
            });
        },

        // ====================================================================
        // Bootstrap Tooltips
        // ====================================================================
        initTooltips: function () {
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                    new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        },

        // ====================================================================
        // jQuery Autocomplete
        // ====================================================================
        initAutocomplete: function () {
            if (typeof jQuery === 'undefined' || typeof jQuery.ui === 'undefined') {
                return;
            }

            const $ = jQuery;

            // Customer Autocomplete
            $('#customerSearch').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: App.getBaseUrl() + 'customers/search',
                        data: { term: request.term },
                        dataType: 'json',
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 2,
                select: function (event, ui) {
                    $('#customerId').val(ui.item.id);
                    $('#customerSearch').val(ui.item.label);
                    if (ui.item.phone) {
                        $('#customerPhone').val(ui.item.phone);
                    }
                    return false;
                }
            });

            // Asset/Serial Number Autocomplete
            $('#serialSearch').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: App.getBaseUrl() + 'assets/search',
                        data: { term: request.term },
                        dataType: 'json',
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 2,
                select: function (event, ui) {
                    $('#assetId').val(ui.item.id);
                    if (ui.item.model) {
                        $('#assetModel').val(ui.item.model);
                    }
                    return false;
                }
            });

            // Parts/Inventory Autocomplete
            $('#partSearch').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: App.getBaseUrl() + 'inventory/search',
                        data: { term: request.term },
                        dataType: 'json',
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 2,
                select: function (event, ui) {
                    $('#partId').val(ui.item.id);
                    $('#partName').val(ui.item.label);
                    if (ui.item.price) {
                        $('#partPrice').val(ui.item.price);
                    }
                    return false;
                }
            });
        },

        // ====================================================================
        // Confirm Dialogs
        // ====================================================================
        initConfirmDialogs: function () {
            document.querySelectorAll('[data-confirm]').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    const message = this.dataset.confirm || 'Are you sure?';
                    if (!confirm(message)) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                });
            });
        },

        // ====================================================================
        // Kanban Board
        // ====================================================================
        initKanban: function () {
            if (typeof Sortable === 'undefined') {
                return;
            }

            const kanbanColumns = document.querySelectorAll('.kanban-column-body');

            kanbanColumns.forEach(function (column) {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',

                    onEnd: function (evt) {
                        const jobId = evt.item.dataset.id;
                        const newStatus = evt.to.closest('.kanban-column').dataset.status;
                        const oldStatus = evt.from.closest('.kanban-column').dataset.status;

                        if (newStatus !== oldStatus) {
                            App.updateJobStatus(jobId, newStatus);
                        }
                    }
                });
            });
        },

        updateJobStatus: function (jobId, status) {
            if (typeof jQuery === 'undefined') {
                console.error('jQuery not available');
                return;
            }

            const $ = jQuery;

            $.ajax({
                url: App.getBaseUrl() + 'jobs/update-status/' + jobId,
                method: 'POST',
                data: { status: status },
                dataType: 'json',
                beforeSend: function () {
                    App.showLoading();
                },
                success: function (response) {
                    if (response.success) {
                        App.showToast('Job status updated', 'success');
                    } else {
                        App.showToast(response.message || 'Failed to update status', 'danger');
                        // Reload to restore original state
                        location.reload();
                    }
                },
                error: function () {
                    App.showToast('Failed to update job status', 'danger');
                    location.reload();
                },
                complete: function () {
                    App.hideLoading();
                }
            });
        },

        // ====================================================================
        // Flyout Sidebar
        // ====================================================================
        initFlyout: function () {
            const flyout = document.querySelector('.flyout-sidebar');
            const backdrop = document.querySelector('.flyout-sidebar-backdrop');

            if (!flyout) return;

            // Close on backdrop click
            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    App.closeFlyout();
                });
            }

            // Close button
            const closeBtn = flyout.querySelector('.flyout-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    App.closeFlyout();
                });
            }

            // ESC key to close
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && flyout.classList.contains('show')) {
                    App.closeFlyout();
                }
            });
        },

        openFlyout: function (content) {
            const flyout = document.querySelector('.flyout-sidebar');
            const backdrop = document.querySelector('.flyout-sidebar-backdrop');
            const body = flyout.querySelector('.flyout-sidebar-body');

            if (content && body) {
                body.innerHTML = content;
            }

            flyout.classList.add('show');
            backdrop.classList.add('show');
            document.body.style.overflow = 'hidden';
        },

        closeFlyout: function () {
            const flyout = document.querySelector('.flyout-sidebar');
            const backdrop = document.querySelector('.flyout-sidebar-backdrop');

            if (flyout) flyout.classList.remove('show');
            if (backdrop) backdrop.classList.remove('show');
            document.body.style.overflow = '';
        },

        // ====================================================================
        // Toast Notifications
        // ====================================================================
        showToast: function (message, type) {
            type = type || 'info';

            const toastEl = document.getElementById('appToast');
            if (!toastEl || typeof bootstrap === 'undefined') {
                console.log('Toast:', type, message);
                return;
            }

            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastBody = document.getElementById('toastBody');

            // Set icon and colors based on type
            const typeConfig = {
                success: { icon: 'bi-check-circle-fill', title: 'Success', class: 'text-success' },
                danger: { icon: 'bi-exclamation-circle-fill', title: 'Error', class: 'text-danger' },
                warning: { icon: 'bi-exclamation-triangle-fill', title: 'Warning', class: 'text-warning' },
                info: { icon: 'bi-info-circle-fill', title: 'Info', class: 'text-info' }
            };

            const config = typeConfig[type] || typeConfig.info;

            toastIcon.className = 'bi ' + config.icon + ' me-2 ' + config.class;
            toastTitle.textContent = config.title;
            toastBody.textContent = message;

            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: this.config.toastDuration
            });

            toast.show();
        },

        // ====================================================================
        // Loading Spinner (Enhanced)
        // ====================================================================
        showLoading: function (message) {
            const spinner = document.getElementById('globalSpinner');
            const spinnerText = document.getElementById('spinnerText');

            if (spinner) {
                if (spinnerText && message) {
                    spinnerText.textContent = message;
                } else if (spinnerText) {
                    spinnerText.textContent = 'กำลังโหลด...';
                }
                spinner.style.display = 'flex';
            }
        },

        hideLoading: function () {
            const spinner = document.getElementById('globalSpinner');
            if (spinner) {
                spinner.classList.add('fade-out');
                setTimeout(() => {
                    spinner.style.display = 'none';
                    spinner.classList.remove('fade-out');
                }, 200);
            }
        },

        /**
         * Show button loading state
         * @param {HTMLElement|jQuery} button - Button element
         * @param {string} loadingText - Text to show while loading
         */
        setButtonLoading: function (button, loadingText) {
            const btn = button.jquery ? button[0] : button;
            if (!btn) return;

            btn.dataset.originalText = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('loading');

            if (loadingText) {
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${loadingText}`;
            }
        },

        /**
         * Reset button loading state
         * @param {HTMLElement|jQuery} button - Button element
         */
        resetButton: function (button) {
            const btn = button.jquery ? button[0] : button;
            if (!btn) return;

            btn.disabled = false;
            btn.classList.remove('loading');

            if (btn.dataset.originalText) {
                btn.innerHTML = btn.dataset.originalText;
                delete btn.dataset.originalText;
            }
        },

        /**
         * Generate skeleton loader HTML for tables
         * @param {number} rows - Number of skeleton rows
         * @param {number} cols - Number of columns
         */
        generateTableSkeleton: function (rows, cols) {
            rows = rows || 5;
            cols = cols || 5;

            let html = '<div class="skeleton-table">';
            for (let i = 0; i < rows; i++) {
                html += '<div class="skeleton-row">';
                for (let j = 0; j < cols; j++) {
                    html += '<div class="skeleton skeleton-cell"></div>';
                }
                html += '</div>';
            }
            html += '</div>';

            return html;
        },

        // ====================================================================
        // Utilities
        // ====================================================================
        getBaseUrl: function () {
            // Get base URL from the page or default
            const baseEl = document.querySelector('base');
            if (baseEl) return baseEl.href;

            // Try to extract from current URL
            const path = window.location.pathname;
            const segments = path.split('/');
            if (segments.length > 1 && segments[1]) {
                return window.location.origin + '/' + segments[1] + '/';
            }

            return window.location.origin + '/';
        },

        debounce: function (func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        formatCurrency: function (amount, locale, currency) {
            locale = locale || 'th-TH';
            currency = currency || 'THB';

            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency: currency
            }).format(amount);
        },

        formatDate: function (dateString, locale) {
            locale = locale || 'th-TH';
            const date = new Date(dateString);

            return new Intl.DateTimeFormat(locale, {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }
    };

    // Expose to global scope
    window.App = App;

})();

// ============================================================================
// jQuery Extensions (if jQuery is available)
// ============================================================================
if (typeof jQuery !== 'undefined') {
    (function ($) {
        'use strict';

        // AJAX form submit with loading state
        $.fn.ajaxForm = function (options) {
            return this.each(function () {
                const $form = $(this);

                $form.on('submit', function (e) {
                    e.preventDefault();

                    const $submitBtn = $form.find('[type="submit"]');
                    const originalText = $submitBtn.html();

                    $.ajax({
                        url: $form.attr('action'),
                        method: $form.attr('method') || 'POST',
                        data: $form.serialize(),
                        dataType: 'json',
                        beforeSend: function () {
                            $submitBtn.prop('disabled', true)
                                .html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
                        },
                        success: function (response) {
                            if (response.success) {
                                if (options && options.onSuccess) {
                                    options.onSuccess(response);
                                } else if (response.redirect) {
                                    window.location.href = response.redirect;
                                } else {
                                    App.showToast(response.message || 'Success', 'success');
                                }
                            } else {
                                App.showToast(response.message || 'An error occurred', 'danger');
                            }
                        },
                        error: function (xhr) {
                            let message = 'An error occurred';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            App.showToast(message, 'danger');
                        },
                        complete: function () {
                            $submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });
            });
        };

        // Confirmation before action
        $(document).on('click', '[data-confirm]', function (e) {
            const message = $(this).data('confirm') || 'Are you sure you want to proceed?';
            if (!confirm(message)) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        // Auto-submit form on change
        $(document).on('change', '[data-auto-submit]', function () {
            $(this).closest('form').submit();
        });

    })(jQuery);
}
