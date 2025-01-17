<?php

namespace App\Exports;

use App\Dentist;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\DentistTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DentistsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
	use DentistTrait;

	protected $year;

	public function __construct($year) {
		$this->year = $year;
	}

	public function map($dentist):array
    {
    	$schools = "";
    	$presentation_dates = "";
        for ($i=0; $i < count($dentist->presentations); $i++) { 
            if (isset($dentist->presentations[$i]->school)) {
                if ($i == count($dentist->presentations) - 1) {
                    $schools = $schools.$dentist->presentations[$i]->school;
                } else {
                    $schools = $schools.$dentist->presentations[$i]->school."\n";
                }
                
            }
            else {
                if ($i == count($dentist->presentations) - 1) {
                    $schools = $schools."";
                } else {
                    $schools = $schools."\n";
                }
            }
            if (isset($dentist->presentations[$i]->presentation_date)) {
                if ($i == count($dentist->presentations) - 1) {
                    $presentation_dates = $presentation_dates.$dentist->presentations[$i]->presentation_date;
                } else {
                    $presentation_dates = $presentation_dates.$dentist->presentations[$i]->presentation_date."\n";
                }
            }   
            else {
                if ($i == count($dentist->presentations) - 1) {
                    $presentation_dates = $presentation_dates."To be determined";
                } else {
                    $presentation_dates = $presentation_dates."To be determined\n";
                }
                
            }
        }
        return [
            $dentist->dentist_name,
            $dentist->clinic_name,
            $dentist->dentist_contact_name,
            $dentist->email,
            $dentist->phone,
            $dentist->website,
            $dentist->address,
            $dentist->city,
            $dentist->province,
            $dentist->postal_code,
            $dentist->postcards,
            $dentist->dental_society_name,
            $dentist->is_organizing_committee ? "Yes" : "No",
            $dentist->is_lootbag_committee ? "Yes" : "No",
            $dentist->is_pr_media_committee ? "Yes" : "No",
            $dentist->is_sponsor_committee ? "Yes" : "No",
            $dentist->is_telephoning_committee ? "Yes" : "No",
            $dentist->add_clinic_to_dentistfind ? "Yes" : "No",
            $schools,
            $presentation_dates
        ];
    }

	public function headings():array
    {
        return [
            'Dentist',
            'Clinic',
            'Contact Name',
            'Email Address',
            'Phone Number',
            'Website',
            'Address',
            'City',
            'Province',
            'Postal Code',
            'Postcards',
            'Dental Society',
            'Organizing Committee',
            'Loot Bag Committee',
            'PR Media Committee',
            'Sponsor Committee',
            'Telephoning Committee',
            'Add Clinic to Dentistfind.com',
            'Assigned Schools',
            'Presentation Date'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->exportDentists($this->year);
    }
}
