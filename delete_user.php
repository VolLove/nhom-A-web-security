<?php
require_once 'models/UserModel.php';
$userModel = new UserModel();

session_start(); // Bắt đầu phiên làm việc

if (isset($_SESSION['id'])) {
    // Lấy ID của người dùng đăng nhập
    $currentUserId = $_SESSION['id'];

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        // Kiểm tra xem người dùng đang thử xóa chính mình hay không
        if ($currentUserId == $id) {
            $userModel->deleteUserById($id); // Xóa người dùng
        }
    }
}

header('location: list_users.php');
?>
