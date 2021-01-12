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
    <title>ประวัติการแจ้งนิสิต</title>

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
        <a href="notify.php"><i class="fas fa-check"></i><span>แจ้งนิสิตที่เพิ่มรายวิชา</span></a>
        <div class="choose"><a href="officer_history.php"><i class="fas fa-history"></i><span>ประวัติการแจ้งนิสิต</span></a></div>
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

        <table class="table" id="history_course_table">
            <thead>
                <tr>
                    <th>ลำดับที่</th>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>รหัสนิสิต</th>
                    <th>ชื่อนิสิต</th> 
                    <th>คณะ</th>
                    <th>สาขา</th>
                    <th>สถานะ</th>
                </tr>
            </thead>

        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT DISTINCT c.*, ss.*, su.*
                        FROM    course c, student_status ss, student_users su
                        WHERE   c.course_ID = $course_ID    AND c.section = $section
                        AND     ss.course_ID = $course_ID   AND ss.section = $section   AND ss.student_ID = su.student_ID AND status = 'ดำเนินการแล้ว'
                        ORDER BY ss.approven_time ASC";
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
                    $date = date_create($rowpost['proceed_date']);
                    $proceed_date = date_format($date,"d M Y"); 
                    
                
        ?>
                    <td><center><?php echo $row_count+1; ?></center></td>
                    <td><?php echo $proceed_date; ?></ce></td>
                    <td><center><?php echo $rowpost['proceed_time']; ?></center></td>
                    <td><center><?php echo $rowpost['student_ID']; ?></center></td>
                    <td><?php echo $rowpost['name']; ?></td>
                    <td><center><?php echo $rowpost['faculty']; ?></center></td>
                    <td><center><?php echo $rowpost['major']; ?></center></td>
                    <?php if ( $rowpost['status'] == 'อนุมัติแล้ว') { ?>
                        <td><center><a href="officer_history_course_status.php?id=<?php echo $rowpost['student_ID'];?>" role="button" style="text-decoration: none;">
                            <approven><?php echo $rowpost['status']; ?></approven></a></center></td>
       
                    <?php  } elseif ( $rowpost['status'] == 'รออนุมัติ') { ?>
                        <td><center><a href="officer_history_course_status.php?id=<?php echo $rowpost['student_ID'];?>" role="button" style="text-decoration: none;">
                            <waiting><?php echo $rowpost['status']; ?></waiting></a></center></td>

                    <?php } else { # status = 'ดำเนินการแล้ว' ?> 
                        <td><center><a href="officer_history_course_status.php?id=<?php echo $rowpost['student_ID'];?>" role="button" style="text-decoration: none;">
                            <proceed><?php echo $rowpost['status']; ?></proceed></a></center></td>
                    <?php } ?>    
                    
        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
                }
        ?>

        <form action="officer_history_course_status.php" method="post"> 
            <?php // ส่งข้อมูลไปที่ history_course_status.php
        
                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;

            ?>
        </form>
        
        <h1>ประวัติการแจ้งนิสิต <o style="color: #e37aa1;"><?php echo $course_ID; ?> <?php echo $course_name; ?></o></h1>
            <div class="head_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                    <aa>ตอนเรียน</aa> <w><?php echo $section; ?></w>
                    <aa>จำนวนนิสิตปัจจุบัน</aa> <w><?php echo $current_student; ?> / <?php echo $open_student_number; ?></w>
                </p> 
            </div>

        <?php
            } else {
                echo "ไม่มีประวัติการแจ้งการดำเนินการเพิ่มรายวิชา";
                echo "<script> document.getElementById('history_course_table').deleteRow(0); </script>";
            }
        ?>

        </table>
    </div>
    <br><br>

</body>
</html>