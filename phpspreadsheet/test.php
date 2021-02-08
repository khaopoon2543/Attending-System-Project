<?php
// include composer autoload
require 'vendor/autoload.php';
 
// import the PhpSpreadsheet Class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
 
$sheet->setCellValue('A1', 'Hello World !'); // กำหนดค่าใน cell A1
$sheet->setCellValue('B1', 'ทดสอบข้อความภาษาไทย !'); // กำหนดค่าใน cell B1
 
$writer = new Xlsx($spreadsheet);
$output_file = "hello_world.xlsx"; // กำหนดชื่อไฟล์ excel ที่ต้องการ
$writer->save($output_file); // สร้าง excel 
 
if(file_exists($output_file)){ // ตรวจสอบว่ามีไฟล์ หรือมีการสร้างไฟล์ แล้วหรือไม่
    echo '<a href="'.$output_file.'" target="_blank">Download</a>';
}