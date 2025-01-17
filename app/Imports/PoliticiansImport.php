<?php

namespace App\Imports;

use App\Politician;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PoliticiansImport implements ToModel, WithChunkReading, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Politician([
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'url_slug' => Str::slug('politician '.$row['firstname'].' '.$row['lastname'], '-'),
            'title' => $row['title'],
            'constituency' => $row['constituency'],
            'city' => $row['city'],
            'province' => $row['province']
        ]);
    }

    public function batchSize(): int
    {
        return 500;
    }
    
    public function chunkSize(): int
    {
        return 500;
    }
}
