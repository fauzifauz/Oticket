<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::withCount('tickets');
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
                
                // For email, we try exact match with encrypted value since LIKE doesn't work
                $encryptedSearch = \App\Casts\DeterministicEncrypted::encryptForSearch($search);
                $q->orWhere('email', $encryptedSearch);
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', (bool)$request->status);
        }

        $type = $request->get('type', 'employee');
        if ($type === 'staff') {
            $query->whereIn('role', ['admin', 'support']);
        } else {
            $query->where('role', 'user');
        }

        $users = $query->orderBy('id', 'asc')->paginate(10);
        
        $stats = [
            'total' => User::count(),
            'employees' => User::where('role', 'user')->count(),
            'staff' => User::whereIn('role', ['admin', 'support'])->count(),
            'pending' => User::where('status', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'type', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,support,user',
            'status' => 'boolean',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $encryptedEmail = \App\Casts\DeterministicEncrypted::encryptForSearch($request->email);
        if (User::where('email', $encryptedEmail)->exists()) {
            return redirect()->back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status ?? false,
            'department' => $request->department,
            'phone' => $request->phone,
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_USER',
            'description' => 'Created user: ' . $request->name . ' (' . $request->role . ')',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($request->has('status') && count($request->all()) <= 3) { 
            $user->update(['status' => $request->status]);
            $msg = $request->status ? 'User approved successfully.' : 'User deactivated successfully.';
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE_USER_STATUS',
                'description' => $msg . ': ' . $user->name,
            ]);

            if ($request->status) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\AccountApproved($user));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Approval email failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('admin.users.index')->with('success', $msg);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|in:admin,support,user',
            'status' => 'boolean',
            'password' => 'nullable|string|min:8',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $encryptedEmail = \App\Casts\DeterministicEncrypted::encryptForSearch($request->email);
        if (User::where('email', $encryptedEmail)->where('id', '!=', $user->id)->exists()) {
            return redirect()->back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status ?? false,
            'department' => $request->department,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $oldStatusValue = $user->status;
        $user->update($data);

        // Specific Logging for Status Change within main form
        if ($oldStatusValue != ($request->status ?? false)) {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE_USER_STATUS',
                'description' => ($request->status ? 'Approved' : 'Deactivated') . ' user from profile: ' . $user->name,
            ]);

            // Notify user on approval
            if ($request->status) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\AccountApproved($user));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Approval email failed: ' . $e->getMessage());
                }
            }
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_USER',
            'description' => 'Updated user details for: ' . $user->name . ' (' . $user->role . ')',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return redirect()->back()->with('error', 'Cannot delete the last admin.');
        }
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE',
            'description' => 'Deleted user: ' . $user->name,
        ]);

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function tickets(string $id)
    {
        $user = User::with(['tickets' => function($query) {
            $query->with(['category', 'support'])->latest();
        }])->findOrFail($id);
        
        return view('admin.users.tickets', compact('user'));
    }

    public function export()
    {
        $users = User::all();
        $filename = "users_export_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Role', 'Department', 'Phone', 'Status', 'Created At'];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->department,
                    $user->phone,
                    $user->status ? 'Active' : 'Inactive',
                    $user->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $users = User::withCount('tickets')->orderBy('id', 'asc')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.users.pdf', compact('users'));
        return $pdf->download('users_report_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
