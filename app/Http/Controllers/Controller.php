<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Calculation\Financial as Financial;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function calculateXirr() {
       
        $values = array(-10000, 12000);
        $dates = array('2023-01-01', '2023-12-31');
        
        // Convert dates to Excel serialized date format
        $excelDates = array_map(function ($date) {
            return Date::stringToExcel($date);
        }, $dates);
        
        // Creating a new PhpSpreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Adding the values and dates to the sheet
        $sheet->fromArray([$values, $excelDates], null, 'A1');
        
        // Calculating XIRR
        $xirr = Financial::IRR($sheet->rangeToArray('A1:A' . count($values), null, true, false), $sheet->rangeToArray('B1:B' . count($values), null, true, false));
        
        // Printing the XIRR
        echo "XIRR: " . ($xirr * 100) . "%\n";
    }
}
