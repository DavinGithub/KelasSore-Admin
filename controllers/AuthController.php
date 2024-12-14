<?php 
// File: controllers/AuthController.php

include dirname(__FILE__) . '/../services/database.php';
include dirname(__FILE__) . '/../models/AuthModel.php';

class AuthController {
    protected $authModel;

    public function __construct() {
        $this->authModel = new AuthModel(); // Inisialisasi model
    }

    // Fungsi untuk login
    public function login($email, $password) {
        $admin = $this->authModel->loginUser($email, $password);
        if ($admin) {
            session_start(); // Pastikan memulai session sebelum set session
            // Setelah login berhasil, simpan data pengguna ke session
            $_SESSION['admin_id'] = $admin['id']; // ID pengguna
            $_SESSION['admin_email'] = $admin['email']; // Email pengguna
            $_SESSION['admin_name'] = $admin['name']; // Nama pengguna
            header('Location: ../views/pages/dashboard/dashboard.php'); // Sesuaikan path
            exit();
        } else {
            return "Email atau Password salah!";
        }
    }

    // Fungsi untuk registrasi
    public function register($email, $name, $password, $password_confirm) {
        $message = $this->authModel->registerUser($email, $name, $password, $password_confirm);
        return $message;
    }

    public function logout() {
        session_start(); // Pastikan session dimulai
        session_destroy(); // Menghancurkan session untuk logout
        header('Location: ../views/pages/login/login.php'); // Redirect ke halaman login
        exit();
    }

    // Fungsi untuk menangani form login
    // Fungsi untuk menangani form login
    public function handleLoginForm($email, $password) {
        return $this->login($email, $password);
    }

    // Fungsi untuk menangani form register
    public function handleRegisterForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $message = $this->register($email, $name, $password, $password_confirm);
            
            if ($message) {
                exit();
            }
        }
    }
}
?>