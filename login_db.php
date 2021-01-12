<?php 
    session_start();
    include('server.php');

    $errors = array();
    if (isset($_POST['login_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if (count($errors) == 0) {
            #$password = md5($password); รหัสแบบการป้องกันแบบ300%!!!!
            $query = "SELECT * FROM teacher_users WHERE username = '$username' AND password = '$password' ";
            $result = mysqli_query($conn, $query);

            /*--------- ถ้า username & password ตรงกัน ----------*/
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['username'] = $username;
                header("location: index.php");

            } else { /*--------- ถ้าไม่ใช่ teacher email ให้หา student email แทนจ้า ----------*/
                $query = "SELECT * FROM student_users WHERE username = '$username' AND password = '$password' ";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    $_SESSION['username'] = $username;
                    header("location: student_index.php");

                } else { /*--------- ถ้าไม่ใช่ student email ให้หา officer email แทนจ้า ----------*/
                    $query = "SELECT * FROM officer_user WHERE username = '$username' AND password = '$password' ";
                    $result = mysqli_query($conn, $query);
    
                    if (mysqli_num_rows($result) > 0) {
                        $_SESSION['username'] = $username;
                        header("location: officer_index.php");
                    } else {
                        array_push($errors, "Wrong Username or Password");
                        $_SESSION['error'] = "Wrong Username or Password!";
                        header("location: login.php");
                    }
                }
            }

        } 
        
        if (empty($username)) {
            array_push($errors, "Username is required");
            $_SESSION['error'] = "Username is required";
            header("location: login.php");
        }

        if (empty($password)) {
            array_push($errors, "Password is required");
            $_SESSION['error'] = "Password is required";
            header("location: login.php");
        }

        if (empty($username)&&empty($password)) { /*--------- ไม่ได้ใส่ username OR password ----------*/
            array_push($errors, "Username & Password is required");
            $_SESSION['error'] = "Username & Password is required";
            header("location: login.php");
        }
        
    }

?>

