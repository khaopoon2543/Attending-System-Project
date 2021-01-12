<?php 
    session_start();
    include('server.php');

    /*-------------------------------- GET data from student_users.php ---------------------------*/

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
    if (isset($_POST['submit'])) {
        $course_ID = mysqli_real_escape_string($conn, $_POST['course_ID']);
        $section = mysqli_real_escape_string($conn, $_POST['section']);

        $user_check_query = "   SELECT *
                                FROM student_status
                                WHERE student_ID = '$student_ID' AND course_ID = '$course_ID'
                                LIMIT 1
                            ";
        $query = mysqli_query($conn, $user_check_query);
        $result = mysqli_fetch_assoc($query);

        if ($result) { // if course_ID exists
            if ($result['course_ID'] === $course_ID) {
                array_push($errors, "ท่านได้ขออนุมัติเพิ่มรายวิชานี้แล้ว");
                $_SESSION['error'] = "ท่านได้ขออนุมัติเพิ่มรายวิชานี้แล้ว";
                header("location: attend.php");
            }
        }

        if (count($errors) == 0) { // ผ่านแล้ว ^-^ ไปหน้า attend_confirm.php
            $_SESSION['course_ID'] = $course_ID;
            $_SESSION['section'] = $section;
            header("location: attend_confirm.php");
        }
        
        if (empty($course_ID)) {
            array_push($errors, "กรุณากรอก 'รหัสรายวิชา'");
            $_SESSION['error'] = "กรุณากรอก 'รหัสรายวิชา'";
            header("location: attend.php");
        }
        if (empty($section)) {
            array_push($errors, "กรุณากรอก 'ตอนเรียน'");
            $_SESSION['error'] = "กรุณากรอก 'ตอนเรียน'";
            header("location: attend.php");
        }
        if (empty($course_ID) && empty($section)){
            array_push($errors, "กรุณากรอก 'รหัสรายวิชา' และ 'ตอนเรียน'");
            $_SESSION['error'] = "กรุณากรอก 'รหัสรายวิชา' และ 'ตอนเรียน'";
            header("location: attend.php");
        }
    }

?>