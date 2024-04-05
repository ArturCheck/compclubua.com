<?php
// Імпорт бібліотек та початок сесії
require_once('db.php');
session_start();

// Перевірка авторизації користувача
if (!isset($_SESSION['user_id'])) {
    // Користувач неавторизований, перенаправлення на сторінку входу
    header('Location: login.php');
    exit;
}

// Завантаження даних користувача
$user = R::load('users', $_SESSION['user_id']);
$welcome_message = "Welcome, {$user->realname}!";

// Отримання замовлень користувача з бази даних
$orders = R::find('orders', 'username = ? ORDER BY order_number ASC LIMIT 5', [$user->username]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>View Orders</title>
    <style>
    .order-block {
        border: 2px solid #ffef00;
        border-radius: 30px;
        padding: 10px;
        margin: 10px 15%;
    }
    </style>

    <script>
    function deleteOrder(orderId) {
        var confirmation = confirm("Are you sure you want to delete this order?");
        if (confirmation) {
            // Відправляємо запит на сервер для видалення
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_order.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Перезавантажуємо сторінку після видалення
                    window.location.reload();
                }
            };
            xhr.send("orderId=" + orderId);
        }
    }
    </script>

</head>
<body>
    <header>
        <div><?php echo $welcome_message ?></div>
        <nav>
            <a href="index.php">Home</a>
            <a href="create_order.php">Create Order</a>
            <a href="#">View Orders</a>
            <a href="profile.php">My Profile (<?php echo $user->username; ?>)</a>
        </nav>
    </header>

    <h2>View Orders</h2>

    <?php if (empty($orders)): ?>
    <p>No orders found.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-block">
                <p>Order № <?php echo $order->order_number; ?></p>
                <p>Order Price: <?php echo $order->total_price; ?>$</p>
                <div class="order-buttons">
                    <a href="change_order.php?id=<?php echo $order->id; ?>"><button style="width: 35%;">Change Order</button></a>
                    <button onclick="deleteOrder(<?php echo $order->id; ?>)" style="width: 35%;">Delete Order</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
