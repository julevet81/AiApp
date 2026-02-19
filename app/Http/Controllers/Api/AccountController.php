<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport;
use App\Models\Application;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    public function index()
    {
        return response()->json(
            Account::with('application')->latest()->paginate(10)
        );
    }

    

    public function store(Request $request)
    {
        $validated = $request->validate([

            // بيانات الحساب
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:accounts,phone',
            'email' => 'required|email|unique:accounts,email',
            'status' => 'nullable|in:opened,registered,confirmed,transferred',
            'transfer_price' => 'nullable|numeric',

            // خيار 1: استخدام تطبيق موجود
            'application_id' => 'nullable|exists:applications,id',

            // خيار 2: إنشاء تطبيق جديد
            'application.app_name' => 'nullable|required_without:application_id|string|max:255',
            'application.idea' => 'nullable|required_without:application_id|string',
            'application.domain' => 'nullable|string|max:255',

        ]);

        DB::beginTransaction();

        try {

            // إذا تم إرسال application_id استخدمه
            if (!empty($validated['application_id'])) {

                $applicationId = $validated['application_id'];
            }
            // إذا تم إرسال بيانات تطبيق جديد
            elseif (!empty($validated['application'])) {

                $application = Application::create([
                    'app_name' => $validated['application']['app_name'],
                    'idea' => $validated['application']['idea'],
                    'domain' => $validated['application']['domain'] ?? null,
                ]);

                $applicationId = $application->id;
            } else {
                return response()->json([
                    'message' => 'application_id or application data is required'
                ], 422);
            }

            // إنشاء الحساب
            $account = Account::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'application_id' => $applicationId,
                'status' => $validated['status'] ?? 'opened',
                'transfer_price' => $validated['transfer_price'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Account created successfully',
                'data' => $account->load('application')
            ], 201);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        return response()->json(
            Account::with(['application', 'histories.user'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([

            'name' => 'sometimes|string|max:255',
            'phone' => "sometimes|string|unique:accounts,phone,$id",
            'email' => "sometimes|email|unique:accounts,email,$id",
            'status' => 'sometimes|in:opened,registered,confirmed,transferred',
            'transfer_price' => 'nullable|numeric'

        ]);

        if (isset($validated['status']) && $validated['status'] !== $account->status) {

            AccountHistory::create([
                'account_id' => $account->id,
                'old_status' => $account->status,
                'new_status' => $validated['status'],
                'updated_by' => Auth::user()->id ?? null,
            ]);
        }

        $account->update($validated);

        return response()->json($account);
    }


    public function destroy($id)
    {
        Account::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }

    // show history route
    public function history($id)
    {
        return response()->json(

            AccountHistory::with('user')
                ->where('account_id', $id)
                ->latest()
                ->get()

        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {

            Excel::import(new AccountsImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Accounts imported successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
