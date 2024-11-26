<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return Model|null
    */
    public function model(array $row): Model|User|null
    {
            return new User([
            'name' => $row["name"],
            'email' => $row["email"],
            'password' => Hash::make($row['password']),
            'role' => $row['role'],
            'phone_number' => $row['phone_number'],
            'address' => $row['address'],
        ]);
    }

    public function rules():array
    {
        return [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required',
            'phone_number' => ['regex:/^(((\+|)84)|0)(3|5|7|8|9)+([0-9]{8})\b/','nullable'],
            'address' => 'string|nullable',
            'role' => 'string|required',
        ];
    }
}
