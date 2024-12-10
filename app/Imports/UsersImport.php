<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnFailure
{
    use SkipsFailures;
    private array $errors = [];

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
            'role_id' => $row['role_id'],
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
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors()
            ];
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
