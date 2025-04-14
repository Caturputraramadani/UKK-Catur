<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.user', compact('users'));
    }

    public function save(Request $request, $id = null)
    {

        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|in:admin,employee',
        ]);

        if ($id) {
            $user = User::findOrFail($id);
            $message = "User updated successfully";
        } else {
            $user = new User();
            $message = "New User created successfully";
        }

        $user->email = $request->email;
        $user->password = $request->password;
        $user->name = $request->name;
        $user->role = $request->role;

        $user->save();

        return redirect()->route('users.index')->with('success', $message);
    }

    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json([
                'error' => 'User not found.'
            ], 404);
        }

        if ($user->sales()->exists()) {
            return response()->json([
                'error' => 'Cannot delete user because they have associated sales records.'
            ], 422);
        }

        try {
            $user->delete();
            return response()->json([
                'success' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel()
    {
        return Excel::download(new UsersExport, 'users_export_' . date('Ymd_His') . '.xlsx');
    }


}
