<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport;

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

            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:accounts',
            'email' => 'required|email|unique:accounts',
            'application_id' => 'required|exists:applications,id',
            'status' => 'nullable|in:opened,registered,confirmed,transferred',
            'transfer_price' => 'nullable|numeric'

        ]);

        $account = Account::create($validated);

        return response()->json($account, 201);
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
