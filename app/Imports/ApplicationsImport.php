<?php

namespace App\Imports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ApplicationsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected static array $statusValues = ['waiting', 'created', 'uploaded', 'verified', 'rejected'];

    protected static array $subStatusValues = ['waiting', 'created', 'uploaded', 'verified'];

    /**
     * Map Excel row to Application model.
     * Column headers in Excel should match (or be slugified): app_name, idea, domain, status, site_url, etc.
     */
    public function model(array $row): Application
    {
        return new Application([
            'app_name' => $row['app_name'] ?? '',
            'idea' => $row['idea'] ?? '',
            'domain' => $this->nullable($row['domain'] ?? null),
            'status' => $this->normalizeEnum($row['status'] ?? 'waiting', self::$statusValues),
            'site_url' => $this->nullable($row['site_url'] ?? null),
            'privacy_url' => $this->nullable($row['privacy_url'] ?? null),
            'delete_url' => $this->nullable($row['delete_url'] ?? null),
            'files_url' => $this->nullable($row['files_url'] ?? null),
            'design_url' => $this->nullable($row['design_url'] ?? null),
            'site_status' => $this->normalizeEnum($row['site_status'] ?? 'waiting', self::$subStatusValues),
            'privacy_status' => $this->normalizeEnum($row['privacy_status'] ?? 'waiting', self::$subStatusValues),
            'delete_status' => $this->normalizeEnum($row['delete_status'] ?? 'waiting', self::$subStatusValues),
            'files_status' => $this->normalizeEnum($row['files_status'] ?? 'waiting', self::$subStatusValues),
            'chort_description' => $this->nullable($row['chort_description'] ?? null),
            'long_description' => $this->nullable($row['long_description'] ?? null),
            'email_access' => $this->nullable($row['email_access'] ?? null),
            'note' => $this->nullable($row['note'] ?? null),
        ]);
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
        $value = $value ? strtolower(trim((string) $value)) : 'waiting';
        return in_array($value, $allowed, true) ? $value : $allowed[0];
    }

    public function rules(): array
    {
        return [
            //'*.app_name' => ['required', 'string', 'unique:applications,app_name'],
            //'*.idea' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            // 'app_name.required' => 'The app_name column is required in the Excel file.',
        ];
    }
}
