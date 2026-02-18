<?php

namespace App\Http\Controllers;

use App\Imports\ApplicationsImport;
use App\Models\Application;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ApplicationImportController extends Controller
{
    public function create()
    {
        return view('applications.import');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'replace' => ['nullable', 'boolean'],
        ]);

        $replace = (bool) ($validated['replace'] ?? false);

        try {
            if ($replace) {
                Application::query()->truncate();
            }

            Excel::import(new ApplicationsImport(), $request->file('file'));

            return back()->with('success', 'Import completed successfully.');
        } catch (ValidationException $e) {
            $messages = [];
            foreach ($e->failures() as $failure) {
                $messages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return back()->withErrors(['file' => $messages])->withInput();
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => $e->getMessage()])->withInput();
        }
    }
}

