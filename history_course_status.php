<?php 
    session_start();
    include('server.php');

    /*!-- student_ID  --*/
    $student_ID = $_GET['id'];
    /*!-- course_ID & section  --*/
    $course_ID = $_SESSION['course_ID'];
    $section = $_SESSION['section'];
    
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
    <title>ประวัติการอนุมัติ</title>

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
        <a href="course.php"><i class="fas fa-table"></i><span style="regu">อนุมัติเพิ่มรายวิชา</span></a>
        <div class="choose"><a href="history.php"><i class="fas fa-history"></i><span>ประวัติการอนุมัติ</span></a></div>
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
    
    <?php
            $query = "  SELECT  ss.*, c.*, su.name
                        FROM course c, student_status ss, student_users su
                        WHERE $student_ID = ss.student_ID  AND $student_ID = su.student_ID
                        AND c.course_ID = $course_ID AND c.section = $section AND ss.course_ID = $course_ID AND ss.section = $section
                     ";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {

                while($rowpost = mysqli_fetch_array($result)) { 

                    # table['course']
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester']; 
                    $course_name = $rowpost['course_name'];  
                    $current_student = $rowpost['current_student'];
                    $open_student_number = $rowpost['open_student_number'];
                    
                    # table['student_status']
                    $status = $rowpost['status'];
                    $student_name = $rowpost['name'];
                    /* ----- date&time request -----*/
                    $request_time = $rowpost['request_time'];
                    $request_date = $rowpost['request_date']; 
                    # FORMAT DATE #
                    $date = date_create($rowpost['request_date']);
                    $request_date = date_format($date,"d M Y");  

                    if ( $rowpost['status'] == 'อนุมัติแล้ว') { 
                        /* ----- date&time approven -----*/
                        $approven_time = $rowpost['approven_time'];
                        $approven_date = $rowpost['approven_date'];
                        # FORMAT DATE #
                        $date = date_create($rowpost['approven_date']);
                        $approven_date = date_format($date,"d M Y");    
                        
                    }
                    if ( $rowpost['status'] == 'ดำเนินการแล้ว') { 
                        /* ----- date&time approven -----*/
                        $approven_time = $rowpost['approven_time'];
                        $approven_date = $rowpost['approven_date']; 
                        # FORMAT DATE #
                        $date = date_create($rowpost['approven_date']);
                        $approven_date = date_format($date,"d M Y");  

                        /* ----- date&time proceed -----*/
                        $proceed_time = $rowpost['proceed_time'];
                        $proceed_date = $rowpost['proceed_date'];  
                        # FORMAT DATE #
                        $date = date_create($rowpost['proceed_date']);
                        $proceed_date = date_format($date,"d M Y");     
                        
                    }
                }
        ?>
        
        <h1>สถานะการขออนุมัติ <span> <?php echo $course_ID; ?> <?php echo $course_name; ?> </span></h1>

            <div class="head_course">
                <p style="margin-bottom:40px;">
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                    <aa>ตอนเรียน</aa> <w><?php echo $section; ?></w>
                    <aa>จำนวนนิสิตปัจจุบัน</aa> <w><?php echo $current_student; ?> / <?php echo $open_student_number; ?></w>
                </p> 
                <br>
                    <aaa style="margin-top:10px;">รหัสนิสิต</aaa><w><?php echo $student_ID; ?></w>
                    <aaa style="margin-top:10px;">ชื่อนิสิต</aaa><w><?php echo $student_name; ?></w>
            </div>

            <br><br>


            <div class="status_history">
                <o style="padding: 0 20px 30px">สถานะปัจจุบัน</o>
                <?php
                    if ($status == 'รออนุมัติ') {
                        echo '<waiting>'; echo $status; echo'</waiting>';

                    } elseif ($status == 'อนุมัติแล้ว') {
                        echo '<approven>'; echo $status; echo'</approven>';

                    } else {
                        echo '<proceed>'; echo $status; echo'</proceed>';
                    }
                ?>
            </div>

            <br><br>

            <div class="head_course" style="background:none; margin-top:-20px" id="student_history">

                <p>
                    <aaa><i class="fas fa-check-circle"></i> ขออนุมัติเพิ่มรายวิชา</aaa> 
                    <w style="padding: 0 120px;"><i class="far fa-clock"></i> <?php echo $request_time; ?> &ensp; <i class="far fa-calendar-alt"></i> <?php echo $request_date; ?></w>
                </p>

                <?php if (isset($approven_time) && isset($approven_date)) { ?>
                    <p>
                        <aaa><i class="fas fa-check-circle"></i> อนุมัติการขอเพิ่มรายวิชาแล้ว</aaa> 
                        <w style="padding: 0 53px;"><i class="far fa-clock"></i> <?php echo $approven_time; ?> &ensp; <i class="far fa-calendar-alt"></i> <?php echo $approven_date; ?></w>
                    </p>
                <?php } else {?>
                    <p><aaa><i class="far fa-check-circle"></i> ยังไม่ได้รับการอนุมัติขอเพิ่มรายวิชา</aaa></p>
                <?php } ?>

                <?php if (isset($proceed_time) && isset($proceed_date)) { ?>
                    <p>
                        <aaa><i class="fas fa-check-circle"></i> ดำเนินการเพิ่มรายวิชาแล้ว</aaa> 
                        <w style="padding: 0 75px;"><i class="far fa-clock"></i> <?php echo $proceed_time; ?> &ensp; <i class="far fa-calendar-alt"></i> <?php echo $proceed_date; ?></w>
                    </p>
                <?php } else {?>
                    <p><aaa><i class="far fa-check-circle"></i> ยังไม่ได้ดำเนินการเพิ่มรายวิชา</aaa></p>
                <?php } ?>
                                
            </div>

        <?php
            } else {
                echo "ท่านยังไม่ได้ทำการขออนุมัติเพิ่มรายวิชา";
            }
        ?>

    </div>

</body>
</html>