<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Municipality names for admin/user assignment (DB first, then static fallback).
     *
     * @return array<int, string>
     */
    private function municipalityOptions(): array
    {
        $fromDb = Municipality::query()
            ->withoutTrashed()
            ->orderBy('name')
            ->pluck('name')
            ->map(fn ($n) => trim((string) $n))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($fromDb !== []) {
            return $fromDb;
        }

        return array_values(array_unique(array_map('trim', User::getMunicipalities())));
    }

    /**
     * List all active (non-archived) users.
     */
    public function index()
    {
        $users = User::withoutTrashed()->orderBy('created_at', 'desc')->get();
        $roles = User::getRoles();
        $municipalities = $this->municipalityOptions();

        return view('superadmin.users', compact('users', 'roles', 'municipalities'));
    }

    /**
     * Create a new user.
     */
    public function store(Request $request)
    {
        $allowedMunis = $this->municipalityOptions();

        $muniRules = ['nullable', 'string', 'max:100'];
        if (in_array($request->role, ['admin', 'user'], true)) {
            $muniRules[] = 'required';
            if ($allowedMunis !== []) {
                $muniRules[] = Rule::in($allowedMunis);
            }
        }

        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username',
            'email'        => 'required|string|email|max:100|unique:users,email',
            'password'     => 'required|string|min:8',
            'full_name'    => 'required|string|max:100',
            'gender'       => 'nullable|string|max:30',
            'role'         => 'required|in:super_admin,admin,user',
            'municipality' => $muniRules,
            'status'       => 'required|in:active,inactive',
        ]);

        $municipality = $request->role === 'super_admin'
            ? null
            : trim((string) $request->municipality);

        $gender = $request->filled('gender') ? trim((string) $request->gender) : null;

        User::create([
            'username'          => trim($request->username),
            'email'             => trim($request->email),
            'password'          => $request->password,
            'full_name'         => trim($request->full_name),
            'gender'            => $gender !== '' ? $gender : null,
            'role'              => $request->role,
            'municipality'      => $municipality !== '' ? $municipality : null,
            'status'            => $request->status,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User created successfully!');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $allowedMunis = $this->municipalityOptions();

        $muniRules = ['nullable', 'string', 'max:100'];
        if (in_array($request->role, ['admin', 'user'], true)) {
            $muniRules[] = 'required';
            if ($allowedMunis !== []) {
                $muniRules[] = Rule::in($allowedMunis);
            }
        }

        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username,' . $id,
            'email'        => 'required|string|email|max:100|unique:users,email,' . $id,
            'full_name'    => 'required|string|max:100',
            'gender'       => 'nullable|string|max:30',
            'role'         => 'required|in:super_admin,admin,user',
            'municipality' => $muniRules,
            'status'       => 'required|in:active,inactive',
        ]);

        $municipality = $request->role === 'super_admin'
            ? null
            : trim((string) $request->municipality);

        $data = [
            'username'     => trim($request->username),
            'email'        => trim($request->email),
            'full_name'    => trim($request->full_name),
            'role'         => $request->role,
            'municipality' => $municipality !== '' ? $municipality : null,
            'status'       => $request->status,
        ];

        if ($request->has('gender')) {
            $g = trim((string) $request->gender);
            $data['gender'] = $g !== '' ? $g : null;
        }

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('superadmin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Archive (soft-delete) a user.
     */
    public function archive(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot archive your own account.'], 403);
            }
            return redirect()->route('superadmin.users')->with('error', 'You cannot archive your own account!');
        }

        $user->archived_by = auth()->id();
        $user->save();
        $user->delete(); // soft delete

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => "User \"{$user->full_name}\" has been archived."]);
        }

        return redirect()->route('superadmin.users')->with('success', "User \"{$user->full_name}\" has been archived.");
    }

    /**
     * Return archived users as JSON (filtered by current super admin).
     */
    public function getArchivedUsers(Request $request)
    {
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        $archivedUsers = User::onlyTrashed()
            ->where('archived_by', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->get()
            ->toArray();
        
        // Output raw JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array_values($archivedUsers));
        die();
    }
    /**
     * Restore an archived user.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json(['success' => true, 'message' => "User \"{$user->full_name}\" has been restored."]);
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $name = $user->full_name;
        $user->forceDelete();

        return response()->json(['success' => true, 'message' => "User \"{$name}\" permanently deleted."]);
    }
}