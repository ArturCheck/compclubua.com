<?php
require_once('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId'])) {
    $user = R::load('users', $_SESSION['user_id']);
    $orderId = filter_input(INPUT_POST, 'orderId', FILTER_VALIDATE_INT);

    if ($orderId) {
        $order = R::load('orders', $orderId);

        if ($order && $order->username == $user->username) {
            // Видаляємо замовлення з бази даних
            R::trash($order);
            echo 'success';
            exit;
        }
    }
}

echo 'error';
?>