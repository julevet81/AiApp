<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users (with roles).
     */
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();

        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users,
        ], 200);
    }

    public function show(User $user)
    {
        $user->load('roles', 'permissions');

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user,
        ], 200);
    }

    // إنشاء مستخدم جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'nullable|in:active,inactive',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم إنشاء المستخدم بنجاح',
            'data' => $user,
            'Token' => $token
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'nullable|in:active,inactive',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // إضافة تحديث الحالة
        if ($request->has('status')) {
            $user->status = $request->status;
        }

        // تحديث كلمة المرور إذا تم إرسالها
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // تحديث الأدوار إذا تم إرسالها
        if (array_key_exists('roles', $request->all())) {
            $user->syncRoles($request->roles ?? []);
        }

        $user->load('roles', 'permissions');

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }

    /**
     * Sync roles for the specified user.
     */
    public function syncRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
        ]);

        $user->syncRoles($request->roles);
        $user->load('roles', 'permissions');

        return response()->json([
            'message' => 'User roles updated successfully',
            'data' => $user,
        ], 200);
    }
}
