<?php 
    session_start();
    include('server.php');

    /*!-- course_ID from course user choose --*/
    $course_ID = $_GET['id'];
    $section = $_GET['sec'];

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM teacher_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $faculty = $row['faculty'];
        $department = $row['department'];
        # เก็บ username & password ไว้เช็ค password ในการยืนยันเพิ่มรายวิชา #
        $username = $row['username'];
        $password = $row['password'];

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
        <div class="choose"><a href="course.php"><i class="fas fa-table"></i><span>อนุมัติเพิ่มรายวิชา</span></a></div>
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

    <!--head info coruse START-->
    <div class="content">
        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT c.course_ID, c.course_name, c.section, c.department, c.semester, c.academic_year, c.level, c.current_student, c.open_student_number
                        FROM course c, teacher_users t 
                        WHERE t.username = '$id' AND c.course_ID = '".$_GET["id"]."' AND c.course_ID = t.course_ID AND c.section = '".$_GET["sec"]."' AND c.section = t.section ";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {

                while($rowpost = mysqli_fetch_array($result)) { 
                    $course_name = $rowpost['course_name'];
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];
                    $section = $rowpost['section']; 
                    $current_student = $rowpost['current_student'];
                    $open_student_number = $rowpost['open_student_number'];
                }
            }    
        ?>
		<script language="JavaScript">
		function toggle(source) {
			var checkboxes = document.querySelectorAll('input[type="checkbox"][id="check_approved"]');
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i] != source)
					checkboxes[i].checked = source.checked;
			}
		}
		</script>

        <h1>อนุมัติเพิ่มรายวิชา <o style="color: #e37aa1;"><?php echo $course_ID; ?> <?php echo $course_name; ?></o></h1>
        <div class="head_course">
            <p>
                <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                <aa>ตอนเรียน</aa> <w><?php echo $section; ?></w>
                <aa>จำนวนนิสิตปัจจุบัน</aa> <w><?php echo $current_student; ?> / <?php echo $open_student_number; ?></w>
            </p>   
        </div>
    </div>
    <!--head info coruse END-->
    
    <!--student request list table START-->
    <div class="content">

    <form action="checkbox.php" method="post"> 

        <table class="table" id="student_request_table" >
		
         
            <thead>
                <tr>
                    <th>ลำดับที่</th>
                    <th style="padding-left: 20px;">รหัสนิสิต</th>
                    <th style="padding-left: 20px;">ชื่อนิสิต</th> 
                    <th style="padding-left: 15px;">เวลา</th>
                    <th style="padding-left: 15px;">วันที่</th>
                    <th style="padding-left: 20px;">อนุมัติ <br><input id="checked_all" type="checkbox" onClick="toggle(this)" /> check all<br/></td></th> 
                </tr>
            </thead>    

        <?php
            $query = "  SELECT sr.*, su.name, c.current_student
                        FROM student_request sr, student_users su, course c
                        WHERE $course_ID = sr.course_ID AND $section = sr.section AND sr.student_ID = su.student_ID AND $course_ID = c.course_ID AND $section = c.section 
                        ORDER BY sr.student_ID ASC ";
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
                    # FORMAT DATE #
                    $date = date_create($rowpost['request_date']);
                    $request_date = date_format($date,"d M Y");
                    
        ?>  
                    <td><center><?php echo $row_count+1; ?></center></td>
                    <td style="padding-left: 20px;"><?php echo $rowpost['student_ID']; ?></td>
                    <td style="padding-left: 20px;"><?php echo $rowpost['name']; ?></td>
                    <td style="padding-left: 20px;"><?php echo $rowpost['request_time']; ?></td>
                    <td style="padding-left: 20px;"><?php echo $request_date; ?></td>
                    <td><input type="checkbox" name="approven_studentid[<?php echo $rowpost['student_ID']; ?>][student_ID]" value="<?php echo $rowpost['student_ID']; ?>" id="check_approved"></td>
                    <input type="hidden" name="approven_studentid[<?php echo $rowpost['student_ID']; ?>][course_ID]" value="<?php echo $course_ID; ?>">
                    <input type="hidden" name="approven_studentid[<?php echo $rowpost['student_ID']; ?>][section]" value="<?php echo $section; ?>" >
                    <input type="hidden" name="approven_studentid[<?php echo $rowpost['student_ID']; ?>][current_student]" value="<?php echo $rowpost['current_student']; ?>" >
                    <input type="hidden" name="approven_studentid[<?php echo $rowpost['student_ID']; ?>][username]" value="<?php echo $username; ?>" >
                    
		
        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
                }  
        ?> 
    
        </table>
        <p></p><p></p>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="error" style="width: 50%; margin-top: 25px; margin-bottom:-97px;">
                <h3>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <div class="input-group" >
            <lable for="password" style="font-size: 20px; margin-left: 750px; display: inline;" ><i class="fas fa-key" style="color: #e37aa1;"></i> กรุณากรอกรหัสผ่าน</lable>
            <input  type="password" name="confirm_password" 
                    style="font-size: 30px; margin-left: 740px; width: 220px;  border-radius: 100px;  border:2px solid;">
        </div>

        <?php   # เก็บ course_ID & section ส่งไป checkbox #
                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;
        ?>

        <input type="submit" value="ยืนยันการอนุมัติ" name="submit" id="submit" >

    <p></p><p></p><p></p>
    </form>
    </div>

        <?php
            } else {
                echo "ไม่มีนิสิตขออนุมัติเพิ่มรายวิชานี้";
                echo "<script> document.getElementById('student_request_table').deleteRow(0); </script>";
                echo "<script> document.getElementById('submit').deleteRow(0); </script>";
            }
        ?>

    <!--student request list table END-->
    
        
</body>
</html>
