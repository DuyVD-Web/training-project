<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomUsersImport implements toCollection
{
    use SkipsFailures;

    private array $errors = [];

    public function collection(Collection $rows): void
    {
        $rows = $rows->slice(1);

        // Validate all rows first
        $validator = Validator::make($rows->toArray(), $this->rules());

        if ($validator->fails()) {
            // Log validation errors

            $this->errors = collect($validator->errors()->getMessages())
                ->flatMap(function ($messages, $attribute) {
                    // Extract row and field indices using regex
                    if (!preg_match('/(\d+)\.(\d+)/', $attribute, $matches)) {
                        return [];
                    }

                    $rowIndex = $matches[1];
                    $fieldIndex = $matches[2];

                    return collect($messages)
                        ->map(function ($message) use ($rowIndex, $fieldIndex,) {
                            $fieldNames = [
                                0 => 'Name',
                                1 => 'Email',
                                2 => 'Password',
                                3 => 'Role',
                                4 => 'Phone Number',
                                5 => 'Address'
                            ];
                            $errorMessage = preg_replace('/^\D*\d+\.\d+\s*/', '', $message);

                            return [
                                'row' => (int)$rowIndex + 1,
                                'field' => $fieldNames[$fieldIndex],
                                'message' => $errorMessage
                            ];
                        })
                        ->map(function ($error) {
                            $formattedError = sprintf(
                                "Row data: %d, Error: %s %s",
                                $error['row'],
                                $error['field'],
                                $error['message']
                            );

                            Log::error($formattedError);
                            return $formattedError;
                        })
                        ->all();
                })
                ->toArray();

            return;
        }

        // Perform bulk insert if validation passes
        try {
            $insertedCount = $this->rawBulkInsert($rows);
            Log::info("Successfully imported $insertedCount users");
        } catch (\Exception $e) {
            Log::error("User Import Error: " . $e->getMessage());
            $this->errors[] = $e->getMessage();
        }
    }

    public function rules():array
    {
        return [
            '*.0' => 'required|string|max:255', // name
            '*.1' => 'required|email:rfc,dns|unique:users,email', // email
            '*.2' => 'required|string|min:6', // password
            '*.3' => 'required|exists:roles,id', // role_id
            '*.4' => ['nullable', 'regex:/^(((\+|)84)|0)(3|5|7|8|9)+([0-9]{8})\b/'], // phone_number
            '*.5' => 'nullable|string|max:500', // address
        ];
    }

    /**
     *
     * @param Collection $rows
     * @return int
     */
    protected function rawBulkInsert(Collection $rows): int
    {
        $userData = $rows->map(function ($row) {
            return [
                'name' => $row[0],
                'email' => $row[1],
                'password' => Hash::make($row[2]),
                'role_id' => $row[3],
                'phone_number' => $row[4] ?? null,
                'address' => $row[5] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();
        return DB::table('users')->insert($userData);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }



}
