<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketStatusController extends Controller
{
    public function index()
    {
        $statuses = TicketStatus::orderBy('order')->get();
        return view('admin.statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ticket_statuses,name',
            'color' => 'required|string|max:50',
        ]);

        TicketStatus::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '_'),
            'color' => $validated['color'],
            'order' => TicketStatus::count() + 1,
        ]);

        return redirect()->route('admin.statuses.index')->with('success', 'Status created successfully.');
    }

    public function edit(TicketStatus $status)
    {
        return view('admin.statuses.edit', compact('status'));
    }

    public function update(Request $request, TicketStatus $status)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ticket_statuses,name,' . $status->id,
            'color' => 'required|string|max:50',
        ]);

        $status->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'], '_'),
            'color' => $validated['color'],
        ]);

        return redirect()->route('admin.statuses.index')->with('success', 'Status updated successfully.');
    }

    public function destroy(TicketStatus $status)
    {
        if ($status->tickets()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete status because it is used by some tickets.');
        }

        $status->delete();
        return redirect()->route('admin.statuses.index')->with('success', 'Status deleted successfully.');
    }
}
