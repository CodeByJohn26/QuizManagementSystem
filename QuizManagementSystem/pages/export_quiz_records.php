<?php
require '../includes/db_connection.php';
require '../vendor/autoload.php'; // Ensure Composer dependencies are installed

session_start();

// Access control
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? null;
if (!$quiz_id) {
    echo "<script>alert('Quiz ID is missing!'); window.location.href = 'view_analytics.php';</script>";
    exit;
}

// Retrieve quiz results including total score and completion time
$stmt = $conn->prepare("
    SELECT u.username AS student_name, r.score, r.total, r.completion_time, r.created_at
    FROM quiz_results r
    JOIN users u ON r.student_id = u.id
    WHERE r.quiz_id = ? AND u.role = 'student'
");
$stmt->execute([$quiz_id]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Load PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Quiz Records");

// Set headers in Excel
$sheet->setCellValue('A1', 'Student Name')
      ->setCellValue('B1', 'Score')
      ->setCellValue('C1', 'Total Score')
      ->setCellValue('D1', 'Percentage (%)')
      ->setCellValue('E1', 'Completion Time')
      ->setCellValue('F1', 'Submission Date');

$row = 2;
$chartData = []; // Store values for chart generation
foreach ($records as $record) {
    // Calculate percentage score
    $percentage = $record['total'] > 0 ? round(($record['score'] / $record['total']) * 100, 2) : 0;

    // Retrieve stored completion time
    $completionTime = $record['completion_time'] ? date('H:i:s', strtotime($record['completion_time'])) : 'N/A';

    $sheet->setCellValue("A$row", $record['student_name'])
          ->setCellValue("B$row", $record['score'])
          ->setCellValue("C$row", $record['total'])
          ->setCellValue("D$row", $percentage)
          ->setCellValue("E$row", $completionTime)
          ->setCellValue("F$row", $record['created_at']);

    // Store data for chart
    $chartData[] = [$record['student_name'], $percentage];
    $row++;
}

// Insert Chart Below Data
$chartLabels = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'Quiz Records'!A2:A$row", null, count($chartData));
$chartValues = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'Quiz Records'!D2:D$row", null, count($chartData));

$dataSeries = new DataSeries(DataSeries::TYPE_BARCHART, null, range(0, count($chartData) - 1), [$chartLabels], [], [$chartValues]);
$plotArea = new PlotArea(null, [$dataSeries]);
$chartTitle = new Title('Student Quiz Performance');

$chart = new Chart('quiz_chart', $chartTitle, null, $plotArea);
$chart->setTopLeftPosition('H3');
$chart->setBottomRightPosition('N20');

$sheet->addChart($chart);

// Generate and output the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="quiz_records.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->setIncludeCharts(true); // Ensure the chart gets exported
$writer->save('php://output');
exit;