<?php
    session_start();
    include "server/database.php";

    $register_message = "";
     if(isset($_SESSION['is_logged_in'])) {
        header('location: dashboard.php');
    }
    
    if(isset($_POST["register"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        // Validation: Check if username or password is empty
        if(empty($username) || empty($password)) {
            $register_message = "Username and password cannot be empty!";
        } else {
            $hash_password = hash("sha256", $password);

            try {
                $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hash_password')";

                if($db->query($sql)) {
                    $register_message = "daftar akun success, please login";     
                }else {
                    $register_message = "daftar akun failed, nice try-_-";
                }
            }catch (mysqli_sql_exception) {
               $register_message = "username already exists, please change!";
            }
        }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - My Anime List</title>
    <link rel="stylesheet" href="layout/dashboard.css">
    <link rel="stylesheet" href="layout/animated-text.css">
</head>
<body>
    <?php include "layout/header.html" ?>
    <h3>Account Register</h3>
    <i><?= $register_message  ?></i>

    <form action="register.php" method="POST">
        <input type="text" placeholder="username" name="username" required>
        <input type="password" placeholder="password" name="password" required>
        <button type="submit" name="register">Register now</button>
    </form>
    <?php include "layout/footer.html" ?>
</body>
</html>