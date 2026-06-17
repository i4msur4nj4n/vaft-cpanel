<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\UserPermission;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'users');
        $users = User::orderBy('created_at', 'desc')->get();
        $modules = UserPermission::modules();
        return view('admin.index', compact('users', 'tab', 'modules'));
    }

    public function toggleRole(User $user)
    {
        $user->update(['role' => $user->role === 'admin' ? 'user' : 'admin']);
        AuditLog::record('UPDATE', 'Toggled user role for: ' . $user->name . ' to ' . $user->role);
        return redirect('/admin-panel')->with('success', 'User role updated!');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }
        $user->save();

        AuditLog::record('UPDATE', 'Updated user credentials for: ' . $user->name . (!empty($validated['password']) ? ' (password reset)' : ''));
        return redirect('/admin-panel')->with('success', 'User credentials updated!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect('/admin-panel')->with('error', 'Cannot delete yourself!');
        }
        $name = $user->name;
        $user->delete();
        AuditLog::record('DELETE', "Permanently deleted user '" . $name . "' and purged all corresponding transaction history.");
        return redirect('/admin-panel')->with('success', 'User deleted!');
    }

    public function getPermissions(User $user)
    {
        $permissions = UserPermission::forUser($user->id);
        return response()->json([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role],
            'permissions' => $permissions,
        ]);
    }

    public function savePermissions(Request $request, User $user)
    {
        $permissions = $request->input('permissions', []);
        $modules = UserPermission::modules();

        foreach ($modules as $mod) {
            $key = $mod['key'];
            $perm = $permissions[$key] ?? [];

            UserPermission::updateOrCreate(
                ['user_id' => $user->id, 'module' => $key],
                [
                    'can_view' => !empty($perm['view']),
                    'can_create' => !empty($perm['create']),
                    'can_edit' => !empty($perm['edit']),
                    'can_delete' => !empty($perm['delete']),
                ]
            );
        }

        AuditLog::record('UPDATE', 'Updated module permissions for user: ' . $user->name);
        return redirect('/admin-panel')->with('success', 'Permissions updated for ' . $user->name . '!');
    }
}
