<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AccountsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Account([
            'name' => $row['name'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'application_id' => $row['application_id'],
            'status' => $row['status'] ?? 'opened',
            'transfer_price' => $row['transfer_price'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string',
            '*.phone' => 'required|unique:accounts,phone',
            '*.email' => 'required|email|unique:accounts,email',
            '*.application_id' => 'required|exists:applications,id',
            '*.status' => [
                'nullable',
                Rule::in(['opened', 'registered', 'confirmed', 'transferred'])
            ],
            '*.transfer_price' => 'nullable|numeric'
        ];
    }
}
