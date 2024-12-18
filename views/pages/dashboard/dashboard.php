<?php
//File: views/pages/dashboard/dashboard.php
include dirname(__FILE__) . '/../../../controllers/InvoiceController.php';

// Initialize the InvoicesController
$invoicesController = new InvoicesController();

// Handle POST request for updating invoice status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_invoice_status') {
    $invoiceId = $_POST['invoice_id'] ?? null;
    $newStatus = $_POST['status'] ?? null;
    $approval = $_POST['approval'] ?? null;

    if ($invoiceId && $newStatus && $approval) {
        $data = [
            'status' => $newStatus,
            'approval' => $approval,
        ];
        
        $response = json_decode($invoicesController->updateInvoice($invoiceId, $data), true);
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
}

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
        if (isset($payment['status']) && $payment['status'] === 'menunggu pembayaran') {
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close-btn:hover {
            color: #000;
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
                    <h2>Status Pembayaran</h2>
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
                            <th>Nama User</th>
                            <th>Name Kelas</th>
                            <th>Status Pembayaran</th>
                            <th>Status Perizinan</th>
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
                                <span class="status-badge status-<?php echo strtolower($payment['status'] ?? 'menunggu pembayaran'); ?>">
                                    <?php echo ucfirst($payment['status'] ?? 'menunggu pembayaran'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" class="action-icon" onclick="openModal(
                                    '<?php echo $payment['id']; ?>',
                                    '<?php echo $payment['status'] ?? ''; ?>',
                                    '<?php echo $payment['approval'] ?? ''; ?>'
                                )">
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

    <!-- Modal for Updating Invoice Status -->
    <div id="updateInvoiceStatusModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Update Invoice Status</h2>
            <form id="updateInvoiceStatusForm">
                <input type="hidden" name="action" value="update_invoice_status">
                <input type="hidden" id="invoiceId" name="invoice_id">
                
                <div>
                    <label for="paymentStatus">Payment Status:</label>
                    <select id="paymentStatus" name="status" required>
                        <option value="menunggu pembayaran">Menunggu Pembayaran</option>
                        <option value="terbayar">Terbayar</option>
                        <option value="gagal">Gagal</option>
                    </select>
                </div>

                <div>
                    <label for="approvalStatus">Approval Status:</label>
                    <select id="approvalStatus" name="approval" required>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <button type="submit">Update Status</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('updateInvoiceStatusModal');
        const invoiceIdInput = document.getElementById('invoiceId');
        const paymentStatusSelect = document.getElementById('paymentStatus');
        const approvalStatusSelect = document.getElementById('approvalStatus');

        function openModal(invoiceId, currentPaymentStatus, currentApproval) {
            invoiceIdInput.value = invoiceId;
            paymentStatusSelect.value = currentPaymentStatus || 'menunggu pembayaran';
            approvalStatusSelect.value = currentApproval || '';
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

        // Submit form with AJAX
        const form = document.getElementById('updateInvoiceStatusForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('', {  // Empty string means submit to the same page
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Invoice status updated successfully!');
                    closeModal();
                    location.reload();
                } else {
                    alert('Failed to update invoice status: ' + data.message);
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