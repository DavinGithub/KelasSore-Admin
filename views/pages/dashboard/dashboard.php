<?php
include dirname(__FILE__) . '/../../../controllers/InvoiceController.php';

// Initialize the InvoicesController
$invoicesController = new InvoicesController();

// Fetch all invoices and decode the JSON response
$response = json_decode($invoicesController->getAllInvoices(), true);
$payments = [];
$metrics = [
    'total_users' => 0,
    'total_orders' => 0,
    'total_sales' => 0,
    'total_pending' => 0
];

// Check if the fetch was successful
if ($response['success'] && isset($response['data'])) {
    $payments = $response['data'];
    
    // Calculate metrics from the payments data
    foreach ($payments as $payment) {
        $metrics['total_orders']++;
        $metrics['total_sales'] += floatval($payment['payment_price'] ?? 0);
        if (isset($payment['payment_status']) && $payment['payment_status'] === 'pending') {
            $metrics['total_pending']++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/dashboard/dashboard.css">
    <style>
        .empty-state {
            text-align: center;
            padding: 2rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
        .empty-state i {
            font-size: 3rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }
        .empty-state p {
            color: #4b5563;
            font-size: 1rem;
        }
    </style>
</head>
<body>
   <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="top-bar">
                <h1>Dashboard</h1>
            </div>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="title">Total Users</div>
                    <div class="value"><?php echo number_format($metrics['total_users']); ?></div>
                </div>

                <div class="metric-card">
                    <div class="title">Total Orders</div>
                    <div class="value"><?php echo number_format($metrics['total_orders']); ?></div>
                </div>

                <div class="metric-card">
                    <div class="title">Total Sales</div>
                    <div class="value">$<?php echo number_format($metrics['total_sales']); ?></div>
                </div>

                <div class="metric-card">
                    <div class="title">Total Pending</div>
                    <div class="value"><?php echo number_format($metrics['total_pending']); ?></div>
                </div>
            </div>

            <div class="deals-table">
                <div class="deals-header">
                    <h2>Payment Status</h2>
                </div>

                <?php if (empty($payments)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Tidak ada data pembayaran yang tersedia saat ini.</p>
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Course Name</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($payment['name'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($payment['course_name'] ?? ''); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($payment['payment_status'] ?? 'pending'); ?>">
                                    <?php echo ucfirst($payment['payment_status'] ?? 'Pending'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" class="action-icon" onclick="openModal('<?php echo $payment['id']; ?>')">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div> 

    <?php include '../../../views/pages/dashboard/modal.php'; ?>

    <script>
        const modal = document.getElementById('updatePaymentStatusModal');
        const paymentIdInput = document.getElementById('paymentId');

        function openModal(paymentId) {
            paymentIdInput.value = paymentId;
            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

   
        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        }

        // Submit form dengan AJAX
        const form = document.getElementById('updatePaymentStatusForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('../../../controllers/InvoiceController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment status updated successfully!');
                    closeModal();
                    location.reload();
                } else {
                    alert('Failed to update payment status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    </script>
</body>
</html>
