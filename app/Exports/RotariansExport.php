<?php

namespace App\Exports;

use App\Rotarian;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\RotarianTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RotariansExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
	use RotarianTrait;

	protected $year;

	public function __construct($year) {
		$this->year = $year;
	}

	public function map($rotarian):array
    {
    	$schools = "";
    	$presentation_dates = "";
    	for ($i=0; $i < count($rotarian->presentations); $i++) { 
            if (isset($rotarian->presentations[$i]->school)) {
                if ($i == count($rotarian->presentations) - 1) {
                    $schools = $schools.$rotarian->presentations[$i]->school;
                } else {
                    $schools = $schools.$rotarian->presentations[$i]->school."\n";
                }
                
            }
            else {
                $schools[] = "";
            }
            if (isset($rotarian->presentations[$i]->presentation_date)) {
                if ($i == count($rotarian->presentations) - 1) {
                    $presentation_dates = $presentation_dates.$rotarian->presentations[$i]->presentation_date;
                } else {
                    $presentation_dates = $presentation_dates.$rotarian->presentations[$i]->presentation_date."\n";
                }
            }   
            else {
                if ($i == count($rotarian->presentations) - 1) {
                    $presentation_dates = $presentation_dates."To be determined";
                } else {
                    $presentation_dates = $presentation_dates."To be determined\n";
                }
                
            }
        }
        return [
            $rotarian->rotarian_name,
            $rotarian->club,
            $rotarian->email,
            $rotarian->phone,
            $rotarian->is_organizing_committee ? "Yes" : "No",
            $rotarian->is_lootbag_committee ? "Yes" : "No",
            $rotarian->is_pr_media_committee ? "Yes" : "No",
            $rotarian->is_sponsor_committee ? "Yes" : "No",
            $rotarian->is_telephoning_committee ? "Yes" : "No",
            $schools,
            $presentation_dates
        ];
    }

	public function headings():array
    {
        return [
            'Rotarian',
            'Rotary Club',
            'Email Address',
            'Phone Number',
            'Organizing Committee',
            'Loot Bag Committee',
            'PR Media Committee',
            'Sponsor Committee',
            'Telephoning Committee',
            'Assigned Schools',
            'Presentation Date'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->exportRotarians($this->year);
    }
}
