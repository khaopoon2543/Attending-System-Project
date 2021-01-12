<?php 
    session_start();
    include('server.php'); 

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM teacher_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $faculty = $row['faculty'];
        $department = $row['department'];
    }
    date_default_timezone_set("Asia/Bangkok");
    $currentDate = date("j F Y h:i A") . "<br>";
    

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

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
    <title>อนุมัติเพิ่มรายวิชา</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    
</head>
<body>

    <input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn"></i>
      </label>
      <div class="left_area">
        <h3>ระบบเพิ่มรายวิชา <span> อาจารย์</span></h3>
      </div>
      <div class="right_area">
        <a class="right_head"><?php echo $name; ?></a>
      </div>
    </header>
    <!--header area end-->
    
    <!--sidebar start -->
    <div class="sidebar">
        <center>
            <img src="chula_logo_index.jpg" class="profile_image" alt="">
            <h4>Chulalongkorn University</h4>
        </center>
        <a href="index.php"><i class="fas fa-home"></i><span>หน้าหลัก</span></a>
        <div class="choose"><a href="course.php"><i class="fas fa-table"></i><span style="regu">อนุมัติเพิ่มรายวิชา</span></a></div>
        <a href="history.php"><i class="fas fa-history"></i><span>ประวัติการอนุมัติ</span></a>
        <a href="index.php?logout='1'" style="color: #e37aa1;"><i class="fas fa-power-off"></i><span>ออกจากระบบ</span></a>
        
        <div class="sidebar_info_user">
            <p><?php echo $currentDate; ?></p>
            <p><?php echo $name; ?></p>
            <p><?php echo $department; ?></p>
            <p><?php echo $faculty; ?></p>
        </div>

    </div>    
    <!--sidebar end-->
    
    <div class="content">
        <h1><i class="fas fa-check-circle" style="color:#e37aa1;"></i> อนุมัติการเพิ่มรายวิชาเสร็จสมบูรณ์</h1>

        <?php

            $course_ID = $_SESSION['course_ID'];
            $section = $_SESSION['section'];
            $approven_time = $_SESSION['approven_time'];
            $approven_date = $_SESSION['approven_date'];
            $approven_student_num = $_SESSION['approven_student_num'];

            # FORMAT DATE #
            $date = date_create($approven_date);
            $approven_date = date_format($date,"d M Y");

            $query = "  SELECT  *
                        FROM    course
                        WHERE   course_ID = $course_ID AND section = $section
                    ";
            
            $result = mysqli_query($conn, $query); 

            if (mysqli_num_rows($result) > 0) {
                
                while($rowpost = mysqli_fetch_array($result)) { 

                    # from table['course']
                    $course_name = $rowpost['course_name'];
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];  
                    $current_student = $rowpost['current_student'];
                    $open_student_number = $rowpost['open_student_number']; 

                }
            } 
        ?>

        <?php # จำนวนนิสิตที่อนุมัติ[ทั้งหมด!!!]
            $query = "  SELECT  student_ID
                        FROM    student_status
                        WHERE   course_ID = $course_ID  AND section = $section AND (status = 'อนุมัติแล้ว' OR status = 'ดำเนินการแล้ว')
                    ";
            $result = mysqli_query($conn, $query);
            $total_approven_student = mysqli_num_rows($result); 
        ?>

            <br>
            <div class="finish" style="background:none; margin-top:-20px">
                <p><aaa>รายวิชา</aaa> <w><?php echo $course_ID; ?> <?php echo $course_name; ?></w></p>
                <p><aaa>ตอนเรียน</aaa> <w><?php echo $section; ?></w></p>
                <p><aaa>ปีการศึกษา</aaa> <w><?php echo $academic_year; ?></w></p>
                <p><aaa>ภาคการศึกษา</aaa> <w><?php echo $semester; ?></w></p>
                <p><aaa>วันที่อนุมัติ</aaa> <w><i class="far fa-calendar-alt"></i> <?php echo $approven_date; ?></w>
                <p><aaa>เวลาที่อนุมัติ</aaa> <w><i class="far fa-clock"></i> <?php echo $approven_time; ?></w></p>
                <p><aaa>จำนวนนิสิตปัจจุบัน</aaa> <w><?php echo $current_student; ?> /<?php echo $open_student_number; ?></w></p>
                <p><aaa>จำนวนนิสิตที่อนุมัติในครั้งนี้</aaa> <w><?php echo $approven_student_num; ?></w></p>
                <p><aaa>จำนวนนิสิตที่อนุมัติในรายวิชานี้ทั้งหมด</aaa> <w><?php echo $total_approven_student; ?></w></p>
            </div>
            <br>
            <a href="history.php"><i class="fas fa-history"></i> <span>ประวัติการอนุมัติ</span></a>
    </div>
    <br>

</body>
</html>