<?php

declare(strict_types=1);

namespace Streicher\App\Services;

class PdfService
{
    private FPDF $pdf;

    public function __construct()
    {
        $this->pdf = new FPDF();
    }

    /**
     * Generate an invoice PDF.
     */
    public function generateInvoice(array $order, array $items): string
    {
        $pdf = $this->pdf;
        $pdf->AddPage();
        
        // Logo
        $logoPath = dirname(__DIR__, 2) . '/web/assets/logo.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 50);
        }
        
        // Header Info
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'INVOICE / PROFORMA', 0, 1, 'R');
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Order #: ' . $order['order_number'], 0, 1, 'R');
        $pdf->Cell(0, 5, 'Date: ' . date('Y-m-d', strtotime($order['created_at'])), 0, 1, 'R');
        $pdf->Ln(20);
        
        // Company Details (Left) vs Customer Details (Right)
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(95, 7, 'From:', 0, 0);
        $pdf->Cell(95, 7, 'Bill To:', 0, 1);
        
        $pdf->SetFont('Arial', '', 10);
        $currY = $pdf->GetY();
        
        // Streicher Info
        $pdf->SetXY(10, $currY);
        $pdf->MultiCell(90, 5, "Streicher GmbH\nIndustriestraße 45\n93055 Regensburg\nGermany\nVAT: DE123456789", 0, 'L');
        
        // Customer Info
        $pdf->SetXY(110, $currY);
        $billing = json_decode($order['billing_address'] ?? '[]', true);
        $customerInfo = ($order['billing_name'] ?? 'Customer') . "\n";
        if (!empty($order['billing_company'])) $customerInfo .= $order['billing_company'] . "\n";
        $customerInfo .= ($order['billing_address'] ?? '') . "\n";
        $customerInfo .= ($order['billing_city'] ?? '') . ", " . ($order['billing_country'] ?? '');
        
        $pdf->MultiCell(90, 5, $customerInfo, 0, 'L');
        
        $pdf->Ln(15);
        
        // Table Header
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(100, 10, 'Description', 1, 0, 'L', true);
        $pdf->Cell(30, 10, 'SKU', 1, 0, 'C', true);
        $pdf->Cell(20, 10, 'Qty', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Total', 1, 1, 'R', true);
        
        // Table Body
        $pdf->SetFont('Arial', '', 10);
        foreach ($items as $item) {
            $pdf->Cell(100, 8, $item['name'] ?? $item['product_name'], 1);
            $pdf->Cell(30, 8, $item['sku'], 1, 0, 'C');
            $pdf->Cell(20, 8, $item['quantity'] ?? $item['qty'], 1, 0, 'C');
            $pdf->Cell(40, 8, number_format((float)$item['total_price'], 2) . ' ' . $order['currency'], 1, 1, 'R');
        }
        
        // Totals
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(150, 10, 'GRAND TOTAL:', 0, 0, 'R');
        $pdf->Cell(40, 10, number_format((float)$order['total_amount'], 2) . ' ' . $order['currency'], 1, 1, 'R');
        
        // Bank Details
        $pdf->Ln(20);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 10, 'Payment Instructions:', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 5, "Bank: Sparkasse Regensburg\nIBAN: DE89 7505 0000 1234 5678 90\nBIC: SOLADE M1 REG\nAccount Holder: Streicher GmbH\n\nPlease include order number " . $order['order_number'] . " in your transfer reference.", 1, 'L');
        
        return $pdf->Output('S', 'invoice-' . $order['order_number'] . '.pdf');
    }
}
