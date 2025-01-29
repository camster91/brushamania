<?php

namespace App\Exports;

use App\Politician;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\PoliticianTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PoliticiansExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    use PoliticianTrait;

	public function map($politician):array
    {
        return [
            $politician->politician_name,
            $politician->title,
            $politician->constituency,
            $politician->email,
            $politician->phone
        ];
    }

	public function headings():array
    {
        return [
            'Name',
            'Title',
            'Constituency',
            'Email Address',
            'Phone Number'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->getPoliticiansList();
    }
}
