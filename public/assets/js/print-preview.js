/**
 * ASIC Repair - Print Preview JavaScript
 * Provides print preview functionality with modal overlay
 */

(function () {
    'use strict';

    const PrintPreview = {
        container: null,

        /**
         * Initialize Print Preview
         */
        init: function () {
            this.createContainer();
            this.bindEvents();
        },

        /**
         * Create print preview container
         */
        createContainer: function () {
            if (document.getElementById('printPreviewContainer')) {
                this.container = document.getElementById('printPreviewContainer');
                return;
            }

            const container = document.createElement('div');
            container.id = 'printPreviewContainer';
            container.className = 'print-preview-container';
            container.innerHTML = `
                <div class="print-preview-header">
                    <div class="print-preview-title">
                        <i class="bi bi-printer"></i>
                        <span id="printPreviewTitle">ตัวอย่างก่อนพิมพ์</span>
                    </div>
                    <div class="print-preview-actions">
                        <button type="button" class="btn btn-outline-secondary" id="printPreviewClose">
                            <i class="bi bi-x-lg"></i>
                            <span class="d-none d-sm-inline">ปิด</span>
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="printPreviewDownload">
                            <i class="bi bi-download"></i>
                            <span class="d-none d-sm-inline">ดาวน์โหลด PDF</span>
                        </button>
                        <button type="button" class="btn btn-primary" id="printPreviewPrint">
                            <i class="bi bi-printer-fill"></i>
                            <span class="d-none d-sm-inline">พิมพ์</span>
                        </button>
                    </div>
                </div>
                <div class="print-preview-body">
                    <div class="print-document" id="printDocument">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            `;

            document.body.appendChild(container);
            this.container = container;
        },

        /**
         * Bind events
         */
        bindEvents: function () {
            // Close button
            document.getElementById('printPreviewClose').addEventListener('click', () => {
                this.close();
            });

            // Print button
            document.getElementById('printPreviewPrint').addEventListener('click', () => {
                this.print();
            });

            // Download button
            document.getElementById('printPreviewDownload').addEventListener('click', () => {
                this.download();
            });

            // Close on Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.container.classList.contains('show')) {
                    this.close();
                }
            });

            // Close on backdrop click
            this.container.addEventListener('click', (e) => {
                if (e.target === this.container) {
                    this.close();
                }
            });
        },

        /**
         * Show print preview with content
         * @param {Object} options - Preview options
         * @param {string} options.title - Preview title
         * @param {string} options.content - HTML content or URL
         * @param {string} options.type - 'html' or 'url'
         * @param {string} options.paperSize - 'a4', 'a5', or 'receipt'
         * @param {string} options.downloadUrl - URL for PDF download
         */
        show: function (options) {
            const defaults = {
                title: 'ตัวอย่างก่อนพิมพ์',
                content: '',
                type: 'html',
                paperSize: 'a4',
                downloadUrl: null
            };

            const settings = { ...defaults, ...options };

            // Set title
            document.getElementById('printPreviewTitle').textContent = settings.title;

            // Set paper size
            const printDoc = document.getElementById('printDocument');
            printDoc.className = 'print-document';
            if (settings.paperSize === 'a5') {
                printDoc.classList.add('a5');
            } else if (settings.paperSize === 'receipt') {
                printDoc.classList.add('receipt');
            }

            // Store download URL
            this.downloadUrl = settings.downloadUrl;

            // Toggle download button visibility
            const downloadBtn = document.getElementById('printPreviewDownload');
            downloadBtn.style.display = settings.downloadUrl ? '' : 'none';

            // Load content
            if (settings.type === 'url') {
                this.loadFromUrl(settings.content);
            } else {
                this.setContent(settings.content);
            }

            // Show container
            this.container.classList.add('show');
            document.body.style.overflow = 'hidden';
        },

        /**
         * Load content from URL
         * @param {string} url - URL to load
         */
        loadFromUrl: function (url) {
            const printDoc = document.getElementById('printDocument');
            printDoc.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3"></div>
                    <p class="text-muted">กำลังโหลด...</p>
                </div>
            `;

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load content');
                    return response.text();
                })
                .then(html => {
                    // Extract body content if full HTML page
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const bodyContent = doc.body.innerHTML;
                    this.setContent(bodyContent);
                })
                .catch(error => {
                    console.error('Print preview load error:', error);
                    printDoc.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-circle text-danger fs-1 mb-3 d-block"></i>
                            <p class="text-danger">ไม่สามารถโหลดเอกสารได้</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="PrintPreview.loadFromUrl('${url}')">
                                ลองใหม่
                            </button>
                        </div>
                    `;
                });
        },

        /**
         * Set preview content
         * @param {string} html - HTML content
         */
        setContent: function (html) {
            document.getElementById('printDocument').innerHTML = html;
        },

        /**
         * Close print preview
         */
        close: function () {
            this.container.classList.remove('show');
            document.body.style.overflow = '';
        },

        /**
         * Print the preview content
         */
        print: function () {
            window.print();
        },

        /**
         * Download as PDF
         */
        download: function () {
            if (this.downloadUrl) {
                window.open(this.downloadUrl, '_blank');
            }
        },

        /**
         * Quick preview helpers
         */
        previewJobCard: function (jobId) {
            this.show({
                title: 'ใบรับเครื่อง',
                content: `/jobs/print/${jobId}`,
                type: 'url',
                downloadUrl: `/jobs/pdf/${jobId}`
            });
        },

        previewReceipt: function (jobId) {
            this.show({
                title: 'ใบเสร็จรับเงิน',
                content: `/jobs/receipt/${jobId}`,
                type: 'url',
                paperSize: 'a4',
                downloadUrl: `/jobs/receipt-pdf/${jobId}`
            });
        },

        previewQuotation: function (quotationId) {
            this.show({
                title: 'ใบเสนอราคา',
                content: `/quotations/print/${quotationId}`,
                type: 'url',
                downloadUrl: `/quotations/pdf/${quotationId}`
            });
        },

        previewReport: function (reportType, params) {
            const queryString = new URLSearchParams(params).toString();
            this.show({
                title: 'รายงาน',
                content: `/reports/${reportType}/print?${queryString}`,
                type: 'url',
                downloadUrl: `/reports/${reportType}/pdf?${queryString}`
            });
        }
    };

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        PrintPreview.init();
    });

    // Expose to global scope
    window.PrintPreview = PrintPreview;

})();

// jQuery convenience methods (if jQuery is available)
if (typeof jQuery !== 'undefined') {
    (function ($) {
        'use strict';

        // Print preview button handler
        $(document).on('click', '[data-print-preview]', function (e) {
            e.preventDefault();

            const $btn = $(this);
            const type = $btn.data('print-type');
            const id = $btn.data('print-id');
            const url = $btn.data('print-url');
            const downloadUrl = $btn.data('print-download');
            const title = $btn.data('print-title') || 'ตัวอย่างก่อนพิมพ์';
            const paperSize = $btn.data('print-paper') || 'a4';

            if (type === 'job') {
                PrintPreview.previewJobCard(id);
            } else if (type === 'receipt') {
                PrintPreview.previewReceipt(id);
            } else if (type === 'quotation') {
                PrintPreview.previewQuotation(id);
            } else if (url) {
                PrintPreview.show({
                    title: title,
                    content: url,
                    type: 'url',
                    paperSize: paperSize,
                    downloadUrl: downloadUrl
                });
            }
        });

    })(jQuery);
}
