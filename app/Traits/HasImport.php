<?php

namespace App\Traits;

trait HasImport {

    public static function getImport($file) {
        $validFiles = [
            'octet-stream',
            'vnd.ms-excel',
            'vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        $file_parts = explode(";base64,", $file);
        $image_type_aux = explode("application/", $file_parts[0]);
        if (!in_array($image_type_aux[1], $validFiles)) {
            throw new \Exception('not a valid excel file');
        }
        $image_base64 = base64_decode($file_parts[1]);
        $file = storage_path('app/import.xlsx');
        file_put_contents($file, $image_base64);
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        return $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    }

}
