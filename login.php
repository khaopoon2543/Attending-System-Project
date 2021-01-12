<?php
    session_start();
    include('server.php'); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    
</head>
<body>

    <div class="login_image">
        <img src="chula_logo_login.jpg">
    </div>

    <div class="login_header">
        <h2>ระบบเพิ่มรายวิชา</h2>
    </div>
    
    <div class="form_login">
    <form action="login_db.php" method="post" class="form_login">
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error" style="margin-left:10px; width:87%; margin-bottom:20px;">
                <h3>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </h3>
            </div>
        <?php endif ?>
        <div class="input-group">
            <label for="username"><i class="fas fa-user-circle"></i> Username</label>
            <input type="text" name="username">
        </div>
        <div class="input-group">
            <label for="password"><i class="fas fa-lock"></i> Password</label>
            <input type="password" name="password">
        </div>
        <div class="input-group">
            <button type="submit" name="login_user" class="btn" >Login</button>
        </div>
    
    </form>
    </div>

</body>
</html>