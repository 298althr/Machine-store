<?php
require_once 'vendor/autoload.php';

use TCPDF;

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Streicher GmbH');
$pdf->SetAuthor('Streicher GmbH');
$pdf->SetTitle('Payment Instructions - Partial Customs Fee');
$pdf->SetSubject('Payment Instructions');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(20, 20, 20);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add logo (streicher_logo.png)
$logo_path = 'streicher_logo.png';
if (file_exists($logo_path)) {
    $pdf->Image($logo_path, 20, 20, 60, 0, 'PNG');
}

// Add title
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Ln(50); // Space after logo
$pdf->Cell(0, 15, 'Payment Instructions – Partial Customs Fee', 0, 1, 'C');
$pdf->Ln(10);

// Payment Details Section
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Payment Details', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 8, 'Amount to Pay:', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, '$30,000.00 USD', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 8, 'Payment Method:', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Cashier\'s Check', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 8, 'Payable To:', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'MIKDAPET FORTE COMPANY LLC', 0, 1, 'L');

$pdf->Ln(10);

// Important Notice
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Important:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 8, 'Once issued, please email a scanned copy or clear photo of the cashier\'s check to store@streichergmbh.com so we can forward it to the clearing agent. The shipment will remain on hold until the remaining balance is paid.', 0, 'L');

$pdf->Ln(15);

// Receiver & Shipping Details Section
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Receiver & Shipping Details', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 8, 'Receiver Name:', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'JAMES PARKER', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 8, 'Address:', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->MultiCell(0, 8, '1415 Highway 85N Suite 310, Fayetteville, Georgia 30214', 0, 'L');

$pdf->Ln(10);

// Shipping Options
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Shipping Options After Full Clearance:', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 8, '1.', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'UPS Next Day Air – Delivery by 10:00 AM the next business day', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(10, 8, '2.', 0, 0, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'FedEx Priority Overnight – Delivery by 10:30 AM the next business day', 0, 1, 'L');

$pdf->Ln(15);

// Next Steps Section
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Next Steps', 0, 1, 'L');
$pdf->Ln(5);

$next_steps = [
    'Issue a cashier\'s check for $30,000 payable to MIKDAPET FORTE COMPANY LLC.',
    'Email a copy of the check to store@streichergmbh.com.',
    'You will be informed of the remaining balance once the clearing agent processes this payment.',
    'After the full fee is settled, customs will release the shipment, and we will dispatch it via your chosen shipping method.'
];

$pdf->SetFont('helvetica', '', 11);
foreach ($next_steps as $index => $step) {
    $pdf->Cell(10, 8, ($index + 1) . '.', 0, 0, 'L');
    $pdf->MultiCell(0, 8, $step, 0, 'L');
    $pdf->Ln(2);
}

// Add footer with contact info
$pdf->Ln(20);
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 8, 'For questions, contact us at store@streichergmbh.com', 0, 1, 'C');

// Close and output PDF
$pdf->Output('payment_instructions.pdf', 'I');
?>
