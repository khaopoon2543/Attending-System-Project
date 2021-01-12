<?php 
    session_start();
    include('server.php');

    $password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    /*-------------------------------- GET data from student_users.php ---------------------------*/

    date_default_timezone_set("Asia/Bangkok");
        $request_date = date("Y-m-d") ;
        $request_time = date("h:i:s A") ;

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM student_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $student_ID = $row['student_ID'];
        $username = $row['username'];
    }


    
    /*---------------------------------- INSERT to database ----------------------------------*/

    $errors = array();
    if (isset($_POST['attend_submit'])) {
        $course_ID = $_SESSION['course_ID'];
        $section = $_SESSION['section'];

        if (empty($password)) {
            array_push($errors, "กรุณากรอก 'รหัสผ่าน'");
            $_SESSION['error'] = "กรุณากรอก 'รหัสผ่าน'";

            # link กลับไปหน้าก่อน attend_confirm.php !!! \(;-;)/ #
            $_SESSION['course_ID'] = $course_ID;
            $_SESSION['section'] = $section;
            header("location: attend_confirm.php");
            
        }

        if (count($errors) == 0) {

            $query_users = "SELECT * FROM student_users WHERE username = '$username' AND password = '$password' ";
            $result_users = mysqli_query($conn, $query_users);

            /*--------- username & password ถูกก!!! ----------*/
            if (mysqli_num_rows($result_users) > 0) {

                $sql = "INSERT INTO student_request (student_ID, course_ID, section, request_time, request_date) VALUES ('$student_ID', '$course_ID', '$section', '$request_time', '$request_date')";
                mysqli_query($conn, $sql);

                $sql = "INSERT INTO student_status (student_ID, course_ID, section, request_time, request_date, status) VALUES ('$student_ID', '$course_ID', '$section', '$request_time', '$request_date', 'รออนุมัติ')";
                mysqli_query($conn, $sql);

                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;
                $_SESSION['request_time'] = $request_time;
                $_SESSION['request_date'] = $request_date;

                header('location: finish_attend.php');

            } else { /*--------- username & password ผิดด!!! ----------*/

                array_push($errors, "รหัสผ่าน 'ผิด' กรุณากรอกใหม่อีกครั้ง!");
                $_SESSION['error'] = "รหัสผ่าน 'ผิด' กรุณากรอกใหม่อีกครั้ง!";

                 # link กลับไปหน้าก่อน attend_confirm.php !!! \(;-;)/ #
                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;
                header("location: attend_confirm.php");

            }
        } 
        
    }

?>