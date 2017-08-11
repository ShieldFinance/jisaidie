<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Request;
abstract class RepaymentsImport extends \Maatwebsite\Excel\Files\ExcelFile {

    public function getFile(Request $request)
    {
        $path = $request->service_file->store('documents');
        return $path;
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }

}

