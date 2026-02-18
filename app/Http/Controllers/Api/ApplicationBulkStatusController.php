<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicationBulkStatusController extends Controller
{
    
    public function update_status(Request $request)
    {
        $mainStatus = ['waiting', 'created', 'uploaded', 'verified', 'rejected'];

        try {
            $validated = $request->validate([
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', 'distinct', 'exists:applications,id'],

                'status' => ['sometimes', 'string', Rule::in($mainStatus)],
                // 'site_status' => ['sometimes', 'string', Rule::in($subStatus)],
                // 'privacy_status' => ['sometimes', 'string', Rule::in($subStatus)],
                // 'delete_status' => ['sometimes', 'string', Rule::in($subStatus)],
            ]);

            $updateData = collect($validated)
                ->only('status')
                ->toArray();

            if (count($updateData) === 0) {
                throw ValidationException::withMessages([
                    'status' => ['Provide at least one field to update: status, site_status, privacy_status, delete_status.'],
                ]);
            }

            $ids = $validated['ids'];

            $updated = DB::transaction(function () use ($ids, $updateData) {
                return Application::query()
                    ->whereIn('id', $ids)
                    ->update(array_merge($updateData, ['updated_at' => now()]));
            });

            return response()->json([
                'success' => true,
                'message' => 'Applications updated successfully',
                'updated_count' => $updated,
                'ids' => $ids,
                'changes' => $updateData,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update applications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_site_status(Request $request)
    {
            $subStatus = ['waiting', 'created', 'uploaded', 'verified'];

            try {
                $validated = $request->validate([
                    'ids' => ['required', 'array', 'min:1'],
                    'ids.*' => ['integer', 'distinct', 'exists:applications,id'],

                    'site_status' => ['sometimes', 'string', Rule::in($subStatus)],
                ]);

                $updateData = collect($validated)
                    ->only('site_status')
                    ->toArray();

                if (count($updateData) === 0) {
                    throw ValidationException::withMessages([
                        'site_status' => ['Provide at least one field to update: status, site_status, privacy_status, delete_status.'],
                    ]);
                }

                $ids = $validated['ids'];

                $updated = DB::transaction(function () use ($ids, $updateData) {
                    return Application::query()
                        ->whereIn('id', $ids)
                        ->update(array_merge($updateData, ['updated_at' => now()]));
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Applications updated successfully',
                    'updated_count' => $updated,
                    'ids' => $ids,
                    'changes' => $updateData,
                ], 200);
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $e->errors(),
                ], 422);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update applications',
                    'error' => $e->getMessage(),
                ], 500);
            }
    }

    public function update_privacy_status(Request $request)
    {
        $subStatus = ['waiting', 'created', 'uploaded', 'verified'];

        try {
            $validated = $request->validate([
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', 'distinct', 'exists:applications,id'],

                'privacy_status' => ['sometimes', 'string', Rule::in($subStatus)],
                // 'delete_status' => ['sometimes', 'string', Rule::in($subStatus)],
            ]);

            $updateData = collect($validated)
            ->only('privacy_status')
            ->toArray();

            if (count($updateData) === 0) {
                throw ValidationException::withMessages([
                    'privacy_status' => ['Provide at least one field to update: status, site_status, privacy_status, delete_status.'],
                ]);
            }

            $ids = $validated['ids'];

            $updated = DB::transaction(function () use ($ids, $updateData) {
                return Application::query()
                    ->whereIn('id', $ids)
                    ->update(array_merge($updateData, ['updated_at' => now()]));
            });

            return response()->json([
                'success' => true,
                'message' => 'Applications updated successfully',
                'updated_count' => $updated,
                'ids' => $ids,
                'changes' => $updateData,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update applications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_delete_status(Request $request)
    {
        $subStatus = ['waiting', 'created', 'uploaded', 'verified'];

        try {
            $validated = $request->validate([
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', 'distinct', 'exists:applications,id'],

                'delete_status' => ['sometimes', 'string', Rule::in($subStatus)],
            ]);
            $updateData = collect($validated)
            ->only('delete_status')
            ->toArray();

            if (count($updateData) === 0) {
                throw ValidationException::withMessages([
                    'delete_status' => ['Provide at least one field to update: status, site_status, privacy_status, delete_status.'],
                ]);
            }

            $ids = $validated['ids'];

            $updated = DB::transaction(function () use ($ids, $updateData) {
                return Application::query()
                    ->whereIn('id', $ids)
                    ->update(array_merge($updateData, ['updated_at' => now()]));
            });

            return response()->json([
                'success' => true,
                'message' => 'Applications updated successfully',
                'updated_count' => $updated,
                'ids' => $ids,
                'changes' => $updateData,
            ], 200);
        }
        catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update applications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', 'distinct', 'exists:applications,id'],
            ]);

            $ids = $validated['ids'];

            $deleted = DB::transaction(function () use ($ids) {
                return Application::query()
                    ->whereIn('id', $ids)
                    ->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Applications deleted successfully',
                'deleted_count' => $deleted,
                'ids' => $ids,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete applications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

