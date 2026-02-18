<?php

namespace App\Console\Commands;

use App\Imports\ApplicationsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportApplicationsFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:import
                            {file : Path to the Excel file (.xlsx, .xls, or .csv)}
                            {--replace : Truncate applications table before import (replaces all data)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import applications from an Excel or CSV file into the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = $this->argument('file');

        if (! is_readable($path)) {
            $this->error("File not found or not readable: {$path}");
            return self::FAILURE;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            $this->error('File must be .xlsx, .xls, or .csv');
            return self::FAILURE;
        }

        if ($this->option('replace')) {
            if (! $this->confirm('This will delete all existing applications. Continue?')) {
                return self::SUCCESS;
            }
            \App\Models\Application::query()->truncate();
            $this->info('Applications table cleared.');
        }

        $this->info('Importing...');

        try {
            Excel::import(new ApplicationsImport, $path);
            $this->info('Import completed successfully.');
            return self::SUCCESS;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $this->error('Validation failed:');
            foreach ($e->failures() as $failure) {
                $this->error("Row {$failure->row()}: " . implode(', ', $failure->errors()));
            }
            return self::FAILURE;
        } catch (\Throwable $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
