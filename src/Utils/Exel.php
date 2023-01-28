<?php
/*
* Create exel file via PhpOfice 
*/
namespace App\Utils;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exel{
    function createXLS($data, $biz, $date, $sku, $typeC ){  
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $i =1;
        foreach ( $data as $line ) {            
            $sheet->fromArray([$line], NULL, "A$i"); 
            $i++;
        }
        $filename=__DIR__ ."/csvupload/$biz-$sku-$date-$typeC.xlsx";
    
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        return "/csvupload/$biz-$sku-$date-$typeC.xlsx";
    }
}

