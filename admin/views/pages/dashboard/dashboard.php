<?php
// Include necessary controllers
include dirname(__FILE__) . '/../../../controllers/InvoiceController.php';
include dirname(__FILE__) . '/../../../controllers/UserController.php';
include dirname(__FILE__) . '/../../../controllers/MentorController.php';
include dirname(__FILE__) . '/../../../controllers/KelasController.php';  // Include KelasController
include dirname(__FILE__) . '/../../../controllers/BookController.php';  // Include BookController

$invoicesController = new InvoicesController();
$userController = new UserController();
$mentorController = new MentorController();
$kelasController = new KelasController();  // Instantiate KelasController
$bookController = new BookController();  // Instantiate BookController

// Fetch the total classes
$totalKelasResponse = $kelasController->gettotalkelas();

// Get the total users and mentors
$totalUsersResponse = $userController->getTotalUser();
$totalMentorsResponse = $mentorController->getTotalMentor();

// Get total books
$totalBooksResponse = $bookController->getTotalBooks();

// Get all invoices data
$invoicesResponse = json_decode($invoicesController->getAllInvoices(), true);

// Initialize metrics
$metrics = [
    'total_users' => $totalUsersResponse['success'] ? $totalUsersResponse['data'] : 0,
    'total_mentors' => $totalMentorsResponse['success'] ? $totalMentorsResponse['data'] : 0,
    'total_orders' => 0,
    'total_sales' => 0,
    'total_pending' => 0,
    'total_kelas' => $totalKelasResponse['success'] ? $totalKelasResponse['data'] : 0,  // Added total classes
    'total_books' => $totalBooksResponse['success'] ? $totalBooksResponse['data'] : 0, // Added total books
];

// Process invoices to calculate metrics
if ($invoicesResponse['success'] && isset($invoicesResponse['data'])) {
    $payments = $invoicesResponse['data'];

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
            background-color: rgba(0, 0, 0, 0.4);
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

        .metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); 
        gap: 1.5rem;
        margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
        }


        .metric-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .metric-card .title {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .metric-card .value {
            font-size: 1.875rem;
            font-weight: 600;
            color: #111827;
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
                    <div class="title">Total Orders</div>
                    <div class="value"><?php echo number_format($metrics['total_orders']); ?></div>
                </div>
                <div class="metric-card">
                    <div class="title">Total Mentor</div>
                    <div class="value"><?php echo number_format($metrics['total_mentors']); ?></div>
                </div>
                <div class="metric-card">
                    <div class="title">Total User</div>
                    <div class="value"><?php echo number_format($metrics['total_users']); ?></div>
                </div>
                <div class="metric-card">
                    <div class="title">Total Kelas</div>
                    <div class="value"><?php echo number_format($metrics['total_kelas']); ?></div>  <!-- Total Kelas -->
                </div>
                <div class="metric-card">
                    <div class="title">Total Books</div>
                    <div class="value"><?php echo number_format($metrics['total_books']); ?></div>  <!-- Total Books -->
                </div>
            </div>
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

            fetch('', { // Empty string means submit to the same page
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
