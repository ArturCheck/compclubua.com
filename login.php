<?php
require_once('db.php');

// Змінні для зберігання введених даних
$enteredUsernameOrEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Зчитування введених даних
    $enteredUsernameOrEmail = $_POST['usernameOrEmail'];
    $password = $_POST['password'];

    // Отримання користувача з бази даних
    $user = R::findOne('users', 'username = ? OR email = ?', [$enteredUsernameOrEmail, $enteredUsernameOrEmail]);

    if ($user) {
        if (password_verify($password, $user->password)) {
            // Успішний вхід, можна встановити сесію
            $_SESSION['user_id'] = $user->id;

            // Перенаправлення на головну сторінку або в інше місце
            header('Location: index.php');
            exit;
        } else {
            // Пароль неправильний
            $error = "Invalid password";
        }
    } else {
        // Ім'я користувача або електронна пошта не існує
        $error = "Invalid username or email";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
    <form method="post" action="login.php">
        <label for="usernameOrEmail">Username or Email:</label>
        <input type="text" id="usernameOrEmail" name="usernameOrEmail" value="<?php echo htmlspecialchars($enteredUsernameOrEmail); ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>
