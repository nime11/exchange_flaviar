<?php
/*
* Create exel file via PhpOfice 
*/
namespace App\Utils;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class Exel{
    function createXLS($data){  
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $i =1;
        foreach ( $data as $line ) {            
            $sheet->fromArray([$line], NULL, "A$i"); 
            $i++;
        }
        $filename='flaviar.xlsx';
        $sheet->getStyle('B:D')
        ->getNumberFormat()
        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

        $writer = new Xlsx($spreadsheet);
        ob_start();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($filename).'"');
        $writer->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        return  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        );
    }
}

