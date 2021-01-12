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
    <title>หน้าหลัก</title>

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
        <div class="choose"><a href="student_index.php"><i class="fas fa-home"></i><span>หน้าหลัก</span></a></div>
        <a href="opening_course.php"><i class="fas fa-table"></i><span>รายวิชาที่เปิดสอน</span></a>
        <a href="attend.php"><i class="fas fa-user-plus"></i><span>ขออนุมัติเพิ่มรายวิชา</span></a>
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
    
    <div class="content">
        <h1>ยินดีต้อนรับเข้าสู่ <span>ระบบเพิ่มรายวิชา</span></h1>
        <p style="padding-top: 120px;"><a><i class="fas fa-asterisk"></i> กรุณาเพิ่มรายวิชาใน www2.reg.chula.ac.th ก่อนทำการขออนุมัติเพิ่มรายวิชา มิเช่นนั้นการขอเพิ่มรายวิชาจะไม่เสร็จสมบูรณ์</a></p>
        <p><a><i class="fas fa-asterisk"></i> ท่านสามารถตรวจสอบผลการเพิ่มรายวิชาได้ใน <i>รายงานผลการลงทะเบียนเรียนรายบุคคล(CR54)</i></a></p>
        <p><a><i class="fas fa-asterisk"></i> หากพบปัญหาโปรดติดต่อเจ้าหน้าที่ฝ่ายงานทะเบียน คณะอักษรศาสตร์ โดยทันที</a></p>
        <p></p>
        <p>กรุณาเลือกบริการที่ต้องการจากรายการด้านซ้ายมือ \ (^-^) /</p>
        <p><a>ท่านจะออกจากระบบโดยอัตโนมัติ เมื่อหยุดการติดต่อนานเกิน 10 นาที</a></p>
        <p>ท่านต้องกด <a>ออกจากระบบ</a> ทุกครั้งที่เสร็จสิ้นการใช้งาน เพื่อมิให้ผู้อื่นเข้าใช้งาน ในชื่อของท่านได้</p>
    </div>
    


</body>
</html>