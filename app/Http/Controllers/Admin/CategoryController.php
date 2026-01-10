<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = \App\Models\TicketCategory::all();
        $priorityColors = $this->getPriorityColors();
        return view('admin.categories.index', compact('categories', 'priorityColors'));
    }

    private function getPriorityColors()
    {
        $content = \App\Models\CmsContent::where('key', 'sla_priority_colors')->first();
        if (!$content) {
            return [
                'critical' => 'rose',
                'high' => 'amber',
                'normal' => 'indigo',
                'low' => 'emerald',
            ];
        }
        return json_decode($content->value, true) ?: [];
    }

    public function create()
    {
        $slaRules = \App\Models\SlaRule::all();
        return view('admin.categories.create', compact('slaRules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ticket_categories',
            'priority' => 'required|exists:sla_rules,priority',
        ]);

        \App\Models\TicketCategory::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(string $id)
    {
        $category = \App\Models\TicketCategory::findOrFail($id);
        $slaRules = \App\Models\SlaRule::all();
        return view('admin.categories.edit', compact('category', 'slaRules'));
    }

    public function update(Request $request, string $id)
    {
        $category = \App\Models\TicketCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', \Illuminate\Validation\Rule::unique('ticket_categories')->ignore($category->id)],
            'priority' => 'required|exists:sla_rules,priority',
        ]);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        \App\Models\TicketCategory::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
