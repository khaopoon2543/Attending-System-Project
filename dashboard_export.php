<?php  //export.php  
    session_start();
    include('server.php');
    
	// include composer autoload
	require 'phpspreadsheet/vendor/autoload.php';
	 
	// import the PhpSpreadsheet Class
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    if(isset($_POST["export"])) {

        $query = "  SELECT c.* , COUNT(DISTINCT ss.student_ID) as total_student
                    FROM course c , student_status ss
                    WHERE c.course_ID = ss.course_ID AND ss.status = 'ดำเนินการแล้ว'
                    GROUP BY c.course_ID
                    ORDER BY COUNT(DISTINCT ss.student_ID) DESC
                    
                ";
        $result = mysqli_query($conn, $query);
		if(mysqli_num_rows($result) > 0) {

			$spreadsheet = new Spreadsheet();
			$spreadsheet->setActiveSheetIndex(0);
			$sheet = $spreadsheet->getActiveSheet()->setTitle('แบ่งตามรายวิชา');
			 
			$sheet->setCellValue('A1', 'ลำดับที่'); // กำหนดค่าใน cell A1
			$sheet->setCellValue('B1', "รหัสรายวิชา"); // กำหนดค่าใน cell B1
			$sheet->setCellValue('C1', "ชื่อวิชา");
			$sheet->setCellValue('D1', "จำนวนนิสิตที่เพิ่มรายวิชาแล้ว");
			$row_count=1;
            $col_count=0;
            while($row = mysqli_fetch_array($result)) {
               
                $row_count_new = $row_count+1 ;
				$change_int_to_st = strval($row_count_new);
				$sheet->setCellValue("A$change_int_to_st","$row_count" ); 
				$sheet->setCellValue("B$change_int_to_st", $row['course_ID']); 
				$sheet->setCellValue("C$change_int_to_st", $row["course_name"]);
				$sheet->setCellValue("D$change_int_to_st", $row['total_student']);
				$row_count++; 
                $col_count++;
			}
			$last_row = $row_count+1;
			$last_change_int_to_st = strval($last_row);
			
            $query = "  SELECT COUNT(ss.student_ID) as total_student_request
                        FROM student_status ss  
						WHERE ss.status = 'ดำเนินการแล้ว'
                    ";
            $result = mysqli_query($conn, $query); 
            while($rs = mysqli_fetch_array($result)){ 
                $total_student_request = $rs['total_student_request']; 
            }
			$sheet->setCellValue("B$last_change_int_to_st","จำนวนทั้งหมด");
			$sheet->setCellValue("D$last_change_int_to_st",$total_student_request);
			$sheet->mergeCells("B$last_change_int_to_st:C$last_change_int_to_st");
			
			$sheet2 = $spreadsheet->createSheet();
			// Zero based, so set the second tab as active sheet
			$sheet2 = $spreadsheet->setActiveSheetIndex(1);
			$sheet2 = $spreadsheet->getActiveSheet()->setTitle('แบ่งตามภาควิชา');
			$sheet2->setCellValue('A1', 'ลำดับที่'); // กำหนดค่าใน cell A1
			$sheet2->setCellValue('B1', "ภาควิชา"); // กำหนดค่าใน cell B1
			$sheet2->setCellValue('C1', "จำนวนนิสิตที่เพิ่มรายวิชาแล้ว");
			$row_count=1;
			$col_count=0;
			$query = "  SELECT c.department, COUNT(c.department) as total_student
						FROM student_status ss
						LEFT JOIN course c 
						ON ss.course_ID = c.course_ID  AND ss.section = c.section
						WHERE ss.status = 'ดำเนินการแล้ว'
                        GROUP BY c.department
                        ORDER BY COUNT(ss.student_ID) DESC
                    ";
            $result = mysqli_query($conn, $query); 
			while($rowpost = mysqli_fetch_array($result)) {
               
                $row_count_new = $row_count+1 ;
				$change_int_to_st = strval($row_count_new);
				//$spreadsheet->setActiveSheetIndex(1)
				$sheet2->setCellValue("A$change_int_to_st","$row_count" ); 
				$sheet2->setCellValue("B$change_int_to_st", $rowpost['department']); 
				$sheet2->setCellValue("C$change_int_to_st", $rowpost['total_student']);
				$row_count++; 
				$col_count++;
			} 
			$last_row = $row_count+1;
			$last_change_int_to_st = strval($last_row);
            while($rs = mysqli_fetch_array($result)){ 
                $total_student_request = $rs['total_student_request']; 
            }
			$sheet2->setCellValue("B$last_change_int_to_st","จำนวนทั้งหมด");
			$sheet2->setCellValue("C$last_change_int_to_st",$total_student_request);
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="สรุปผลระบบเพิ่มรายวิชา.xlsx"');
			//header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
		}
	}
?>