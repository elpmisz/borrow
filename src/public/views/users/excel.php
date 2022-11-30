<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$spreadsheet = new Spreadsheet();
$writer = new Xlsx($spreadsheet);

$param = (isset($params) ? explode("/", $params) : "");
$zone = (!empty($param[0]) ? $param[0] : "");

$sql = "SELECT A.id user_id,A.name user_name,A.email user_email,A.contact user_contact,A.province_code,
B.name login_name,C.name province_name,CONCAT('เขต ',C.zone_id) district_name,
CASE 
  WHEN B.level = 1 THEN 'ผู้ใช้ระดับจังหวัด'
  WHEN B.level = 2 THEN 'ผู้ใช้ระดับเขต'
  WHEN B.level = 9 THEN 'ผู้ดูแลระบบ'
  ELSE NULL
END level_name,
CASE 
  WHEN B.level = 1 THEN 'warning'
  WHEN B.level = 2 THEN 'primary'
  WHEN B.level = 9 THEN 'danger'
  ELSE NULL
END level_color,
IF(A.status = 1,'รายละเอียด','ระงับการใช้งาน') status_name,
IF(A.status = 1,'success','danger') status_color
FROM user_detail A
LEFT JOIN user_login B
ON A.id = B.user_id
LEFT JOIN province C
ON A.province_code = C.code ";

if ($zone) {
  $sql .= " AND C.zone_id = {$zone} ";
}

$sql .= " ORDER BY A.status ASC, B.level DESC, C.zone_id ASC, A.id ASC ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$activeSheet->setCellValue('A1', '#');
$activeSheet->setCellValue('B1', 'รหัส');
$activeSheet->setCellValue('C1', 'ชื่อหน่วยงาน');
$activeSheet->setCellValue('D1', 'จังหวัด');
$activeSheet->setCellValue('E1', 'เขต');
$activeSheet->setCellValue('F1', 'ระดับ');

foreach (range('A', 'F') as $column) {
  $activeSheet->getColumnDimension($column)->setAutoSize(true);
}

$styleHeader = [
  'font' => [
    'bold' => true,
  ],
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
  ],
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ],
  ]
];

$activeSheet->getStyle('A1:F1')->applyFromArray($styleHeader);

$styleData = [
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ]
  ],
];

$i = 1;
foreach ($result as $row) {
  $i++;
  $activeSheet->setCellValue('A' . $i, $i - 1);
  $activeSheet->setCellValue('B' . $i, $row['login_name']);
  $activeSheet->setCellValue('C' . $i, $row['user_name']);
  $activeSheet->setCellValue('D' . $i, $row['province_name']);
  $activeSheet->setCellValue('E' . $i, $row['district_name']);
  $activeSheet->setCellValue('F' . $i, $row['level_name']);
  $activeSheet->getStyle('A' . $i . ':F' . $i)->applyFromArray($styleData);
}

$date = date('Y-m-d');
$filename = $date . '_users.xlsx';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=" . $filename);
header("Cache-Control: max-age=0");
$writer->save('php://output');
exit();
