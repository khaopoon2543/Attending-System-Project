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
        <h1>ยืนยันขออนุมัติเพิ่มรายวิชา</h1>

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
            </div>

        <?php //ดึงข้อมูลของ course&section นั้น!!
            $course_ID = $_SESSION['course_ID'];
            $section = $_SESSION['section'];

            $query = "  SELECT c.*, t.name
                        FROM course c , teacher_users t
                        WHERE $course_ID = c.course_ID AND $section = c.section AND $course_ID = t.course_ID
                     ";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {
                while($rowpost = mysqli_fetch_array($result)) { 
                    $course_name = $rowpost['course_name'];
                    $department = $rowpost['department'];
                    $level = $rowpost['level']; 
                    $credit = $rowpost['credit']; 
                    $teacher_name = $rowpost['name'];
                    $note = $rowpost['note'];

                }  
                
            $_SESSION['course_ID'] = $course_ID;
            $_SESSION['section'] = $section;
        ?>          

        <table class="table" id="attend_confirm_table">
            <thead>
                <tr>
                    <th>รหัสรายวิชา</th>
                    <th>ชื่อรายวิชา</th>
                    <th>ตอนเรียน</th>
                    <th>อาจารย์ที่สอน</th>
                    <th>ภาควิชา</th>  
                    <th>หน่วยกิต</th>
                    <th>หมายเหตุ</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td><center><?php echo $course_ID; ?></center></td>
                    <td><?php echo $course_name; ?></td>
                    <td><center><?php echo $section; ?></center></td>
                    <td><center><?php echo $teacher_name; ?></center></td>
                    <td><center><?php echo $department; ?></center></td>
                    <td><center><?php echo $credit; ?></center></td>
                    <td><center><?php echo $note; ?></center></td>
                </tr>
            </tbody>

        </table>

        <br><br><br><br>

        <aa style="font-size:20px;">หากไม่ใช่รายวิชาที่ต้องการขออนุมัติเพิ่มรายวิชา</aa>
        <a href="attend.php"><i class="fas fa-undo"></i> <span>กลับไปหน้าก่อน</span></a>
        <br>
        <a><i class="fas fa-asterisk"></i> กรุณาเพิ่มรายวิชาใน www2.reg.chula.ac.th ก่อนทำการกดยืนยัน <br>
        <i class="fas fa-asterisk"></i> มิเช่นนั้นการขอเพิ่มรายวิชาจะไม่เสร็จสมบูรณ์</a>
        

        <form action="attend_confirm_db.php" method="post">


            <div class="input-group" id="pass&submit" style="margin-top:20px;">
                <lable for="password" style="font-size: 20px; margin-left: 800px;" ><i class="fas fa-key" style="color: #e37aa1;"></i> กรุณากรอกรหัสผ่าน</lable>
                <input  type="password" name="confirm_password" 
                        style="font-size: 30px; margin-left: 790px; width: 220px;  border-radius: 100px;  border:2px solid;">
            </div>

                <input type="submit" value="ยืนยันขออนุมัติ" name="attend_submit" id="attend_submit" style="margin-left: 795px;">
            
            <?php include('errors.php'); ?>
            <?php if (isset($_SESSION['error'])) : ?>
            <div class="error" style="width: 50%; margin-top:-70px;">
                <h3>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </h3>
            </div>
            <?php endif ?>

        </form> <br>

        <?php
            } else {
                echo "ไม่มีรายวิชานี้ในระบบ กรุณากรอกข้อมูลรายวิชาให้ถูกต้อง";
                echo "<script> document.getElementById('attend_confirm_table').deleteRow(0); </script>";
                echo "<p><a href='attend.php'><i class='fas fa-undo'></i> <span>กลับไปหน้าก่อน</span></a></p>";
            }
        ?>
        

    </div>

</body>
</html>