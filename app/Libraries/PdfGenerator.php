<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * PDF Generator Library
 * 
 * Generates PDF documents for job cards, receipts, and quotations.
 */
class PdfGenerator
{
    protected Dompdf $dompdf;
    
    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('chroot', WRITEPATH);
        
        $this->dompdf = new Dompdf($options);
    }
    
    /**
     * Generate Job Card PDF
     */
    public function generateJobCard(array $job, array $customer, array $asset, array $parts = []): string
    {
        $html = view('pdf/job_card', [
            'job'      => $job,
            'customer' => $customer,
            'asset'    => $asset,
            'parts'    => $parts,
        ]);
        
        return $this->render($html, 'A4', 'portrait');
    }
    
    /**
     * Generate Receipt PDF
     */
    public function generateReceipt(array $job, array $customer, array $payments = []): string
    {
        $html = view('pdf/receipt', [
            'job'      => $job,
            'customer' => $customer,
            'payments' => $payments,
        ]);
        
        return $this->render($html, 'A4', 'portrait');
    }
    
    /**
     * Generate Quotation PDF
     */
    public function generateQuotation(array $quotation, array $customer, array $items = []): string
    {
        $html = view('pdf/quotation', [
            'quotation' => $quotation,
            'customer'  => $customer,
            'items'     => $items,
        ]);
        
        return $this->render($html, 'A4', 'portrait');
    }
    
    /**
     * Render HTML to PDF
     */
    protected function render(string $html, string $paper = 'A4', string $orientation = 'portrait'): string
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        
        return $this->dompdf->output();
    }
    
    /**
     * Stream PDF to browser
     */
    public function stream(string $html, string $filename = 'document.pdf', string $paper = 'A4', string $orientation = 'portrait'): void
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        
        $this->dompdf->stream($filename, ['Attachment' => false]);
    }
    
    /**
     * Download PDF
     */
    public function download(string $html, string $filename = 'document.pdf', string $paper = 'A4', string $orientation = 'portrait'): void
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper($paper, $orientation);
        $this->dompdf->render();
        
        $this->dompdf->stream($filename, ['Attachment' => true]);
    }
}

