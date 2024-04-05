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

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createOrderButton'])) {
    
    $nextOrderNumber = null;

    for ($i = 1; $i <= 5; $i++) {
        $orderExists = R::count('orders', 'username = ? AND order_number = ?', [$user->username, $i]);

        if (!$orderExists) {
            $nextOrderNumber = $i;
            break;
        }
    }

    // Перевірка, чи користувач має менше або рівно 5 попередніх замовлень
    if ($nextOrderNumber !== null) {
        // Розрахунок total_price
        $totalPrice = 0;
        $componentPrices = [
            'motherboard' => 150,
            'case' => 50,
            'ram' => 80,
            'storage' => 100,
            'gpu' => 200,
            'psu' => 70,
            'cooler' => 30,
            'cpu' => 180
        ];

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'Quantity') !== false) {
                $component = str_replace('Quantity', '', $key);
                $quantity = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT) ?? 0;
                $totalPrice += $quantity * $componentPrices[$component];
            }
        }

        $order = R::dispense('orders');

        // Встановлення значень для полів таблиці orders
        $order->username = $user->username;
        $order->order_number = $nextOrderNumber;
        $order->total_price = $totalPrice;
        $order->motherboard_quantity = $_POST['motherboardQuantity'];
        $order->case_quantity = $_POST['caseQuantity'];
        $order->ram_quantity = $_POST['ramQuantity'];
        $order->storage_quantity = $_POST['storageQuantity'];
        $order->gpu_quantity = $_POST['gpuQuantity'];
        $order->psu_quantity = $_POST['psuQuantity'];
        $order->cooler_quantity = $_POST['coolerQuantity'];
        $order->cpu_quantity = $_POST['cpuQuantity'];

        // Збереження об'єкта в базі даних
        R::store($order);

        header('Location: view_orders.php');
        exit;
    } else {
        $errorMsg = 'You have reached the maximum limit of 5 orders!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Create Order</title>
</head>
<body>
    <header>
        <div><?php echo $welcome_message; ?></div>
        <nav>
            <a href="index.php">Home</a>
            <a href="create_order.php">Create Order</a>
            <a href="view_orders.php">View Orders</a>
            <a href="profile.php">My Profile (<?php echo $user->username; ?>)</a>
        </nav>
    </header>

    <h2>Create Your Computer</h2>

    <div style="color: #ff6f6f; text-align: center; margin-bottom: 5px;"><?php echo $errorMsg; ?></div>

    <form id="orderForm" method="post">
        <label for="motherboardQuantity">Motherboard (150$):</label>
        <input type="number" id="motherboardQuantity" name="motherboardQuantity" min="0" max="100">

        <label for="caseQuantity">Case (50$):</label>
        <input type="number" id="caseQuantity" name="caseQuantity" min="0" max="100">

        <label for="ramQuantity">RAM (80$):</label>
        <input type="number" id="ramQuantity" name="ramQuantity" min="0" max="100">

        <label for="storageQuantity">Storage (100$):</label>
        <input type="number" id="storageQuantity" name="storageQuantity" min="0" max="100">

        <label for="gpuQuantity">GPU (200$):</label>
        <input type="number" id="gpuQuantity" name="gpuQuantity" min="0" max="100">

        <label for="psuQuantity">PSU (70$):</label>
        <input type="number" id="psuQuantity" name="psuQuantity" min="0" max="100">

        <label for="coolerQuantity">Cooler (30$):</label>
        <input type="number" id="coolerQuantity" name="coolerQuantity" min="0" max="100">

        <label for="cpuQuantity">CPU (180$):</label>
        <input type="number" id="cpuQuantity" name="cpuQuantity" min="0" max="100">

        <button type="button" onclick="calculatePrice()">Calculate Price</button>
        <button type="submit" name="createOrderButton" onclick="createOrder()">Create Order</button>
    </form>

    <p><a href="index.php">Back to Home</a></p>

    <script src="create_order.js"></script>
</body>
</html>
