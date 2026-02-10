<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Auth as SupportFacadesAuth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // عرض جميع المستخدمين
    public function index()
    {

        $users = User::all();
        return response()->json([
            'message' => 'تم جلب جميع المستخدمين بنجاح',
            'data' => $users
        ], 200);
    }

    // عرض مستخدم واحد
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'message' => 'تم جلب بيانات المستخدم بنجاح',
            'data' => $user
        ], 200);
    }

    // إنشاء مستخدم جديد
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم إنشاء المستخدم بنجاح',
            'data' => $user,
            'Token' => $token
        ], 201);
    }

    // تحديث بيانات المستخدم
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'تم تحديث المستخدم بنجاح',
            'data' => $user
        ], 200);
    }

    // حذف المستخدم
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'تم حذف المستخدم بنجاح'
        ], 200);
    }
}
