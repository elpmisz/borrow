<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$spreadsheet = new Spreadsheet();
$writer = new Xlsx($spreadsheet);

$param = (isset($params) ? explode("/", $params) : "");
$type = (!empty($param[0]) ? $param[0] : "");

$sql = "SELECT A.id,A.name,IF(A.unit = '','-',A.unit) unit,IF(A.reference = '','-',B.name) reference_name
FROM item A
LEFT JOIN item B
ON A.reference = B.id
WHERE A.type = 2 ";
$sql .= " ORDER BY A.status ASC, A.type ASC, A.reference ASC ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll();

$sql = "SELECT A.id,A.name,B.name code
FROM user_detail A
LEFT JOIN user_login B
ON A.id = B.user_id
WHERE B.level IN (2,9)
AND A.id NOT IN (96) ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();

$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$activeSheet->setCellValue('A1', 'รหัส');
$activeSheet->setCellValue('B1', 'ชื่ออุปกรณ์');
$activeSheet->setCellValue('C1', 'หน่วย');
$activeSheet->setCellValue('D1', 'อ้างอิง');
foreach ($users as $user) {
  $activeSheet->setCellValue('E1', $user['code']);
}

foreach (range('A', 'D') as $column) {
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

$activeSheet->getStyle('A1:D1')->applyFromArray($styleHeader);

$styleData = [
  'borders' => [
    'allBorders' => [
      'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    ]
  ],
];

$i = 1;
foreach ($items as $item) {
  $i++;
  $activeSheet->setCellValue('A' . $i, $item['id']);
  $activeSheet->setCellValue('B' . $i, $item['name']);
  $activeSheet->setCellValue('C' . $i, $item['unit']);
  $activeSheet->setCellValue('D' . $i, $item['reference_name']);
  $activeSheet->getStyle('A' . $i . ':D' . $i)->applyFromArray($styleData);
}

$date = date('Y-m-d');
$filename = $date . '_form_item.xlsx';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=" . $filename);
header("Cache-Control: max-age=0");
$writer->save('php://output');
exit();
