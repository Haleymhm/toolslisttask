<?php
namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;


class EmpresaExport implements FromArray,ShouldAutoSize
{
    protected $Empresa;

    public function __construct(array $empresa)
    {
        $this->empresa= $empresa;
    }

    public function array(): array
    {
        return $this->empresa;
    }
}
