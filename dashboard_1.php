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
    <title>แดชบอร์ด</title>

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
        <a href="officer_history.php"><i class="fas fa-history"></i><span>ประวัติการแจ้งนิสิต</span></a>
        <div class="choose"><a href="dashboard.php"><i class="fas fa-table"></i><span>แดชบอร์ด</span></a></div>
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
        <h1>แดชบอร์ด</h1>

            <div class="head_course" id="dashboard">
    
                <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
            
            </div>

        <h2 style="color:#e37aa1; font-weight: lighter; margin-top:70px; margin-bottom:10px;"> 
            <i class="fas fa-table"></i> 
            รายวิชาที่ดำเนินการเพิ่มมากที่สุด
        </h2>

        <table class="table" bordered="1">
            <thead>  
                    <tr>  
                        <th>ลำดับที่</th>  
                        <th>รหัสรายวิชา</th>  
                        <th>ชื่อวิชา</th>  
                        <th>จำนวนนิสิตที่ขอเพิ่มรายวิชา</th>
                    </tr>
            </thead>

        <?php
            $query = "  SELECT c.* , COUNT(DISTINCT ss.student_ID) as total_student
                        FROM course c , student_status ss
                        WHERE c.course_ID = ss.course_ID AND ss.status = 'ดำเนินการแล้ว'
                        GROUP BY c.course_ID
                        ORDER BY COUNT(DISTINCT ss.student_ID) DESC
						LIMIT 7
                    ";
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
        ?>

                    <td><center><?php echo $row_count+1; ?></center></td>
                    <td><?php echo $rowpost['course_ID']; ?></td>
                    <td><?php echo $rowpost['course_name']; ?></td>
                    <td><center><?php echo $rowpost['total_student']; ?></center></td>
                    
        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
        }   } 
        
        ?>
        </table>
        

    </div>

</body>
</html>