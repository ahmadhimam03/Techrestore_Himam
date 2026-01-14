<?php
require_once 'config_portfolio.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pesan = isset($_POST['pesan']) ? trim($_POST['pesan']) : '';

if (empty($nama) || empty($email) || empty($pesan)) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi!']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email tidak valid!']);
    exit;
}

$nama = mysqli_real_escape_string($conn, $nama);
$email = mysqli_real_escape_string($conn, $email);
$pesan = mysqli_real_escape_string($conn, $pesan);

$sql = "INSERT INTO kontak (nama, email, pesan, status) VALUES (?, ?, ?, 'unread')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode([
        'success' => true, 
        'message' => "Thank you, $nama! Your message has been sent."
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to send message. Please try again.'
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
```