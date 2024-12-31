<?php include('../config.php');
require '../vendor/autoload.php';


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();



function getColumnLabel($index) {
    $base26 = '';
    if ($index >= 26) {
        $base26 .= chr(65 + ($index / 26) - 1);
    }    
    $base26 .= chr(65 + ($index % 26));
    return $base26;
}


$exportSql = $_REQUEST['exportSql']; 

// $exportSql = "select a.lho, a.remarks,a.reference_code,a.id,a.bank,a.customer,a.location,a.zone,a.state,a.city,a.branch,a.created_by,a.bm,b.mis_id,b.atmid,
// b.component,b.subcomponent,b.engineer,b.docket_no,b.status,b.created_at,b.ticket_id,b.close_date,b.call_type,b.case_type ,      
// (SELECT name from vendorUsers WHERE id= a.created_by) AS createdBy,b.id as detailId,b.status as detailsStatus
// from mis a INNER JOIN mis_details b ON b.mis_id = a.id 
// where 1 and 
// b.mis_id = a.id 
//  and b.atmid like '%S1NG004319032%' and CAST(b.created_at AS DATE) >= '2023-01-01' and CAST(b.created_at AS DATE) <= '2024-04-18' and b.status in ('open', 'close', 'schedule', 'material_requirement', 'material_dispatch', 'permission_require', 'material_delivered', 'MRS', 'cancelled', 'available', 'not_available', 'material_in_process', 'fund_required', 'reassign', 'Mail Update')  order by b.id desc"
// ;

 $sql_app = mysqli_query($con, $exportSql); // Execute the SQL query


$headerStyles = [
    'font' => [
        'bold' => true, // Make the text bold
        'color' => ['rgb' => 'FFFFFF'], // Font color (white)
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '0070C0'], // Background color (blue)
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Border color (black)
        ],
    ],
];
// Define column headers
$headers = array(
'SR',
'TicketId',
'Customer',
'Bank',
'Atmid',
'Atm Address',
'City',
'State',
'Branch',
'Call Type',
'Call Receive From',
'Component',
'Sub Component',
'Current Status',
'Status Remarks',
'Schedule Date',
'Material Condition',
'Material',
'Material Remark',
'Courier Agency (Material Dispatch)',
'POD (Material Dispatch)',
'Serial Number',
'Material dispatch date ',
'Material Dispatch Remark',
'DOCKET NO',
'Close Type',
'Close Remark',
'Last Action By',
'Last Action Date',
'Call Log Date',
'Call Log By',
'Aging',
'Remark',
'Engineer Name',
'Engineer Contact Number',
'Dependency',
'Closure Time',
'Downtime',
'LHO',
'All Remarks'
);


$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startcolor' => ['rgb' => 'FFA500'], // Change this color as needed
    ],
];

$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];


foreach ($headers as $index => $header) {
    $column = getColumnLabel($index);
    $sheet->setCellValue($column . '1', $header);
    $sheet->getStyle($column . '1')->applyFromArray($headerStyle);
}


$i = 2; 
$counter = 1; 
while ($sql_result = mysqli_fetch_assoc($sql_app)) {
    
    $iterationStart = microtime(true);

    $id = $sql_result['id'];

    $detailId = $sql_result['detailId'];


    $customer = $sql_result['customer'];
    $ticket_id = $sql_result['ticket_id'];
    $createdBy = $sql_result['createdBy'];
    $mis_id = $sql_result['mis_id'];
    $closed_date = $sql_result['close_date'];
    $date2 = $sql_result['created_at'];
    $bank = $sql_result['bank'];
    $atmid = $sql_result['atmid'];
    $bm_name = $sql_result['bm'];
    $status = $sql_result['status'];

    if($status == 'reassign'){
        $status = 'Bank Dependecy' ;
    }

    $created_by = $sql_result['created_by'];
    $site_eng_contact = $sql_result['eng_name_contact'];
    $city = $sql_result['city'];
    $state = $sql_result['state'];
    $branch = $sql_result['branch'];
    $call_type = $sql_result['call_type'];
    $case_type = $sql_result['case_type'];
    $component = $sql_result['component'];
    $subcomponent = $sql_result['subcomponent'];
    $material_condition = $sql_result['material_condition'];
    $material = $sql_result['material'];
    $material_req_remark = $sql_result['material_req_remark'];
    $dispatch_date = $sql_result['dispatch_date'];
    $material_dispatch_remark = $sql_result['material_dispatch_remark'];
    $docket_no = $sql_result['docket_no'];
    $footage_date = $sql_result['footage_date'];
    $fromtime = $sql_result['fromtime'];
    $totime = $sql_result['totime'];
    $close_type = "";
    $created_date = $sql_result['created_date'];
    $created_at = $sql_result['created_at'];
    $remarks = $sql_result['remarks'];
    $site_engineer = $sql_result['eng_name'];
    $site_engineer_contact = $sql_result['eng_contact'];
    $pod = $sql_result['pod'];
    $courier_agency = $sql_result['courier_agency'];
    $serial_number = $sql_result['serial_number'];
    $lho = $sql_result['lho'];

    if ($closed_date != '0000-00-00') {
        $date1 = date_create($closed_date);
    }


    $cust_date2 = date('Y-m-d', strtotime($date2));

    $cust_date2 = date_create($cust_date2);
    if($date1 && $cust_date2){
        
    $diff = date_diff($date1, $cust_date2);
    $aging_day = $diff->format("%a");
    }else{
        $diff = '';
        $aging_day = '';
    }





    $historydate = mysqli_query($con, "select created_at from mis_history where mis_id='" . $detailId . "' order by id desc limit 1");
    $created_date_result = mysqli_fetch_row($historydate);
    $created_date = $created_date_result[0];

    $customer = $sql_result['customer'];
    $closed_date = $sql_result['close_date'];

    if ($closed_date != '0000-00-00') {
        $date1 = date_create($closed_date);
    }

    $date2 = $sql_result['created_at'];
    $cust_date2 = date('Y-m-d', strtotime($date2));

    $cust_date2 = date_create($cust_date2);

    if($date1 && $cust_date2){
        
    $diff = date_diff($date1, $cust_date2);
    $aging_day = $diff->format("%a");
    }else{
        $diff = '';
        $aging_day = '';
    }

    
    $atmid = $sql_result['atmid'];

    $bm_name = $sql_result['bm'];

    $created_by = $sql_result['created_by'];


    $mis_his_key = 0;


//     echo "select type,created_by,remark,schedule_date,material,material_condition,courier_agency,pod,serial_number,dispatch_date,(SELECT name FROM mis_loginusers WHERE id=mis_history.created_by) AS last_action_by from mis_history where mis_id='" . $detailId . "' order by id desc" ; 
// echo '<br />';
    
    $lastactionsql = mysqli_query($con, "select type,created_by,remark,schedule_date,material,material_condition,courier_agency,pod,serial_number,dispatch_date,(SELECT name FROM user WHERE userid = mis_history.created_by) AS last_action_by from mis_history where mis_id='" . $detailId . "' order by id desc");
    if ($lastactionsql_result = mysqli_fetch_assoc($lastactionsql)) {

        $his_type = $lastactionsql_result['type'];


        $lastactionuserid = $lastactionsql_result['created_by'];

        if($his_type == 'Mail Update'){
            $status_remark = 'Auto Update On ticket';
        }else{
            $status_remark = $lastactionsql_result['remark'];
        }

        if ($mis_his_key == 0) {
            $last_action_by = $lastactionsql_result['last_action_by'];
        }
        $mis_his_key = $mis_his_key + 1;
        $schedule_date = "";
        if ($his_type == 'schedule') {
            $schedule_date = $lastactionsql_result['schedule_date'];
        }


        $material = "";
        $material_req_remark = "";
        if ($his_type == 'material_requirement') {
            $material = $lastactionsql_result['material'];
            $material_req_remark = $lastactionsql_result['remark'];
            $material_condition = $lastactionsql_result['material_condition'];
        }
        $courier_agency = "";
        $pod = "";
        $serial_number = "";
        $dispatch_date = "";
        $material_dispatch_remark = "";
        // if($his_type=='material_dispatch'){
        $courier_agency = $lastactionsql_result['courier_agency'];
        $pod = $lastactionsql_result['pod'];
        $serial_number = $lastactionsql_result['serial_number'];
        $dispatch_date = $lastactionsql_result['dispatch_date'];
        $material_dispatch_remark = $lastactionsql_result['remark'];
        // }
        $close_type = "";
        $close_remark = "";
        $close_created_at = "";
        $attachment = "";
        if ($his_type == 'close') {
            $close_type = $lastactionsql_result['close_type'];
            $close_remark = $lastactionsql_result['remark'];
            $close_created_at = $lastactionsql_result['created_at'];
            $attachment = $lastactionsql_result['attachment'];
        }
    }

    $dependency = ''; // Initialize the $dependency variable
    $type = array();
    $timeDifference = '';
    $dependencySql = mysqli_query($con, "SELECT * FROM mis_history WHERE mis_id='" . $detailId . "'");
    while ($dependencySqlResult = mysqli_fetch_assoc($dependencySql)) {

        $closeType = $dependencySqlResult['type'];
        if ($closeType == 'close') {
            $closureTime = $dependencySqlResult['created_at'];

            $closureTime = new DateTime($closureTime);

            $date2 = new DateTime($sql_result['created_at']);
            $difference = $closureTime->diff($date2);

            $timeDifference = "";
            $timeDifference .= $difference->d > 0 ? $difference->d . " days " : "";
            $timeDifference .= $difference->h > 0 ? $difference->h . " hours " : "";
            $timeDifference .= $difference->i > 0 ? $difference->i . " minutes " : "";
            $timeDifference .= $difference->s > 0 ? $difference->s . " seconds" : "";

        }
        $alltypes  = $allremarks = [];

        if($closeType != 'Mail Update'){

            $alltypes[] = $dependencySqlResult['type'];
            $allremarks[] = $dependencySqlResult['remark'];

        }

    }
    
       $dependencySql2 = mysqli_query($con, "SELECT * FROM mis_history WHERE mis_id='" . $detailId . "' order by id desc");
                                                if($dependencySqlResult2 = mysqli_fetch_assoc($dependencySql2)){
                                                    
                                                    $misType = $dependencySqlResult2['type'];
                                                    
                                                        $closureTime2 = $datetime;  
                                                        $closureTime2 = new DateTime($closureTime2);

                                                        $date22 = new DateTime($sql_result['created_at']);
                                                        $difference2 = $closureTime2->diff($date22);
                                                        
                                                        $timeDifference2 = "";
                                                        $timeDifference2 .= $difference2->d > 0 ? $difference2->d . " days " : "";
                                                        $timeDifference2 .= $difference2->h > 0 ? $difference2->h . " hours " : "";
                                                        $timeDifference2 .= $difference2->i > 0 ? $difference2->i . " minutes " : "";
                                                        $timeDifference2 .= $difference2->s > 0 ? $difference2->s . " seconds" : "";
                                                        
                                                        if($misType=='close'){
                                                        $timeDifference2 = $timeDifference ;     
                                                        }
                                                        
                                                }
        

    if (count($type) > 0) {
        if (in_array('reassign', $type)) {
            $dependency = 'Bank';
        } else {
            $dependency = 'Advantage';
        }
    } else {
        $dependency = 'Advantage';
    }



    $allremarkswithstatus = '';
    $currentcounter = 0 ;
    foreach ($alltypes as $alltypeskey => $alltypesvalue ) {
        
       $allremarkswithstatus .=  $alltypesvalue  . ' ' . $allremarks[$currentcounter] . "\n\n";

       $currentcounter++ ;

    }



    
     $sheet->getStyle('A' . $i . ':AM' . $i)->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'], // Border color (black)
            ],
        ],
    ]);
    


      $sheet->setCellValue('A' . $i , $counter ) ; 
        $sheet->setCellValue('B' . $i , $ticket_id ? $ticket_id : 'NA' ) ;  
        $sheet->setCellValue('C' . $i , $customer ? $customer : 'NA' ) ;  
        $sheet->setCellValue('D' . $i , $bank ? $bank : 'NA' ) ;  
        $sheet->setCellValue('E' . $i , $atmid ? $atmid : 'NA' ) ;  
        $sheet->setCellValue('F' . $i , $sql_result['location'] ? $sql_result['location'] : 'NA' ) ;  
        $sheet->setCellValue('G' . $i , $city ? $city : 'NA' ) ;  
        $sheet->setCellValue('H' . $i , $state ? $state : 'NA' ) ;  
        $sheet->setCellValue('I' . $i , $branch ? $branch : 'NA' ) ;  
        $sheet->setCellValue('J' . $i , $call_type ? $call_type : 'NA' ) ;  
        $sheet->setCellValue('K' . $i , $case_type ? $case_type : 'NA' ) ;  
        $sheet->setCellValue('L' . $i , $component ? $component : 'NA' ) ;  
        $sheet->setCellValue('M' . $i , $subcomponent ? $subcomponent : 'NA' ) ;  
        $sheet->setCellValue('N' . $i , $status ? $status : 'NA' ) ;  

        
        $status_remark = strip_tags($status_remark);
        $status_remark = str_replace(array('<br>', '<br/>', '<br />','&nbsp;'), "\n", $status_remark);
        $status_remark = preg_replace('/\s+/', ' ', $status_remark);



        $sheet->setCellValue('O' . $i , $status_remark ? $status_remark : 'NA' ) ;  

        


        $sheet->setCellValue('P' . $i , $schedule_date ? $schedule_date : 'NA' ) ;  
        $sheet->setCellValue('Q' . $i , $material_condition ? $material_condition : 'NA' ) ;  
        $sheet->setCellValue('R' . $i , $material ? $material : 'NA' ) ;  
        
        $material_req_remark = strip_tags($material_req_remark);
        $material_req_remark = str_replace(array('<br>', '<br/>', '<br />','&nbsp;'), "\n", $material_req_remark);
        $material_req_remark = preg_replace('/\s+/', ' ', $material_req_remark);


        $sheet->setCellValue('S' . $i , $material_req_remark ? $material_req_remark : 'NA' ) ;  

        $sheet->setCellValue('T' . $i , $courier_agency ? $courier_agency : 'NA' ) ;  
        $sheet->setCellValue('U' . $i , $pod ? $pod : 'NA' ) ;  


        $sheet->setCellValue('V' . $i , $serial_number ? $serial_number : 'NA' ) ;  
        $sheet->setCellValue('W' . $i , $dispatch_date ? $dispatch_date : 'NA' ) ;  
        $sheet->setCellValue('X' . $i ,'' ) ;  
        // $sheet->setCellValue('X' . $i , $material_dispatch_remark ? $material_dispatch_remark : 'NA' ) ;  
        
        $sheet->setCellValue('Y' . $i , $docket_no ? $docket_no : 'NA' ) ;  
       
        $sheet->setCellValue('Z' . $i , $close_type ? $close_type : 'NA' ) ;  
        
        
        $close_remark = strip_tags($close_remark);
        $close_remark = str_replace(array('<br>', '<br/>', '<br />','&nbsp;'), "\n", $close_remark);
        $close_remark = preg_replace('/\s+/', ' ', $close_remark);


        $sheet->setCellValue('AA' . $i , $close_remark ? $close_remark : 'NA' ) ;  

        $sheet->setCellValue('AB' . $i , $last_action_by ? $last_action_by : 'NA' ) ;  
        $sheet->setCellValue('AC' . $i , $created_date ? $created_date : 'NA' ) ;  
        $sheet->setCellValue('AD' . $i , $created_at ? $created_at : 'NA' ) ;  


        $sheet->setCellValue('AE' . $i , $createdBy ? $createdBy : 'NA' ) ;  
        $sheet->setCellValue('AF' . $i , $timeDifference2 ? $timeDifference2 : 'NA' ) ;  
        
        
        $remarks = strip_tags($remarks);
        $remarks = str_replace(array('<br>', '<br/>', '<br />','&nbsp;'), "\n", $remarks);
        $remarks = preg_replace('/\s+/', ' ', $remarks);

        $sheet->setCellValue('AG' . $i , $remarks ? $remarks : 'NA' ) ;  


        $sheet->setCellValue('AH' . $i , $site_engineer ? $site_engineer : 'NA' ) ;  
        $sheet->setCellValue('AI' . $i , $site_engineer_contact ? $site_engineer_contact : 'NA' ) ;  

        $sheet->setCellValue('AJ' . $i , $dependency ? $dependency : 'NA' ) ;  
        $sheet->setCellValue('AK' . $i , $timeDifference ? $timeDifference : 'NA' ) ;  
        $sheet->setCellValue('AL' . $i , $timeDifference2 ? $timeDifference2 : 'NA' ) ; 
        $sheet->setCellValue('AM' . $i , $lho ? $lho : 'NA' ) ;  

        $allremarkswithstatus = strip_tags($allremarkswithstatus);
        $allremarkswithstatus = str_replace(array('<br>', '<br/>', '<br />','&nbsp;'), "\n", $allremarkswithstatus);
        $allremarkswithstatus = preg_replace('/\s+/', ' ', $allremarkswithstatus);


        $sheet->setCellValue('AN' . $i , $allremarkswithstatus ? $allremarkswithstatus : 'NA' ) ;  
        
        
    $i++;
    $counter++;
    
    
}


// return ; 
// Create a writer to save the Excel file
$writer = new Xlsx($spreadsheet);

// Save the Excel file to a temporary location
$tempFile = tempnam(sys_get_temp_dir(), 'Inventory');
$writer->save($tempFile);

// Provide the file as a download to the user
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="MIS.xlsx"');
header('Cache-Control: max-age=0');
readfile($tempFile);

// Close the database connection
mysqli_close($con);

// Clean up and delete the temporary file
unlink($tempFile);
?>
