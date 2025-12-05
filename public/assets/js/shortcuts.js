/**
 * ASIC Repair - Keyboard Shortcuts
 * Global keyboard shortcuts for quick navigation and actions
 */

(function () {
    'use strict';

    const KeyboardShortcuts = {
        enabled: true,
        shortcuts: {},
        helpModal: null,

        /**
         * Initialize keyboard shortcuts
         */
        init: function () {
            this.registerDefaultShortcuts();
            this.createHelpModal();
            this.bindEvents();
            this.showShortcutHints();
        },

        /**
         * Register default shortcuts
         */
        registerDefaultShortcuts: function () {
            // Navigation shortcuts
            this.register('g h', 'ไปที่แดชบอร์ด', () => {
                window.location.href = '/dashboard';
            });

            this.register('g j', 'ไปที่งานซ่อม', () => {
                window.location.href = '/jobs';
            });

            this.register('g k', 'ไปที่ Kanban', () => {
                window.location.href = '/jobs/kanban';
            });

            this.register('g c', 'ไปที่ลูกค้า', () => {
                window.location.href = '/customers';
            });

            this.register('g i', 'ไปที่คลังอะไหล่', () => {
                window.location.href = '/inventory';
            });

            // Quick actions
            this.register('n', 'สร้างใบงานใหม่', () => {
                window.location.href = '/jobs/create';
            }, 'Shift');

            this.register('/', 'Focus ช่องค้นหา', (e) => {
                e.preventDefault();
                const searchInput = document.querySelector('#globalSearch, #search, [name="search"], .search-input');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            });

            this.register('Escape', 'ปิด Modal/ยกเลิก', () => {
                // Close any open Bootstrap modal
                const openModal = document.querySelector('.modal.show');
                if (openModal && typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getInstance(openModal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }

                // Close print preview if open
                if (typeof PrintPreview !== 'undefined') {
                    PrintPreview.close();
                }

                // Blur focused input
                if (document.activeElement && document.activeElement.blur) {
                    document.activeElement.blur();
                }
            });

            this.register('?', 'แสดงคีย์ลัด', () => {
                this.showHelp();
            }, 'Shift');

            // Form shortcuts (when not in input)
            this.register('s', 'บันทึกฟอร์ม', (e) => {
                e.preventDefault();
                const form = document.querySelector('form:not(.filter-form)');
                if (form) {
                    const submitBtn = form.querySelector('[type="submit"]');
                    if (submitBtn) {
                        submitBtn.click();
                    } else {
                        form.submit();
                    }
                }
            }, 'Ctrl');
        },

        /**
         * Register a keyboard shortcut
         * @param {string} key - Key combination (e.g., 'Ctrl+S', 'g h')
         * @param {string} description - Description for help
         * @param {function} callback - Function to execute
         * @param {string} modifier - Optional modifier key
         */
        register: function (key, description, callback, modifier) {
            this.shortcuts[key] = {
                key: key,
                description: description,
                callback: callback,
                modifier: modifier || null
            };
        },

        /**
         * Bind keyboard events
         */
        bindEvents: function () {
            let sequenceBuffer = '';
            let sequenceTimeout = null;

            document.addEventListener('keydown', (e) => {
                if (!this.enabled) return;

                // Skip if in input/textarea/contenteditable
                if (this.isTypingContext(e.target)) {
                    // Allow Escape in inputs
                    if (e.key === 'Escape') {
                        e.target.blur();
                    }
                    // Allow Ctrl+S even in inputs
                    if (!(e.ctrlKey && e.key === 's')) {
                        return;
                    }
                }

                // Build modifier string
                const modifiers = [];
                if (e.ctrlKey) modifiers.push('Ctrl');
                if (e.shiftKey) modifiers.push('Shift');
                if (e.altKey) modifiers.push('Alt');
                if (e.metaKey) modifiers.push('Meta');

                const key = e.key;

                // Check for single key shortcuts with modifiers
                for (const shortcut in this.shortcuts) {
                    const sc = this.shortcuts[shortcut];

                    // Check Ctrl+key combinations
                    if (sc.modifier === 'Ctrl' && e.ctrlKey && !e.shiftKey && key.toLowerCase() === shortcut.toLowerCase()) {
                        e.preventDefault();
                        sc.callback(e);
                        return;
                    }

                    // Check Shift+key combinations
                    if (sc.modifier === 'Shift' && e.shiftKey && !e.ctrlKey) {
                        if (key === shortcut || (shortcut === '?' && key === '?')) {
                            e.preventDefault();
                            sc.callback(e);
                            return;
                        }
                    }
                }

                // Handle sequence shortcuts (e.g., 'g h')
                if (!e.ctrlKey && !e.altKey && !e.metaKey) {
                    // Clear timeout for sequence reset
                    if (sequenceTimeout) {
                        clearTimeout(sequenceTimeout);
                    }

                    // Add to sequence buffer
                    sequenceBuffer += (sequenceBuffer ? ' ' : '') + key.toLowerCase();

                    // Check for matching sequence
                    for (const shortcut in this.shortcuts) {
                        if (shortcut.toLowerCase() === sequenceBuffer && !this.shortcuts[shortcut].modifier) {
                            e.preventDefault();
                            this.shortcuts[shortcut].callback(e);
                            sequenceBuffer = '';
                            return;
                        }
                    }

                    // Reset sequence after delay
                    sequenceTimeout = setTimeout(() => {
                        sequenceBuffer = '';
                    }, 1000);
                }

                // Single key shortcuts
                if (this.shortcuts[key] && !this.shortcuts[key].modifier && !e.ctrlKey && !e.shiftKey && !e.altKey) {
                    e.preventDefault();
                    this.shortcuts[key].callback(e);
                }
            });
        },

        /**
         * Check if we're in a typing context
         */
        isTypingContext: function (element) {
            if (!element) return false;

            const tagName = element.tagName.toLowerCase();
            if (tagName === 'input' || tagName === 'textarea' || tagName === 'select') {
                return true;
            }

            if (element.isContentEditable) {
                return true;
            }

            return false;
        },

        /**
         * Create help modal
         */
        createHelpModal: function () {
            if (document.getElementById('shortcutsModal')) {
                this.helpModal = document.getElementById('shortcutsModal');
                return;
            }

            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = 'shortcutsModal';
            modal.setAttribute('tabindex', '-1');
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-keyboard me-2"></i>
                                คีย์ลัด
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="shortcuts-list">
                                ${this.generateShortcutsList()}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <small class="text-muted">กด <kbd>?</kbd> เพื่อเปิดหน้านี้</small>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            this.helpModal = modal;
        },

        /**
         * Generate shortcuts list HTML
         */
        generateShortcutsList: function () {
            const categories = {
                'navigation': { title: 'การนำทาง', shortcuts: [] },
                'actions': { title: 'การดำเนินการ', shortcuts: [] },
                'general': { title: 'ทั่วไป', shortcuts: [] }
            };

            for (const key in this.shortcuts) {
                const sc = this.shortcuts[key];
                let category = 'general';

                if (key.startsWith('g ')) {
                    category = 'navigation';
                } else if (['n', 's'].includes(key.toLowerCase()) || sc.modifier) {
                    category = 'actions';
                }

                categories[category].shortcuts.push({
                    key: this.formatKeyDisplay(key, sc.modifier),
                    description: sc.description
                });
            }

            let html = '';
            for (const cat in categories) {
                if (categories[cat].shortcuts.length === 0) continue;

                html += `<div class="shortcut-category mb-3">
                    <h6 class="text-muted mb-2">${categories[cat].title}</h6>
                    <div class="shortcut-items">`;

                for (const sc of categories[cat].shortcuts) {
                    html += `
                        <div class="shortcut-item d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span>${sc.description}</span>
                            <span class="shortcut-keys">${sc.key}</span>
                        </div>`;
                }

                html += '</div></div>';
            }

            return html;
        },

        /**
         * Format key display
         */
        formatKeyDisplay: function (key, modifier) {
            let display = '';

            if (modifier) {
                display += `<kbd>${modifier}</kbd> + `;
            }

            // Format sequence keys
            if (key.includes(' ')) {
                const keys = key.split(' ');
                display += keys.map(k => `<kbd>${k.toUpperCase()}</kbd>`).join(' ');
            } else {
                display += `<kbd>${key.length === 1 ? key.toUpperCase() : key}</kbd>`;
            }

            return display;
        },

        /**
         * Show help modal
         */
        showHelp: function () {
            if (this.helpModal && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(this.helpModal);
                modal.show();
            }
        },

        /**
         * Show shortcut hints on relevant elements
         */
        showShortcutHints: function () {
            // Add title attributes to show shortcuts on hover
            const hintMap = {
                'a[href*="/dashboard"]': 'G แล้ว H',
                'a[href*="/jobs"][href$="jobs"]': 'G แล้ว J',
                'a[href*="/jobs/kanban"]': 'G แล้ว K',
                'a[href*="/customers"]': 'G แล้ว C',
                'a[href*="/inventory"]': 'G แล้ว I',
                'a[href*="/jobs/create"]': 'Shift + N'
            };

            for (const selector in hintMap) {
                const elements = document.querySelectorAll(selector);
                elements.forEach(el => {
                    if (!el.title.includes('คีย์ลัด')) {
                        el.title = (el.title ? el.title + ' | ' : '') + 'คีย์ลัด: ' + hintMap[selector];
                    }
                });
            }
        },

        /**
         * Enable/disable shortcuts
         */
        setEnabled: function (enabled) {
            this.enabled = enabled;
        }
    };

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        KeyboardShortcuts.init();
    });

    // Expose to global scope
    window.KeyboardShortcuts = KeyboardShortcuts;

})();
