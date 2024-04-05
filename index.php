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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Computer Components</title>
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

    <h2>Computer Components Information</h2>

    <div class="component-info">
        <h3>Motherboard</h3>
        <p>A motherboard is the main board that houses connectors for other components such as the processor, RAM, graphics card, and others.</p>
    </div>

    <div class="component-info">
        <h3>Case</h3>
        <p>A case is the shell that surrounds all computer components, providing protection and organization into a unified whole.</p>
    </div>

    <div class="component-info">
        <h3>Random Access Memory (RAM)</h3>
        <p>RAM is a type of memory used for temporary storage of data actively used by the computer.</p>
    </div>

    <div class="component-info">
        <h3>Permanent Storage</h3>
        <p>Permanent storage is a device for long-term data storage, such as a hard disk drive or solid-state drive (SSD).</p>
    </div>

    <div class="component-info">
        <h3>Graphics Card</h3>
        <p>A graphics card is a device that processes graphical data and is responsible for rendering graphics on the monitor screen.</p>
    </div>

    <div class="component-info">
        <h3>Power Supply Unit (PSU)</h3>
        <p>A power supply unit is a device that supplies electrical power to all components of the computer.</p>
    </div>

    <div class="component-info">
        <h3>Cooler</h3>
        <p>A cooler is a device for cooling components such as the processor or graphics card to prevent overheating.</p>
    </div>

    <div class="component-info">
        <h3>Processor</h3>
        <p>A processor is the central processing unit responsible for executing computational operations and managing other components.</p>
    </div>
</body>
</html>
