<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = $userModel->findUserById($_SESSION['id']); //Add new user
$id = NULL;

if (!empty($_GET['id'])) {
    $token = $_GET['token'];
    if (!$token || $token !== $_SESSION['token']) {
        // show an error message
        echo '<p class="error">Error: Invalid token</p>';
        // return 405 http status code
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }
    $id = $_GET['id'];
    if ($user[0]['id'] === $id || $user[0]['type'] === "admin") {
        $userModel->deleteUserById($id);//Delete existing user
        if ($user[0]['type'] === "admin") {
            header('location: list_users.php');
        } else {
            header('location: logout.php');
        }
        
    } else {
        header('location: list_users.php');
    }
}

?>