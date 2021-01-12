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

    /*!-- academic_year & semester --*/
    $query = " SELECT * FROM course ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $academic_year = $row['academic_year'];
        $semester = $row['semester'];
    }

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
    <title>รายวิชาที่เปิดสอน</title>

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
        <div class="choose"><a href="opening_course.php"><i class="fas fa-table"></i><span>รายวิชาที่เปิดสอน</span></a></div>
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
    

    <div class="content" >
        <h1>รายวิชาที่เปิดสอน</h1>
    
        <table class="table" id="opening_course_table">
            <thead>
                <tr>
                    <th>รหัสรายวิชา</th>
                    <th>ชื่อรายวิชา</th>
                    <th>ตอนเรียน</th>
                    <th>หมายเหตุ</th>
                    <th>จำนวนนิสิต</th> 
                    <th>นิสิตที่รอขออนุมัติ</th> 
                </tr>
            </thead>
        
        <form method="post" action="opening_course.php">

            <div class="head_course" id="opening_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                </p> 
            </div>

            <div class="search">
                <i class="fas fa-search"></i>
                รหัสรายวิชา
	            <input type="text" name="course_ID">
                ชื่อรายวิชา
                <input type="text" name="course_name" style="width:13%;">
                หมายเหตุ 
                <input type="text" name="note" style="width:10%;">
            </div>
            <input type="submit" name="submit" value="ค้นหา" id="search" style="margin:-55px 900px; margin-bottom:30px;">

        <?php

        if (empty($_POST["submit"]))  {
            echo "<div class='search'><i class='fas fa-info-circle' style='font-size:20px; color:#e37aa1;'></i>";
            echo "<a> กรุณาเลือกกรอกข้อมูล และกดปุ่ม 'ค้นหา' ทางด้านขวามือ</a></div>";
            echo "<script> document.getElementById('opening_course_table').deleteRow(0); </script>";
            exit() ;

        } else { 
            
            // ทางเลือกที่ 1 กำหนดเงื่อนไขการค้นหาตาม course_ID
            if (isset($_POST['course_ID']) && empty($_POST['course_name']) && empty($_POST['note']))
            {
                $course_ID = $_POST['course_ID'];
                $query = "select * from course WHERE course_ID like '%$course_ID%' ;";
            }	
            // ทางเลือกที่ 2 กำหนดเงื่อนไขการค้นหาตาม course_name
            elseif (isset($_POST['course_name']) && empty($_POST['course_ID']) && empty($_POST['note']))
            {
                $course_name = $_POST['course_name'];
                $query = "select * from course WHERE course_name like '%$course_name%' ;";
            }
            // ทางเลือกที่ 3 กำหนดเงื่อนไขการค้นหาตาม course_ID & course_name
            elseif (isset($_POST['course_name']) && isset($_POST['course_ID']) && empty($_POST['note']))
            {
                $course_ID = $_POST['course_ID'];
                $course_name = $_POST['course_name'];
                $query = "select * from course WHERE course_ID like '%$course_ID%' AND course_name like '%$course_name%' ;";
            }
            // ทางเลือกที่ 4 กำหนดเงื่อนไขการค้นหาตาม note
            elseif (isset($_POST['note']) && empty($_POST['course_ID']) && empty($_POST['course_name']))
            {
                $note = $_POST['note'];
                $query = "select * from course WHERE note like '%$note%' ;";
            }
            // ทางเลือกที่ 5 กำหนดเงื่อนไขการค้นหาตาม ALL !!!
            elseif (isset($_POST['note']) && isset($_POST['course_ID']) && isset($_POST['course_name']))
            {
                $course_ID = $_POST['course_ID'];
                $course_name = $_POST['course_name'];
                $note = $_POST['note'];
                $query = "select * from course WHERE course_ID like '%$course_ID%' AND course_name like '%$course_name%' AND note like '%$note%' ;";
            }
            // ทางเลือกที่ 6 ไม่ได้ใส่อะไรเลย;-;
            else
            {
                $query = "select * from course ORDER BY course_ID ASC;";
            }
        
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
                    $course_ID = $rowpost['course_ID'];
                    $section = $rowpost['section'];
                    
                    # จำนวนนิสิตที่ขออนุมัติ[ทั้งหมด!!!]
                    $query_total = "    SELECT  student_ID
                                        FROM    student_request
                                        WHERE   course_ID = $course_ID  AND section = $section
                                    ";
                    $result_total = mysqli_query($conn, $query_total);
                    $total_request_student = mysqli_num_rows($result_total);

        ?>          
                    <td><center><?php echo $rowpost['course_ID']; ?></center></td>
                    <td><?php echo $rowpost['course_name']; ?></td>
                    <td><center><?php echo $rowpost['section']; ?></center></td>
                    <td><center><?php echo $rowpost['note']; ?></center></td>
                    <td><center><?php echo $rowpost['current_student']; ?> / <?php echo $rowpost['open_student_number']; ?></center></td>

                    <?php if ($total_request_student == '0') { ?>
                            <td style="padding: 10px 5px;"><center><a class="btn0"><?php echo $total_request_student; ?></a><center></td> 
                    <?php  } else { ?>
                            <td style="padding: 10px 5px;"><center><a class="btn1"><?php echo $total_request_student; ?></a><center></td>
                    <?php } ?>

        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
                }
        ?>
        
            
        </form>
        </table>
        <?php
            }  else {
                echo "ไม่พบรายวิชาที่ต้องการค้นหา";
                echo "<script> document.getElementById('opening_course_table').deleteRow(0); </script>";
                exit() ; 
            }
        }
        ?>
        <p></p><p></p><p></p>
    </div>

    


</body>
</html>