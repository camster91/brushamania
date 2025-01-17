<?php

namespace App\Exports;

use App\School;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\SchoolTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SchoolsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
	use SchoolTrait;

    protected $year;

	public function __construct($year) {
		$this->year = $year;
	}

    public function map($school):array
    {
        dd($school);

        $dentist_user = null;
        if (isset($school->presentation['dentist_user']) && $school->presentation['dentist_user'] > 0) {
            $dentist_user = User::find($school->presentation->dentist_user);
        }
        $rotarian_user = null;
        if (isset($school->presentation['rotarian_user']) && $school->presentation['rotarian_user'] > 0) {
            $rotarian_user = User::find($school->presentation->rotarian_user);
        }
        $guests = "";
        if(!is_null($school->presentation)) {
            foreach($school->presentation->guests as $i => $guest) {
                if($i < count($school->presentation->guests) - 1) {
                    $guests.=$guest->name.", ";
                } else {
                    $guests.=$guest->name;
                }
            }
        }

        $is_paid = "No";
        if(strtolower($school->postal_code[0]) == 'm') {
            $is_paid = "Toronto School";
        } elseif(isset($school->presentation->is_paid))  {
            $is_paid = $school->presentation->is_paid ? "Paid" : "Not paid";
        }

        return [
            $this->year != 0 ? !is_null($school->presentation) ? $school->presentation->created_at : "" : $school->last_registration_year,
            $school->school_name,
            $school->address,
            $school->city,
            $school->province,
            $school->postal_code,
            $school->phone,
            'https://www.google.com/maps/search/'.$school->school_slug,
            $school->contact,
            $school->email,
            !is_null($school->presentation) ? $school->presentation->total_classes : "",
            !is_null($school->presentation) ? $school->presentation->total_students : "",
            !is_null($school->presentation) ? $school->presentation->requested_presentation_date : "",
            !is_null($school->presentation) ? $school->presentation->presentation_date : "",
            $is_paid,
            $school->requested_dentist,
            !is_null($school->presentation) ? $school->presentation->dentist : "",
            !is_null($dentist_user) ? $school->presentation->dentist_contact_salutation.' '.$dentist_user->firstname.' '.$dentist_user->lastname : "",
            !is_null($school->presentation) ? $school->presentation->dentist_phone : "",
            !is_null($dentist_user) ? $dentist_user->email : "",
            !is_null($school->presentation) ? $school->presentation->rotarian : "",
            !is_null($school->presentation) ? $school->presentation->rotarian_phone : "",
            !is_null($rotarian_user) ? $rotarian_user->email : "",
            !is_null($school->presentation) ? $school->presentation->rotary_club : "",
            $guests
        ];
    }

    public function headings():array
    {
        return [
            $this->year != 0 ? 'Registration Date' : 'Last Year School Participated',
            'School Name',
            'School Address',
            'School City',
            'School Province',
            'School Postal Code',
            'School Telephone',
            'Link to School Map',
            'School Contact',
            'School Email',
            'Total Number of Classes',
            'Total Number of Students',
            'Requested Presentation Date',
            'Presentation Date',
            'School Fee Paid',
            'Requested Dentist',
            'Assigned Dentist',
            'Dentist Contact',
            'Dentist Telephone',
            'Dentist Email',
            'Assigned Rotarian',
            'Rotarian Telephone',
            'Rotarian Email',
            'Rotary Club',
            'Name of Guests',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->exportSchools($this->year);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A:Y')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }


}
