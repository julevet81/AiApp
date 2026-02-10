<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class AppController extends Controller
{
    // عرض كل التطبيقات
    public function index()
    {
        return response()->json(Application::latest()->get());
    }

    // إضافة تطبيق جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'idea'     => 'required|string',
            'domain'   => 'required|string',
            'status'   => 'required|in:pending,active,stopped',
            'note'     => 'nullable|string',
        ]);

        $app = Application::create($validated);

        return response()->json($app, 201);
    }

    // عرض تطبيق واحد
    public function show(Application $app)
    {
        return response()->json($app);
    }

    // تحديث التطبيق
    public function update(Request $request, Application $app)
    {
        $verified = $request->validate([
            'app_name' => 'sometimes|required|string|max:255',
            'idea'     => 'sometimes|required|string',
            'domain'   => 'sometimes|required|string',
            'status'   => 'sometimes|required|in:pending,active,stopped',
            'note'     => 'nullable|string',
        ]);
        $app->update($verified);

        return response()->json($app);
    }

    // حذف التطبيق
    public function destroy(Application $app)
    {
        $app->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}