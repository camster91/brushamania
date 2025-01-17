<?php

namespace App\Imports;

use App\School;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;

class SchoolsMasterListImport implements ToCollection, WithHeadingRow, ShouldQueue, WithChunkReading
{
    
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            School::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => $row['school'],
                    'address' => $row['address'],
                    'city' => $row['city'],
                    'province' => $row['province'],
                    'postal_code' => $row['postal_code'],
                    'phone' => $row['phone'],
                    'url_slug' => Str::slug('school '.$row['school'].' '.$row['address'].' '.$row['city'], '-')
                ]
            );
        }
    }
    
    public function chunkSize(): int
    {
        return 500;
    }
}
