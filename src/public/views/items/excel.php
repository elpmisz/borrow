<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$spreadsheet = new Spreadsheet();
$writer = new Xlsx($spreadsheet);

$param = (isset($params) ? explode("/", $params) : "");
$type = (!empty($param[0]) ? $param[0] : "");

$sql = "SELECT A.name,IF(A.unit = '','-',A.unit) unit,IF(A.reference = '','-',B.name) reference_name
FROM item A
LEFT JOIN item B
ON A.reference = B.id
WHERE A.id != '' ";

if ($type) {
  $sql .= " AND A.type = {$type} ";
}
$sql .= " ORDER BY A.status ASC, A.type ASC, A.reference ASC ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$spreadsheet->setActiveSheetIndex(0);
$activeSheet = $spreadsheet->getActiveSheet();

$activeSheet->setCellValue('A1', '#');
$activeSheet->setCellValue('B1', 'สถานที่');
$activeSheet->setCellValue('C1', 'ชื่ออุปกรณ์');
$activeSheet->setCellValue('D1', 'ผู้ใช้งาน');

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
foreach ($result as $row) {
  $i++;
  $activeSheet->setCellValue('A' . $i, $i - 1);
  $activeSheet->setCellValue('B' . $i, $row['name']);
  $activeSheet->setCellValue('C' . $i, $row['unit']);
  $activeSheet->setCellValue('D' . $i, $row['reference_name']);
  $activeSheet->getStyle('A' . $i . ':D' . $i)->applyFromArray($styleData);
}

$date = date('Y-m-d');
$filename = $date . '_item.xlsx';
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=" . $filename);
header("Cache-Control: max-age=0");
$writer->save('php://output');
exit();
