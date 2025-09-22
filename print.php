<?php
session_start();

include __DIR__ . "/system/action.php";
useQuery("order.php");
useQuery("product.php");
require __DIR__ . "/system/libs/fpdf.php";

// Ambil order_id
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    $orderId = findLastOrderId();
    if (!$orderId) {
        exit("No transaction found.");
    }
}

$order = findOrderById($orderId);
if (!$order) {
    exit("Order not found!");
}

$items = findOrderItems($orderId);

$subtotal = $order['total_payment'] / 1.11;
$pajak    = $order['total_payment'] - $subtotal;

$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

$pdf->Cell(0,10,'Restro POS - Struk Transaksi',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',11);
$pdf->Cell(100,6,'Order ID: '.$order['id'],0,1);
$pdf->Cell(100,6,'Admin   : '.($order['admin_name'] ?? '-'),0,1);
$pdf->Cell(100,6,'Customer: '.($order['customer_name'] ?? '-'),0,1);
$pdf->Cell(100,6,'Tanggal : '.$order['created_at'],0,1);

$pdf->Ln(5);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(70,8,'Produk',1,0,'C');
$pdf->Cell(20,8,'Quantity',1,0,'C');
$pdf->Cell(40,8,'Harga',1,0,'C');
$pdf->Cell(40,8,'Total',1,1,'C');

$pdf->SetFont('Arial','',11);
foreach ($items as $item) {
    $pdf->Cell(70,8,$item['name_product'],1);
    $pdf->Cell(20,8,$item['quantity'],1,0,'C');
    $pdf->Cell(40,8,'Rp '.number_format($item['price'],0,',','.'),1,0,'R');
    $pdf->Cell(40,8,'Rp '.number_format($item['total_price'],0,',','.'),1,1,'R');
}

$pdf->Ln(4);
$pdf->SetFont('Arial','',11);
$pdf->Cell(130,6,'Subtotal',0,0,'R');
$pdf->Cell(40,6,'Rp '.number_format($subtotal,0,',','.'),0,1,'R');
$pdf->Cell(130,6,'Pajak (11%)',0,0,'R');
$pdf->Cell(40,6,'Rp '.number_format($pajak,0,',','.'),0,1,'R');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,8,'Total',0,0,'R');
$pdf->Cell(40,8,'Rp '.number_format($order['total_payment'],0,',','.'),0,1,'R');

// Output PDF
$pdf->Output('I','Struk_'.$order['id'].'.pdf');

var_dump($pdf);