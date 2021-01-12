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
        <h1>ขออนุมัติเพิ่มรายวิชา</h1>

        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT c.*
                        FROM course c
                     ";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {
                while($rowpost = mysqli_fetch_array($result)) { 
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];       
                }}
        ?>
            <div class="head_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                    <aa>ชื่อ-นามสกุล</aa> <w><?php echo $name; ?></w>
                    <aa>เลขประจำตัวนิสิต</aa> <w><?php echo $student_ID; ?></w>
                </p>
                <p>
                    <i class="fas fa-pen-nib" style="font-size:30px; color:#e37aa1; float:left;"></i>
                    <aaa> กรุณากรอก 'รหัสรายวิชา' และ 'ตอนเรียน' ที่ต้องการขออนุมัติเพิ่มรายวิชา</aaa>
                </p>
            </div>
                
            <br><br>
            
        <form action="attend_db.php" method="post">

            <div class="input-group-student">
                    <label for="course_ID">รหัสรายวิชา</label> <input type="text" name="course_ID" class="form-control" style=" width: 15%;">
                    <label for="section" style="margin-left:30px;">ตอนเรียน</label> <input type="text" name="section" class="form-control" style=" width: 10%;">
            </div>

                <input type="submit" value="ยืนยัน" name="submit" id="submit" style="margin: -60px 730px;" OnClick="return confirm('หากท่านได้ทำการเพิ่มรายวิชาใน www.reg.chula.ac.th แล้ว กรุณากด OK')">

            <?php include('errors.php'); ?>
            <?php if (isset($_SESSION['error'])) : ?>
            <div class="error" style="width: 70%; margin-top:50px;">
                <h3>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </h3>
            </div>
            <?php endif ?>
        
        </form>

    </div>

</body>
</html>