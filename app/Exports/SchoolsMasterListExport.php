<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\SchoolTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolsMasterListExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use SchoolTrait;

    public function map($school):array
    {
        return [
            $school->id,
            $school->name,
            $school->address,
            $school->city,
            $school->province,
            $school->postal_code,
            $school->phone
        ];
    }

    public function headings():array
    {
        return [
            'ID',
            'School',
            'Address',
            'City',
            'Province',
            'Postal Code',
            'Phone'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->exportSchoolMasterList();
    }

    public function styles(Worksheet $sheet) 
    {
        $sheet->getStyle('A:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }
}