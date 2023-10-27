<?php
    require_once 'ReportExcelPhp/PHPExcel.php';
    
    require_once 'includes/db.inc.php';
    
    $connection = new mysqli($host, $db_user, $db_password, $database);
    
    $statement = $connection->prepare("SELECT s.sellerID, s.sellerName, p.paymentType, p.paymentAmount, p.paymentDate 
                        FROM sellerPayment p, seller s
                        WHERE p.sellerID = s.sellerID 
                        AND month(paymentDate) = ? 
                        AND year(paymentDate) = ? 
                        AND paymentID IN (SELECT paymentID FROM sellerPayment)
                        ORDER BY p.paymentType DESC");
    $statement->bind_param("ss", $_POST['month'], $_POST['year']);
    $statement->execute();
    $statement->bind_result($sellerID, $sellerName, $paymentType, $paymentAmount, $paymentDate);
    
    $report = PHPExcel_IOFactory::load("monthlyincomereport.xlsx");
    $report->setActiveSheetIndex(0);
    $sheet = $report->getActiveSheet();
    $textmonth = date("F", mktime(0, 0, 0, $_POST["month"], 10));
    $sheet->setCellValue("A2", "For ".$textmonth.", ".$_POST['year']);
    $date = date("Y-m-d");
    $sheet->setCellValue("E3", "Date: ".$date);
    
    $no = 1;
    $cellno = 5;
    $total = 0;
    
    while($statement->fetch()){
        $sheet->setCellValue("A".$cellno, $no);
        $sheet->setCellValue("B".$cellno, $sellerID);
        $sheet->setCellValue("C".$cellno, $sellerName);
        $sheet->setCellValue("D".$cellno, $paymentDate);
        $sheet->setCellValue("E".$cellno, $paymentType);
        $sheet->setCellValue("F".$cellno, $paymentAmount);
        
        $total += $paymentAmount;
        $cellno++;
        $no++;
        
    }
    $statement->close();
    $sheet->setCellValue("E".$cellno, "Total: ");
    $sheet->setCellValue("F".$cellno, $total);
    
    $styleArray = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
        ),
    );
    $sheet->getStyle("A5:F".($cellno - 1))->applyFromArray($styleArray);
    $sheet->getStyle("E".$cellno.":F".$cellno)->applyFromArray($styleArray);
    
    $filename = "Monthly_Income_report_".$textmonth."_".$_POST['year'].".xlsx";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=$filename");
    header("Cache-Control: max-age=0");
    
    $r = PHPExcel_IOFactory::createWriter($report, "Excel2007");
    $r->save("php://output");
    exit();
            
