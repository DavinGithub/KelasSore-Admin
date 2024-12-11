<?php
$metrics = [
    'total_users' => 40689,
    'total_orders' => 10293,
    'total_sales' => 89000,
    'total_pending' => 2040,
];

$deals = [
    [
        'product_name' => 'Apple Watch',
        'location' => '6096 Marylaine Landing',
        'date_time' => '12.09.2019 - 12:53 PM',
        'place' => '423',
        'amount' => 34295,
        'status' => 'delivered'
    ],
    [
        'product_name' => 'Apple Watch',
        'location' => '6096 Marylaine Landing',
        'date_time' => '12.09.2019 - 12:53 PM',
        'place' => '423',
        'amount' => 34295,
        'status' => 'pending'
    ],
    [
        'product_name' => 'Apple Watch',
        'location' => '6096 Marylaine Landing',
        'date_time' => '12.09.2019 - 12:53 PM',
        'place' => '423',
        'amount' => 34295,
        'status' => 'rejected'
    ],
];  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/mentor/mentor.css">
    
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
        <div class="top-bar">
    <h1>Mentor</h1>
    <button class="add-mentor-btn">
        <i class="fas fa-plus"></i> Add Mentor
    </button>
</div>
            <div class="deals-table">
                <div class="deals-header">
                    <h2>Deals Details</h2>
                    <select>
                        <option>October</option>
                        <option>November</option>
                        <option>December</option>
                    </select>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Location</th>
                            <th>Date - Time</th>
                            <th>Place</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach($deals as $deal): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="product-image"></div>
                                    <span><?php      echo htmlspecialchars($deal['product_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($deal['location']); ?></td>
                            <td><?php echo htmlspecialchars($deal['date_time']); ?></td>
                            <td><?php echo htmlspecialchars($deal['place']); ?></td>
                            <td>$<?php echo number_format($deal['amount'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $deal['status']; ?>">
                                    <?php echo ucfirst($deal['status']); ?>
                                </span>
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