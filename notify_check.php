<?php 
    session_start();
    include('server.php');

    /*!-- course_ID from course user choose --*/
    $course_ID = $_GET['id'];
    $section = $_GET['sec'];

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
    
    <div class="content">

        <table class="table" id="dashboard_table">
            <thead>
                <tr>
                    <th>ลำดับที่</th>
                    <th>รหัสนิสิต</th>
                    <th>ชื่อนิสิต</th> 
                    <th>วันที่</th>
                    <th style="padding-left:5px;">เวลา</th> 
                </tr>
            </thead>

        <?php
            $query = "  SELECT DISTINCT c.*, ss.*, su.*
                        FROM    course c, student_status ss, student_users su
                        WHERE   c.course_ID = $course_ID    AND c.section = $section
                        AND     ss.course_ID = $course_ID   AND ss.section = $section   AND ss.student_ID = su.student_ID AND ss.status = 'อนุมัติแล้ว'
                        ORDER BY ss.approven_date ASC";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {
                
                $row_count=0;
                $col_count=0;
                while($rowpost = mysqli_fetch_array($result)) { 
                    if($row_count%2!=0){
                        echo "<tbody>";
                        echo "<tr class='active-row'>";
                    } 
                    else {
                        echo "<tbody>";
                        echo "<tr>";
                    }
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester']; 
                    $course_name = $rowpost['course_name'];  
                    $current_student = $rowpost['current_student'];
                    $open_student_number = $rowpost['open_student_number'];
                    # FORMAT DATE #
                    $date = date_create($rowpost['approven_date']);
                    $approven_date = date_format($date,"d M Y");

                    # เก็บ student_ID ไว้ใน array ส่งต่อไป notify_check_db.php
                    # EX array --> { [ [0] => 6140053622 ,  [1] => 6140053633] }
                    $student_notify[] = $rowpost['student_ID'];

                    # จำนวนนิสิตที่อนุมัติแล้ว[ทั้งหมด!!!]
                    $query_total = "    SELECT  student_ID
                                        FROM    student_status
                                        WHERE   status = 'อนุมัติแล้ว' AND course_ID = $course_ID  AND section = $section
                                    ";
                    $result_total = mysqli_query($conn, $query_total);
                    $total_approven_student = mysqli_num_rows($result_total); 

        ?>          
                    <td><center><?php echo $row_count+1; ?></center></td>
                    <td><center><?php echo $rowpost['student_ID']; ?></center></td>
                    <td><?php echo $rowpost['name']; ?></td>
                    <td><?php echo $approven_date; ?></ce></td>
                    <td style="padding-left:5px;"><center><?php echo $rowpost['approven_time']; ?></center></td>
                    

        <?php       
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
        }

        # เก็บใส่ SESSION ส่ง array ไปยัง notify_check_db.php
        $_SESSION['student_notify'] = $student_notify;
        $_SESSION['course_ID'] = $course_ID;
        $_SESSION['section'] = $section;
        $_SESSION['current_student'] = $current_student;

        ?>


        <h1>แจ้งนิสิตที่เพิ่มรายวิชา <span><?php echo $course_ID;?> <?php echo $course_name; ?></span></h1>
        
            <div class="head_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                    <aa>ตอนเรียน</aa> <w><?php echo $section; ?></w>
                    <aa>จำนวนนิสิตปัจจุบัน</aa> <w><?php echo $current_student; ?> / <?php echo $open_student_number; ?></w>
                    <aa>นิสิตที่รอดำเนินการ</aa> <w><?php echo $total_approven_student; ?></w>
                </p> 
            </div>

        </table>
        
        <form action="notify_check_db.php" method="post" style="padding-top:100px;">

            <?php include('errors.php'); ?>
            <?php if (isset($_SESSION['error'])) : ?>
            <div class="error" style="width: 50%; margin-left:18px; margin-top:30px; margin-bottom:-100px;">
                <h3>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </h3>
            </div>
            <?php endif ?>

            <div class="input-group" id="pass&submit">
                <lable for="password" style="font-size: 20px; margin-left: 800px; display: inline;" ><i class="fas fa-key" style="color: #e37aa1;"></i> กรุณากรอกรหัสผ่าน</lable>
                <input  type="password" name="confirm_password" 
                        style="font-size: 30px; margin-left: 790px; width: 220px;  border-radius: 100px;  border:2px solid;">
            </div>

            <input type="submit" value="ยืนยันการแจ้งนิสิต" name="notify_submit" id="notify_submit" style="margin-left: 795px;">

        <?php
            } else {
                echo "ไม่มีนิสิตที่ผ่านการอนุมัติขอเพิ่มรายวิชาในภาคการศึกษานี้";
                echo "<script> document.getElementById('dashboard_table').deleteRow(0); </script>";
            }
        ?>
        
        </form>

    </div>
    <br>  

</body>
</html>