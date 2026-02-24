<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountHistory;
use Illuminate\Http\Request;

class AccountHistoryController extends Controller
{
    public function changeDate(Request $request, AccountHistory $history)
    {
        $request->validate([
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        // تحديث التواريخ فقط
        if ($request->filled('created_at')) {
            $history->created_at = $request->created_at;
        }

        if ($request->filled('updated_at')) {
            $history->updated_at = $request->updated_at;
        }

        $history->save();

        return response()->json([
            'message' => 'History date updated successfully',
            'data' => $history
        ]);
    }
}
