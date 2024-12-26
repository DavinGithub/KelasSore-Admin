<?php
// File: views/pages/artikel/artikel-detail.php

include_once dirname(__FILE__) . '/../../../controllers/ArtikelController.php';
include_once dirname(__FILE__) . '/../../../models/ArtikelModel.php';
include_once dirname(__FILE__) . '/../../../services/database.php';

$artikelController = new ArtikelController();

$artikelId = isset($_GET['id']) ? $_GET['id'] : null;
$artikel = null;

if ($artikelId) {
    $artikel = $artikelController->getArtikelById($artikelId);
    
    if (!$artikel) {
        echo "Artikel tidak ditemukan.";
        exit;
    }
} else {
    echo "Invalid artikel ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Artikel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Global reset and font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        /* Body styling */
        body {
            background-color: #f0f2f5;
            color: #333;
        }

        /* Sidebar styling (add this if needed) */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
            min-height: 100vh;
            padding: 20px;
        }

        /* Main container styling */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
        }

        /* Artikel detail styling */
        .artikel-detail-container {
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        /* Artikel header styling */
        .artikel-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .artikel-header h1 {
            font-size: 2em;
            color: #333;
            font-weight: bold;
        }

                /* Styling untuk title, subtitle, dan content agar teks panjang dibungkus */
        /* Styling untuk title, subtitle, dan content agar teks panjang dibungkus */
        .info-value {
            word-wrap: break-word;  /* Memaksa kata panjang terputus */
            overflow-wrap: break-word; /* Membungkus kata yang panjang */
            word-break: break-word;   /* Memecah kata panjang agar tidak keluar dari container */
            line-height: 1.6;         /* Agar teks terlihat lebih rapi */
            word-break: break-word;   /* Untuk memastikan kata panjang terputus pada batas */
        }

        .info-item {
            overflow: hidden;         /* Menghindari overflow di dalam kontainer */
        }


        /* Artikel content grid layout */
        .artikel-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        /* Information item styling */
        .info-item {
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .info-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .info-value {
            color: #333;
            line-height: 1.6;
        }

        /* Image styling */
        .artikel-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 15px;
        }

        /* Back button styling */
        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1em;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        /* Content text styling */
        .content-text {
            white-space: pre-line;
            text-align: justify;
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .artikel-detail-container {
                margin: 10px;
                padding: 20px;
            }

            .artikel-header h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <?php include '../../../views/layout/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="artikel-detail-container">
                <div class="artikel-header">
                    <h1>Detail Artikel</h1>
                </div>

                <div class="artikel-content">
                <div class="info-item">
                        <div class="info-label">Image</div>
                        <div class="info-value">
                            <?php if (!empty($artikel['image'])): ?>
                                <img src="../../../assets/images/artikels/<?php echo htmlspecialchars($artikel['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($artikel['title']); ?>"
                                     class="artikel-image">
                            <?php else: ?>
                                <p>No image available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Title</div>
                        <div class="info-value"><?php echo htmlspecialchars($artikel['title']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Subtitle</div>
                        <div class="info-value"><?php echo htmlspecialchars($artikel['subtitle']); ?></div>
                    </div>

                  
                    <div class="info-item">
                        <div class="info-label">Content</div>
                        <div class="info-value">
                            <?php echo nl2br(htmlspecialchars($artikel['content'])); ?>
                        </div>
                    </div>
                </div>

                <a href="artikel.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</body>
</html>
