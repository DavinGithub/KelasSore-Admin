<?php
// File: views/pages/pembayaran/invoice-detail.php

// Include necessary files
include_once dirname(__FILE__) . '/../../../controllers/InvoiceController.php';
include_once dirname(__FILE__) . '/../../../models/InvoiceModel.php';
include_once dirname(__FILE__) . '/../../../services/database.php';

// Initialize the controller
$invoiceController = new InvoicesController();

// Get the invoice ID from the URL
$invoiceId = isset($_GET['id']) ? $_GET['id'] : null;
$invoice = null;

if ($invoiceId) {
    // Get the invoice details by ID
    $response = $invoiceController->getdetailinvoicesbyid($invoiceId);

    // Decode the JSON response into an associative array
    $invoiceData = json_decode($response, true);

    // Check if the response contains data
    if (isset($invoiceData['success']) && $invoiceData['success'] === true) {
        $invoice = $invoiceData['data'];
    } else {
        // Handle error if the response doesn't have 'success' or 'data'
        echo "Error: Invoice data not found.";
        exit;
    }

    // Check if the invoice is null or empty
    if (!$invoice) {
        echo "Invoice not found.";
        exit;
    }
} else {
    echo "Invalid invoice ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Invoice</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentor.css">
    <style>
        .invoice-detail-container {
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px;
        }

        .invoice-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .invoice-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 5px;
        }

        .info-value {
            color: #333;
            font-size: 1.1em;
        }

      
    </style>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            
            <div class="invoice-detail-container">
                <div class="invoice-header">
                    <h1>Detail Invoice</h1>
                </div>

                <div class="invoice-info-grid">
                    <!-- Display invoice details -->
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value"><?php echo htmlspecialchars($invoice['status']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($invoice['name']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Payment Price</div>
                        <div class="info-value">Rp <?php echo number_format($invoice['payment_price'], 0, ',', '.'); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Nominal</div>
                        <div class="info-value">Rp <?php echo number_format($invoice['nominal'], 0, ',', '.'); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">No Rekening</div>
                        <div class="info-value"><?php echo htmlspecialchars($invoice['no_rekening']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Image Pay</div>
                        <div class="info-value">
                            <?php if (!empty($invoice['image_pay'])): ?>
                                <img src="<?php echo htmlspecialchars($invoice['image_pay']); ?>" alt="Payment Image" style="max-width: 300px; max-height: 200px;">
                            <?php else: ?>
                                No image available.
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Bank Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($invoice['bank_name']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
