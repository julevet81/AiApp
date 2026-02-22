<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;


class AppController extends Controller
{
    public function index()
    {
        try {
            $apps = Application::latest()->get();

            return response()->json([
                'success' => true,
                'data'    => $apps
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch applications',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([

                'app_name'           => 'required|string|max:255',
                'idea'               => 'required|string',
                'domain'             => 'nullable|string|max:255',

                'status'             => 'sometimes|in:waiting,created,uploaded,verified,rejected',

                'site_url'           => 'nullable|string|max:255',
                'privacy_url'        => 'nullable|string|max:255',
                'delete_url'         => 'nullable|string|max:255',
                'design_url'         => 'nullable|string|max:255',
                'files_url'          => 'nullable|string|max:255',

                'site_status'        => 'sometimes|in:waiting,created,uploaded,verified,rejected',
                'privacy_status'     => 'sometimes|in:waiting,created,uploaded,verified,rejected',
                'delete_status'      => 'sometimes|in:waiting,created,uploaded,verified,rejected',
                'files_status'      => 'sometimes|in:waiting,created,uploaded,verified',

                'chort_description'  => 'nullable|string|max:255',
                'long_description'   => 'nullable|string',

                'email_access'       => 'nullable|email|max:255',

                'note'               => 'nullable|string',
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
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

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
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $app = Application::findOrFail($id);

            $validated = $request->validate([

                'app_name'           => 'sometimes|string|unique:applications,app_name,' . $id . '|max:255',
                'idea'               => 'sometimes|string',
                'domain'             => 'sometimes|string|max:255',
                'status'             => 'sometimes|in:waiting,created,uploaded,verified,rejected',

                'site_url'           => 'sometimes|string|max:255',
                'privacy_url'        => 'sometimes|string|max:255',
                'delete_url'         => 'sometimes|string|max:255',
                'design_url'         => 'sometimes|string|max:255',
                'files_url'          => 'sometimes|string|max:255',

                'site_status'        => 'sometimes|in:waiting,created,uploaded,verified,rejected',
                'privacy_status'     => 'sometimes|in:waiting,created,uploaded,verified,rejected',
                'delete_status'      => 'sometimes|in:waiting,created,uploaded,verified,rejected',
                'files_status'       => 'sometimes|in:waiting,created,uploaded,verified',

                'chort_description'  => 'nullable|string|max:255',
                'long_description'   => 'nullable|string',

                'email_access'       => 'nullable|string|max:255',

                'note'               => 'nullable|string',
            ]);

            if (isset($validated['status']) && in_array($validated['status'], ['verified', 'uploaded'])) {
                $validated['site_status'] = 'uploaded';
                $validated['privacy_status'] = 'uploaded';
                $validated['delete_status'] = 'uploaded';
            }

            $app->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully',
                'data'    => $app->fresh()
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
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to update application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


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
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
