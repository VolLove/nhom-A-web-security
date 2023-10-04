<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = $userModel->findUserById($_SESSION['id']); //Add new user
$id = NULL;

if (!empty($_GET['id'])) {
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