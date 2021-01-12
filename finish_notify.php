<?php 
    session_start();
    include('server.php');

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM officer_user WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $faculty = $row['faculty'];
        $department = $row['department'];
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
    <title>แจ้งนิสิตที่เพิ่มรายวิชา</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    
</head>
<body>

    <input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn" style="left: 290px;"></i>
      </label>
      <div class="left_area">
        <h3>ระบบเพิ่มรายวิชา <span>เจ้าหน้าที่</span></h3>
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
        <a href="officer_index.php"><i class="fas fa-home"></i><span>หน้าหลัก</span></a>
        <div class="choose"><a href="notify.php"><i class="fas fa-check"></i><span>แจ้งนิสิตที่เพิ่มรายวิชา</span></a></div>
        <a href="officer_history.php"><i class="fas fa-history"></i><span>ประวัติการแจ้งนิสิต</span></a>
        <a href="dashboard.php"><i class="fas fa-table"></i><span>แดชบอร์ด</span></a>
        <a href="officer_index.php?logout='1'" style="color: #e37aa1;"><i class="fas fa-power-off"></i><span>ออกจากระบบ</span></a>
        <div class="sidebar_info_user" style="margin-top:-50px;">
            <p><?php echo $currentDate; ?></p>
            <p><?php echo $name; ?></p>
            <p><?php echo $department; ?></p>
            <p><?php echo $faculty; ?></p>
        </div>
        
    </div> 
    <!--sidebar end-->
    
    <div class="content" >
        <h1><i class="fas fa-check-circle" style="color:#e37aa1;"></i> แจ้งนิสิตที่เพิ่มรายวิชาเสร็จสมบูรณ์</h1>

        <?php
            $course_ID = $_SESSION['course_ID'];
            $section = $_SESSION['section'];
            $proceed_time = $_SESSION['proceed_time'];
            $proceed_date = $_SESSION['proceed_date'];
            $proceed_student_num = $_SESSION['proceed_student_num'];

            # FORMAT DATE #
            $date = date_create($proceed_date);
            $proceed_date = date_format($date,"d M Y");

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

        <?php # จำนวนนิสิตที่แจ้ง[ทั้งหมด!!!]
            $query = "  SELECT  student_ID
                        FROM    student_status
                        WHERE   course_ID = $course_ID  AND section = $section AND status = 'ดำเนินการแล้ว'
                    ";
            $result = mysqli_query($conn, $query);
            $total_proceed_student = mysqli_num_rows($result); 
        ?>

            <br>
            <div class="finish" style="background:none">
                <p><aaa>รายวิชา</aaa> <w><?php echo $course_ID; ?> <?php echo $course_name; ?></w></p>
                <p><aaa>ตอนเรียน</aaa> <w><?php echo $section; ?></w></p>
                <p><aaa>ปีการศึกษา</aaa> <w><?php echo $academic_year; ?></w></p>
                <p><aaa>ภาคการศึกษา</aaa> <w><?php echo $semester; ?></w></p>
                <p><aaa>วันที่แจ้งนิสิต</aaa> <w><i class="far fa-calendar-alt"></i> <?php echo $proceed_date; ?></w>
                <p><aaa>เวลาที่แจ้งนิสิต</aaa> <w><i class="far fa-clock"></i> <?php echo $proceed_time; ?></w></p>
                <p><aaa>จำนวนนิสิตปัจจุบัน</aaa> <w><?php echo $current_student; ?> /<?php echo $open_student_number; ?></w></p>
                <p><aaa>จำนวนนิสิตที่แจ้งในครั้งนี้</aaa> <w><?php echo $proceed_student_num; ?></w></p>
                <p><aaa>จำนวนนิสิตที่แจ้งในรายวิชานี้ทั้งหมด</aaa> <w><?php echo $total_proceed_student; ?></w></p>
            </div>
            <br>
            <a href="officer_history.php"><i class="fas fa-history"></i> <span>ประวัติการแจ้งนิสิต</span></a>

    </div>
    <br>  

</body>
</html>