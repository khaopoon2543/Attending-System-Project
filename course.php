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
    
    <div class="content" >
        <h1>อนุมัติเพิ่มรายวิชา <o style="color: #e37aa1;">รายวิชาที่เปิดสอน</o></h1>
    
        <table class="table" id="course_table">
            <thead>
                <tr>
                    <th>รหัสรายวิชา</th>
                    <th>ชื่อรายวิชา</th>
                    <th>ตอนเรียน</th>
                    <th>จำนวนนิสิต</th> 
                    <th>นิสิตที่ขออนุมัติ</th> 
                </tr>
            </thead>

        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT c.*
                        FROM course c, teacher_users t 
                        WHERE t.username = '$id' AND c.course_ID = t.course_ID AND c.section = t.section
                        ORDER BY c.course_ID ASC";
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
                    $query_total = "  SELECT  student_ID
                                FROM    student_request
                                WHERE   course_ID = $course_ID  AND section = $section
                            ";
                    $result_total = mysqli_query($conn, $query_total);
                    $total_request_student = mysqli_num_rows($result_total); 

        ?>          
                    <td><center><?php echo $rowpost['course_ID']; ?></center></td>
                    <td><?php echo $rowpost['course_name']; ?></td>
                    <td><center><?php echo $rowpost['section']; ?></center></td>
                    <td><center><?php echo $rowpost['current_student']; ?> / <?php echo $rowpost['open_student_number']; ?></center></td>

                    <?php if ($total_request_student == '0') { ?>
                            <td style="padding: 10px 5px;"><center><a href="approve.php?id=<?php echo $rowpost['course_ID'];?>&sec=<?php echo $rowpost['section'];?>" role="button" class="btn0">
                                <?php echo $total_request_student; ?></a><center></td> 
                    <?php  } else { ?>
                            <td style="padding: 10px 5px;"><center><a href="approve.php?id=<?php echo $rowpost['course_ID'];?>&sec=<?php echo $rowpost['section'];?>" role="button" class="btn2">
                                <?php echo $total_request_student; ?></a><center></td>
                    <?php } ?>
                    

        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
        }
        ?>
        
            <div class="head_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                </p> 
            </div>

        <?php
            } else {
                echo "ไม่มีรายวิชาที่เปิดสอนในภาคการศึกษานี้";
                echo "<script> document.getElementById('course_table').deleteRow(0); </script>";
            }
        ?>

        </table>
    </div>

</body>
</html>