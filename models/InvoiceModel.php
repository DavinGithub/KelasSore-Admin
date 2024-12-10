<?php
// File: models/InvoiceModel.php
require_once dirname(__FILE__) . '/../services/database.php';

class InvoiceModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllInvoices()
    {
        $query = "SELECT * FROM invoices";
        $result = mysqli_query($this->conn, $query);
        $invoicesList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $invoicesList[] = $row;
        }
        return $invoicesList;
    }

    public function getInvoiceById($invoiceId)
    {
        $query = "SELECT * FROM invoices WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $invoiceId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function insertInvoice($data)
    {
        // Query untuk memasukkan data ke tabel invoices
        $query = "INSERT INTO invoices (user_id, kelas_id, status, name, payment_price, nominal, no_rekening, image_pay, bank_name, transfer_date, approval, created_at, updated_at)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // Siapkan pernyataan SQL untuk eksekusi
        $stmt = mysqli_prepare($this->conn, $query);
    
        
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssss", 
            $data['user_id'],
            $data['kelas_id'],
            $data['status'],          
            $data['name'],            
            $data['payment_price'],  
            $data['nominal'],        
            $data['no_rekening'],    
            $data['image_pay'],      
            $data['bank_name'],     
            $data['transfer_date'],  
            $data['approval'],       
            $data['created_at'],    
            $data['updated_at']      
        );
    
        // Eksekusi pernyataan SQL
        $executeResult = mysqli_stmt_execute($stmt);
    
        // Cek apakah eksekusi berhasil
        if ($executeResult) {
            return true; // Data berhasil dimasukkan
        } else {
            return false; // Gagal memasukkan data
        }
    }

    public function updateInvoice($invoiceId, $data)
    {
        // Query untuk memperbarui data di tabel invoices
        $query = "UPDATE invoices SET status = ?, name = ?, payment_price = ?, nominal = ?, no_rekening = ?, image_pay = ?, bank_name = ?, transfer_date = ?, approval = ?, updated_at = ? WHERE id = ?";
    
        // Siapkan pernyataan SQL untuk eksekusi
        $stmt = mysqli_prepare($this->conn, $query);

        $updated_at = date('Y-m-d H:i:s');
    
        // Bind parameters individually
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssi", 
            $data['status'],          
            $data['name'],            
            $data['payment_price'],  
            $data['nominal'],        
            $data['no_rekening'],    
            $data['image_pay'],      
            $data['bank_name'],     
            $data['transfer_date'],  
            $data['approval'],    
            $updated_at,   
            $invoiceId              
        );
    
        // Eksekusi pernyataan SQL
        $executeResult = mysqli_stmt_execute($stmt);
    
        // Cek apakah eksekusi berhasil
        if ($executeResult) {
            return true; // Data berhasil diperbarui
        } else {
            return false; // Gagal memperbarui data
        }
    }
    

    public function deleteInvoice($invoiceId)
    {
        $query = "DELETE FROM invoices WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $invoiceId);
        return mysqli_stmt_execute($stmt);
    }
}
?>
