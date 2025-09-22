<?php
    session_start();
    include "server/database.php";

    $login_message = "";
    if(isset($_SESSION['is_logged_in'])) {
        header('location: dashboard.php');
    }

    if(isset($_POST['login'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = $_POST['password'];

    if(empty($username) || empty($password)) {
        $login_message = "Username and password cannot be empty!";
    } else {
        $hash_password = hash("sha256", $password);

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $hash_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $data = $result->fetch_assoc();
                $_SESSION['username'] = $data['username'];
                $_SESSION['is_logged_in'] = true;

                header("location: dashboard.php");
                
            } else {
                $login_message = "Invalid password";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - My Anime List</title>
    <link rel="stylesheet" href="layout/dashboard.css">
    <link rel="stylesheet" href="layout/animated-text.css">
</head>
<body>
    <?php include "layout/header.html" ?>
    <h3 class="animated-text fade-in">Please Login</h3>
    <i class="fade-in"><?= $login_message ?></i>
    <form action="login.php" method="POST">
        <input type="text" placeholder="username" name="username" required>
        <input type="password" placeholder="password" name="password" required>
        <button type="submit" name="login">Login now</button>
    </form>
    <?php include "layout/footer.html" ?>
</body>
</html>