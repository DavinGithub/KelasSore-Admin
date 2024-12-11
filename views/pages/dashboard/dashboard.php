<?php
$metrics = [
    'total_users' => 3412,
    'total_orders' => 4214,
    'total_sales' => 4213,
    'total_pending' => 1043,
];

$payments = [
    [
        'name' => 'John Doe',
        'course_name' => 'Web Development',
        'payment_status' => 'accepted'
    ],
    [
        'name' => 'Jane Smith',
        'course_name' => 'Graphic Design',
        'payment_status' => 'pending'
    ],
    [
        'name' => 'Mike Johnson',
        'course_name' => 'Data Science',
        'payment_status' => 'accepted'
    ],
    [
        'name' => 'Emma White',
        'course_name' => 'Cyber Security',
        'payment_status' => 'pending'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/dashboard.css">
    
</head>
<body>
    <?php include 'views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="top-bar">
                <h1>Dashboard</h1>
            </div>
            
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="title">Total Users</div>
                    <div class="value"><?php echo number_format($metrics['total_users']); ?></div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> 8.5% from yesterday
                    </div>
                </div>

                <div class="metric-card">
                    <div class="title">Total Orders</div>
                    <div class="value"><?php echo number_format($metrics['total_orders']); ?></div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> 1.3% from past week
                    </div>
                </div>

                <div class="metric-card">
                    <div class="title">Total Sales</div>
                    <div class="value">$<?php echo number_format($metrics['total_sales']); ?></div>
                    <div class="trend down">
                        <i class="fas fa-arrow-down"></i> 4.3% from yesterday
                    </div>
                </div>

                <div class="metric-card">
                    <div class="title">Total Pending</div>
                    <div class="value"><?php echo number_format($metrics['total_pending']); ?></div>
                    <div class="trend up">
                        <i class="fas fa-arrow-up"></i> 1.8% from yesterday
                    </div>
                </div>
            </div>

            <div class="deals-table">
                <div class="deals-header">
                    <h2>Payment Status</h2>
                </div>

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
                            <td><?php echo htmlspecialchars($payment['name']); ?></td>
                            <td><?php echo htmlspecialchars($payment['course_name']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $payment['payment_status']; ?>">
                                    <?php echo ucfirst($payment['payment_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" class="action-icon"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>