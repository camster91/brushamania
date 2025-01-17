<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\ChildTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChildrenExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
	use ChildTrait;

	protected $year;

	public function __construct($year) {
		$this->year = $year;
	}

	public function map($child):array
    {
        return [
            $child->registration_date,
            $child->child_name,
            "".$child->brush_count,
            "".$child->days_brushing,
            $child->child_parent->name,
            $child->user,
            $child->child_parent->phone
        ];
    }

	public function headings():array
    {
        return [
            'Date of Registration',
            'Student Name',
            'Brushtracker Count',
            'Number of days brushing',
            'Parent Name',
            'Parent Email Address',
            'Parent Phone Number'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->exportChildren($this->year);
    }

    public function styles(Worksheet $sheet) 
    {
        $sheet->getStyle('A:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }
}
