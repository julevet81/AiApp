<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Exception;

class AppController extends Controller
{
    // عرض كل التطبيقات
    public function index()
    {
        try {
            $apps = Application::latest()->get();

            return response()->json([
                'success' => true,
                'data'    => $apps
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // إضافة تطبيق جديد
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'app_name' => 'required|string|max:255',
                'idea'     => 'required|string',
                'domain'   => 'required|string',
                'status'   => 'sometimes|required|in:waiting,created,uploaded,verified,rejected',
                'note'     => 'nullable|string',
            ]);

            $app = Application::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Application created successfully',
                'data'    => $app
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // عرض تطبيق واحد
    public function show($id)
    {
        try {
            $app = Application::findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $app
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // تحديث التطبيق
    public function update(Request $request, $id)
    {
        try {
            $app = Application::findOrFail($id);

            $validated = $request->validate([
                'app_name' => 'sometimes|string|max:255',
                'idea'     => 'sometimes|string',
                'domain'   => 'sometimes|string',
                'status'   => 'sometimes|required|in:waiting,created,uploaded,verified,rejected',
                'note'     => 'nullable|string',
            ]);

            $app->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully',
                'data'    => $app
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // حذف التطبيق
    public function destroy($id)
    {
        try {
            $app = Application::findOrFail($id);
            $app->delete();

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}