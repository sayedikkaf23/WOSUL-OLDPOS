<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
