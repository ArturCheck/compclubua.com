<?php
require_once('db.php');
session_start();

// Перевірка, чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    // Користувач неавторизований, перенаправлення на сторінку входу
    header('Location: login.php');
    exit;
}

// Отримання даних користувача з бази даних
$user_id = $_SESSION['user_id'];
$user = R::load('users', $user_id);

// Обробка зміни паролю
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Перевірка, чи введено старий пароль правильно
    if (password_verify($old_password, $user->password)) {
        // Збереження нового паролю в базу даних
        $user->password = password_hash($new_password, PASSWORD_DEFAULT);
        R::store($user);
        $success_message = "Password successfully changed.";
    } else {
        $error = "Invalid old password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profile</title>
</head>
<body>
    <header>
        <div>Welcome, <?php echo $user->realname; ?>!</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="create_order.php">Create Order</a>
            <a href="view_orders.php">View Orders</a>
            <a href="#">My Profile (<?php echo $user->username; ?>)</a>
        </nav>
    </header>

    <h2>My Profile</h2>

    <?php if (isset($error)): ?>
        <p class="error-message" style="color: #ff6060;"><?php echo $error; ?></p>
    <?php elseif (isset($success_message)): ?>
        <p class="success-message" style="color: #17ff17;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <p><span style="color: #a4a4ff;">Username:</span> <span style="color: #00ff00;"><?php echo $user->username; ?></span></p>
    <p><span style="color: #a4a4ff;">Email:</span> <span style="color: #00ff00;"><?php echo $user->email; ?></span></p>
    <p><span style="color: #a4a4ff;">Date of Birthday:</span> <span style="color: #00ff00;"><?php echo $user->date_birthday; ?></span></p>
    <p><span style="color: #a4a4ff;">Real Name:</span> <span style="color: #00ff00;"><?php echo $user->realname; ?></span></p>

    <form method="post" action="profile.php">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>

        <button type="submit">Change Password</button>
    </form>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
