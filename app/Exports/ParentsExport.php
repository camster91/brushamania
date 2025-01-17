<?php

namespace App\Exports;

use App\ChildParent;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Traits\ParentTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ParentsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    use ParentTrait;

	protected $year;

	public function __construct($year) {
		$this->year = $year;
	}

	public function map($parent):array
    {
    	$children = "";
    	$brushtracker = "";
    	for ($i=0; $i < count($parent->children); $i++) {
            if ($i == count($parent->children) - 1) {
                $children = $children.$parent->children[$i]->firstname." ".$parent->children[$i]->lastname;
            } else {
                $children = $children.$parent->children[$i]->firstname." ".$parent->children[$i]->lastname."\n";
            }
              
	        if ($i == count($parent->children) - 1) {
	            $brushtracker = $brushtracker.$parent->children[$i]->brush_count;
	        } else {
	            $brushtracker = $brushtracker.$parent->children[$i]->brush_count."\n";
	        }

        }
        return [
            $parent->parent_name,
            $parent->email,
            $parent->phone,
            $children,
            $brushtracker
        ];
    }

	public function headings():array
    {
        return [
            'Parent',
            'Email Address',
            'Phone Number',
            'Children',
            'Brushtracker Count'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->exportParents($this->year);
    }
}
