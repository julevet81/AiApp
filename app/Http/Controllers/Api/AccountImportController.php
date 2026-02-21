<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountImportController extends Controller
{
    public function import(Request $request)
    {
        // Validation
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'replace' => ['nullable', 'boolean'],
        ]);

        DB::beginTransaction();

        try {

            // Import file
            Excel::import(new AccountsImport, $request->file('file'));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Accounts and Applications imported successfully'
            ], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->failures()
            ], 422);
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Import failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
