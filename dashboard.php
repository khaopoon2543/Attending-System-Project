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
            <br>

        <?php // จำนวนนิสิตที่ขอเพิ่มรายวิชาทั้งหมด ------------------------------------------------------------------------
            
            $query = "  SELECT COUNT(ss.student_ID) as total_student_request
                        FROM student_status ss 
                        WHERE ss.status = 'ดำเนินการแล้ว'   
                    ";
            $result = mysqli_query($conn, $query); 
            while($rs = mysqli_fetch_array($result)){ 
                $total_student_request = $rs['total_student_request']; 
            }


            $query = "  SELECT c.*
                        FROM course c , student_status ss
                        WHERE c.course_ID = ss.course_ID AND ss.status = 'ดำเนินการแล้ว'
                        GROUP BY c.course_ID
                        ORDER BY COUNT(DISTINCT ss.student_ID) DESC
                        LIMIT 1
                    ";
            $result = mysqli_query($conn, $query); 
            if (mysqli_num_rows($result) > 0) {
                while($rs = mysqli_fetch_array($result)){ 
                    $course_name = $rs['course_name']; 
                }
            } else {
                $course_name = 'ไม่มีการดำเนินการขอเพิ่มรายวิชา'; 
            }

        ?>

        <div class="card" id="card1">
            <a>ดำเนินการเพิ่มแล้วทั้งหมด</a>
            <h2>    <?php echo $total_student_request; ?> <l>ครั้ง</l> 
                    <i class="fas fa-users"></i>
            </h2>
        </div>

        <div class="card" id="card2">
            <a>รายวิชาที่ดำเนินการเพิ่มมากที่สุด</a>
            <h2>    <?php echo $course_name; ?> 
                    <i class="fas fa-star"></i>
            </h2>
        </div>


        <?php  // CHART 1 DOUGHTNUT ------------------------------------------------------------------------------------
            $query = "  SELECT c.* , COUNT(DISTINCT ss.student_ID) as total_student
                        FROM course c , student_status ss
                        WHERE c.course_ID = ss.course_ID AND ss.status = 'ดำเนินการแล้ว'
                        GROUP BY c.course_ID
                        ORDER BY COUNT(DISTINCT ss.student_ID) DESC
                        LIMIT 7
                    ";
            $result = mysqli_query($conn, $query); 

            //for chart
            $course_ID = array();
            $total_student = array();
 
            while($rs = mysqli_fetch_array($result)){ 
                $course_ID[] = "\"".$rs['course_name']."\""; 
                $total_student[] = "\"".$rs['total_student']."\""; 
            }
            
            $course_ID = implode(",", $course_ID); 
            $total_student = implode(",", $total_student); 
 
        ?>
 
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

        <div class="table_chart">
            <div class="card_chart">
                <h2>รายวิชาที่ดำเนินการเพิ่มมากที่สุด
                    <a href="dashboard_1.php" style="float:right; margin-top:-20px;"> <i class="fas fa-external-link-alt" style="font-size:20px;"></i> </a>
                </h2>
                
                <div class="legend_cell">
                    <canvas id="myChart1"></canvas>
                </div>
            </div>
            <div class="card_chart">
                <h2>ภาคสาขาที่ดำเนินการเพิ่มมากที่สุด                  
                    <a href="dashboard_2.php" style="float:right; margin-top:-20px;"> <i class="fas fa-external-link-alt" style="font-size:20px;"></i> </a>
                </h2>
                <div class="legend_cell">
                    <canvas id="myChart2"></canvas>
                </div>
            </div>
        </div>

        <script>
            var ctx1 = document.getElementById("myChart1").getContext('2d');
            var myChart1 = new Chart(ctx1, {

               
                type: 'doughnut',
                data: {

                    labels: [<?php echo $course_ID;?> 
                    ],
                    datasets: [{
                        
                        data: [<?php echo $total_student;?>
                            ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        hoverBackgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                       
                    }]
                },
                
                options: {  legend: {
                                        position: 'right',
                                        labels: {   
                                                    fontSize: 15 ,
                                                    fontFamily: 'Prompt'	
                                                },
                                    }
                        }
                
            });
        </script>  

    
        <?php  // CHART 2 LINE ---------------------------------------------------------------------------------------------
            $query = "  SELECT c.department, COUNT(c.department) as total_student
						FROM student_status ss
						LEFT JOIN course c 
						ON ss.course_ID = c.course_ID  AND ss.section = c.section 
						WHERE ss.status = 'ดำเนินการแล้ว'
                        GROUP BY c.department
                        ORDER BY COUNT(ss.student_ID) DESC
                        LIMIT 7
                    ";
            $result = mysqli_query($conn, $query); 
 
 
            //for chart
            $department = array();
            $total_student = array();
 
            while($rs = mysqli_fetch_array($result)){ 
                $department[] = "\"".$rs['department']."\""; 
                $total_student[] = "\"".$rs['total_student']."\""; 
            }
            
            $department = implode(",", $department); 
            $total_student = implode(",", $total_student); 
 
        ?>

        <script>
            var ctx2 = document.getElementById("myChart2").getContext('2d');
            var myChart2 = new Chart(ctx2, {
                type: 'pie',
                data: {

                    datasets: [{
                        data: [<?php echo $total_student;?>
                            ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        hoverBackgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                    }],
                    labels: [<?php echo $department;?> 
                    ]
                },
                options: {  legend: {
                                        position: 'right',
                                        labels: {   
                                                    fontSize: 15 ,
                                                    fontFamily: 'Prompt'	
                                                },
                                    }
                            
                        }
                
            });
        </script>  

        
        <i class="fas fa-file-download"></i> ดาวน์โหลดข้อมูลแดชบอร์ด

        <br><br><br>

        <form method="post" action="dashboard_export.php">
            <input type="submit" name="export" class="btn btn-success" value="Export" style="margin:0px 350px;"/>
        </form>

    </div>

</body>
</html>