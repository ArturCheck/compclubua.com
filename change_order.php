<?php
require_once('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user = R::load('users', $_SESSION['user_id']);
$welcome_message = "Welcome, {$user->realname}!";
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateOrderButton'])) {
    $orderId = filter_input(INPUT_POST, 'orderId', FILTER_VALIDATE_INT);

    if ($orderId) {
        $order = R::load('orders', $orderId);

        if ($order && $order->username == $user->username) {
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

            // Оновлення значень для полів таблиці orders
            $order->total_price = $totalPrice;
            $order->motherboard_quantity = $_POST['motherboardQuantity'];
            $order->case_quantity = $_POST['caseQuantity'];
            $order->ram_quantity = $_POST['ramQuantity'];
            $order->storage_quantity = $_POST['storageQuantity'];
            $order->gpu_quantity = $_POST['gpuQuantity'];
            $order->psu_quantity = $_POST['psuQuantity'];
            $order->cooler_quantity = $_POST['coolerQuantity'];
            $order->cpu_quantity = $_POST['cpuQuantity'];

            R::store($order);

            header('Location: view_orders.php');
            exit;
        } else {
            $errorMsg = 'Invalid order ID or you do not have permission to edit this order.';
        }
    } else {
        $errorMsg = 'Invalid order ID.';
    }
} else {
    $orderId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($orderId) {
        $order = R::load('orders', $orderId);

        if (!$order || $order->username != $user->username) {
            $errorMsg = 'Invalid order ID or you do not have permission to view this order.';
        }
    } else {
        $errorMsg = 'Invalid order ID.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Change Order</title>
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

    <h2>Change Order</h2>

    <div style="color: #ff6f6f; text-align: center; margin-bottom: 5px;"><?php echo $errorMsg; ?></div>

    <form id="orderForm" method="post">
        <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">

        <label for="motherboardQuantity">Motherboard (150$):</label>
        <input type="number" id="motherboardQuantity" name="motherboardQuantity" min="0" max="100" value="<?php echo $order->motherboard_quantity; ?>">

        <label for="caseQuantity">Case (50$):</label>
        <input type="number" id="caseQuantity" name="caseQuantity" min="0" max="100" value="<?php echo $order->case_quantity; ?>">

        <label for="ramQuantity">RAM (80$):</label>
        <input type="number" id="ramQuantity" name="ramQuantity" min="0" max="100" value="<?php echo $order->ram_quantity; ?>">

        <label for="storageQuantity">Storage (100$):</label>
        <input type="number" id="storageQuantity" name="storageQuantity" min="0" max="100" value="<?php echo $order->storage_quantity; ?>">

        <label for="gpuQuantity">GPU (200$):</label>
        <input type="number" id="gpuQuantity" name="gpuQuantity" min="0" max="100" value="<?php echo $order->gpu_quantity; ?>">

        <label for="psuQuantity">PSU (70$):</label>
        <input type="number" id="psuQuantity" name="psuQuantity" min="0" max="100" value="<?php echo $order->psu_quantity; ?>">

        <label for="coolerQuantity">Cooler (30$):</label>
        <input type="number" id="coolerQuantity" name="coolerQuantity" min="0" max="100" value="<?php echo $order->cooler_quantity; ?>">

        <label for="cpuQuantity">CPU (180$):</label>
        <input type="number" id="cpuQuantity" name="cpuQuantity" min="0" max="100" value="<?php echo $order->cpu_quantity; ?>">

        <button type="button" onclick="calculatePrice()">Calculate Price</button>
        <button type="submit" name="updateOrderButton">Update Order</button>
    </form>

    <p><a href="view_orders.php">Back to View Orders</a></p>

    <script src="create_order.js"></script>
</body>
</html>
