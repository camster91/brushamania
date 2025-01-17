<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\Traits\SchoolTrait;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NewSchoolExport implements FromView, ShouldAutoSize, WithStyles
{
	use SchoolTrait;

    protected $year;

	public function __construct($year) {
		$this->year = $year;
	}

    public function view(): View
    {
        return view('exports.schools', [
            'schools' => $this->exportSchools($this->year),
            'year' => $this->year
        ]);
    }

    public function styles(Worksheet $sheet) 
    {
        $sheet->getStyle('A:Y')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }


}
