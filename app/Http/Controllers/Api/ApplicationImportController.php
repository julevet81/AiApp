<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\ApplicationsImport;
use App\Models\Application;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ApplicationImportController extends Controller
{
    public function import(Request $request)
    {
        try {

            $validated = $request->validate([
                'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
                'replace' => ['nullable', 'boolean'],
            ]);

            $replace = (bool) ($validated['replace'] ?? false);

            if ($replace) {
                Application::truncate();
            }

            Excel::import(new ApplicationsImport(), $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Applications imported successfully'
            ], 200);
        } catch (ValidationException $e) {

            $errors = [];

            if (method_exists($e, 'failures')) {
                foreach ($e->failures() as $failure) {
                    $errors[] = [
                        'row' => $failure->row(),
                        'errors' => $failure->errors(),
                    ];
                }
            } else {
                $errors = $e->errors();
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $errors
            ], 422);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

