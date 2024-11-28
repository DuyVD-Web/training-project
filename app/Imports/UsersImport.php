<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return User
     */
    public function model(array $row): User
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
            'role' => 'string|required|in:admin,user',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
