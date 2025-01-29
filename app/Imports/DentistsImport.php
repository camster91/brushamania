<?php

namespace App\Imports;

use App\Dentist;
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DentistsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $randstr = Str::random(20);
        $user = new User;
        $user->firstname = $row['firstname'];
        $user->lastname = $row['lastname'];
        $user->email = $row['email'];
        $user->role = 'dentist';
        $user->password = bcrypt($randstr);
        $user->save();
        return new Dentist([
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'phone' => $row['phone'],
            'user_id' => $user->id,
            'last_registration_year' => 1,
            'url_slug' => Str::slug('dentist dr '.$row['firstname'].' '.$row['lastname'], '-')
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
