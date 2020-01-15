<?php
namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;




class ActividadExport implements FromArray,ShouldAutoSize
{
    protected $Actividad;

    public function __construct(array $actividad)
    {
        $this->actividad = $actividad;
    }

    public function array(): array
    {
        return $this->actividad;
    }
}
