<?php 
    session_start();
    include('server.php');

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM student_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $student_ID = $row['student_ID'];
        $name = $row['name'];
        $faculty = $row['faculty'];
        $major = $row['major'];
        $year = $row['year'];
        $level = $row['level'];
    }
    date_default_timezone_set("Asia/Bangkok");
    $currentDate = date("j F Y h:i A") . "<br>";

    /*!-- no username --*/
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    /*!-- logout --*/
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ขออนุมัติเพิ่มรายวิชา</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    
</head>
<body>

    <input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn" style="left: 250px;"></i>
      </label>
      <div class="left_area">
        <h3>ระบบเพิ่มรายวิชา <span>นิสิต</span></h3>
      <div class="right_area">
        <a class="right_head"><?php echo $name; ?></a>
      </div>
      </div>
    </header>
    <!--header area end-->
    
    <!--sidebar start -->
    <div class="sidebar">
        <center>
            <img src="chula_logo_index.jpg" class="profile_image" alt="">
            <h4>Chulalongkorn University</h4>
        </center>
        <a href="student_index.php"><i class="fas fa-home"></i><span>หน้าหลัก</span></a>
        <a href="opening_course.php"><i class="fas fa-table"></i><span>รายวิชาที่เปิดสอน</span></a>
        <div class="choose"><a href="attend.php"><i class="fas fa-user-plus"></i><span>ขออนุมัติเพิ่มรายวิชา</span></a></div>
        <a href="student_status.php"><i class="fas fa-history"></i><span>สถานะการขออนุมัติ</span></a>
        <a href="student_index.php?logout='1'" style="color: #e37aa1;"><i class="fas fa-power-off"></i><span>ออกจากระบบ</span></a>
        <div class="sidebar_info_user" style="margin-top:-50px;">
            <p><?php echo $currentDate; ?></p>
            <p><?php echo $name; ?></p>
            <p><?php echo $major; ?></p>
            <p><?php echo $faculty; ?></p>
        </div>

    </div>    
    <!--sidebar end-->
    
    <div class="content" >
        <h1><i class="fas fa-check-circle" style="color:#e37aa1;"></i> ขออนุมัติเพิ่มรายวิชาเสร็จสมบูรณ์</h1>

        <?php

            $course_ID = $_SESSION['course_ID'];
            $section = $_SESSION['section'];
            $request_time = $_SESSION['request_time'];
            $request_date = $_SESSION['request_date'];

            # FORMAT DATE #
            $date = date_create($request_date);
            $request_date = date_format($date,"d M Y");

            $query = "  SELECT  *
                        FROM    course
                        WHERE   course_ID = $course_ID 
                    ";
            
            $result = mysqli_query($conn, $query); 

            if (mysqli_num_rows($result) > 0) {
                
                while($rowpost = mysqli_fetch_array($result)) { 

                    # from table['course']
                    $course_name = $rowpost['course_name'];
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];  

                }
            } 
        ?>

            <br>
            <div class="finish" style="background:none">
                <p><aaa>รายวิชา</aaa> <w><?php echo $course_ID; ?> <?php echo $course_name; ?></w></p>
                <p><aaa>ตอนเรียน</aaa> <w><?php echo $section; ?></w></p>
                <p><aaa>ปีการศึกษา</aaa> <w><?php echo $academic_year; ?></w></p>
                <p><aaa>ภาคการศึกษา</aaa> <w><?php echo $semester; ?></w></p>
                <p><aaa>วันที่ขออนุมัติ</aaa> <w><i class="far fa-calendar-alt"></i> <?php echo $request_date; ?></w>
                <p><aaa>เวลาที่ขออนุมัติ</aaa> <w><i class="far fa-clock"></i> <?php echo $request_time; ?></w></p>
            </div>
            <br>
            <a href="student_status.php"><i class="fas fa-history"></i> <span>สถานะการขออนุมัติ</span></a>

    </div>

</body>
</html>