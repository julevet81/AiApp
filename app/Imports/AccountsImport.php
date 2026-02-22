<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Application;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class AccountsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {

            // 1️⃣ إنشاء التطبيق أولاً
            $application = Application::create([
                'app_name' => $row['app_name'],
                'idea' => $row['idea'],
                'domain' => $row['domain'] ?? null,
            ]);

            // 2️⃣ إنشاء الحساب وربطه بالتطبيق
            return new Account([
                'name' => $row['name'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'application_id' => $application->id,
                'status' => $row['status'] ?? 'opened',
            ]);
        });
    }

    protected function nullable(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        return (string) $value;
    }

    protected function normalizeEnum(?string $value, array $allowed): string
    {
        $value = $value ? strtolower(trim((string) $value)) : 'opened';
        return in_array($value, $allowed, true) ? $value : $allowed[0];
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string',
            '*.phone' => 'nullable',
            '*.email' => 'nullable|email',

            // بيانات التطبيق (إجبارية)
            '*.app_name' => 'required|string',
            '*.idea' => 'required|string',
            '*.domain' => 'nullable|string',

            '*.status' => [
                'nullable',
                Rule::in(['opened', 'registered', 'confirmed', 'transferred'])
            ],

        ];
    }
}
