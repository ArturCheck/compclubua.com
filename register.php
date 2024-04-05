<?php
require_once('db.php');

// Змінні для зберігання введених даних
$enteredUsername = '';
$enteredEmail = '';
$enteredRealname = '';
$enteredDateBirthday = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Зчитування введених даних
    $enteredUsername = $_POST['username'];
    $enteredEmail = $_POST['email'];
    $enteredRealname = $_POST['realname'];
    $enteredDateBirthday = $_POST['date_birthday'];

    // Обробка форми реєстрації
    $username = $enteredUsername;
    $email = $enteredEmail;
    $realname = $enteredRealname;
    $date_birthday = $enteredDateBirthday;
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Перевірка на введення та інші правила для реєстрації
    if (empty($username) || empty($email) || empty($realname) || empty($date_birthday) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 5 || strlen($username) > 50) {
        $error = "Username must be between 5 and 50 characters.";
    } elseif (strlen($password) < 8 || strlen($password) > 60 || !preg_match("/[A-Z!@#$%^&*()_+{}|:<>?~]/", $password)) {
        $error = "Password must be between 8 and 60 characters and contain at least one uppercase letter or special character.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Перевірка, чи існує користувач з вказаним ім'ям користувача або електронною поштою
        $existingUserByUsername = R::findOne('users', 'username = ?', [$username]);
        $existingUserByEmail = R::findOne('users', 'email = ?', [$email]);

        if ($existingUserByUsername && $existingUserByEmail) {
            $error = "Username and email are already in use.";
        } elseif ($existingUserByUsername) {
            $error = "Username is already in use.";
        } elseif ($existingUserByEmail) {
            $error = "Email is already in use.";
        } else {
            // Перевірка віку на даті народження
            $today = new DateTime('now');
            $birthdate = new DateTime($date_birthday);
            $age = $today->diff($birthdate)->y;

            if ($age < 6 || $age > 130) {
                $error = "Invalid age. Please enter a valid date of birth.";
            } else {
                // Збереження користувача в базу даних
                $user = R::dispense('users');
                $user->username = $username;
                $user->email = $email;
                $user->realname = $realname;
                $user->date_birthday = $date_birthday;
                $user->password = password_hash($password, PASSWORD_DEFAULT);
                R::store($user);

                // Перенаправлення на головну сторінку або в інше місце
                header('Location: login.php');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
    <form method="post" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($enteredUsername); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($enteredEmail); ?>" required>

        <label for="realname">Real Name:</label>
        <input type="text" id="realname" name="realname" value="<?php echo htmlspecialchars($enteredRealname); ?>" required>

        <label for="date_birthday">Date of Birthday:</label>
        <input type="date" id="date_birthday" name="date_birthday" value="<?php echo htmlspecialchars($enteredDateBirthday); ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Register</button>
    </form>
</body>
</html>
