<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slas = \App\Models\SlaRule::all();
        $colors = $this->getPriorityColors();
        return view('admin.slas.index', compact('slas', 'colors'));
    }

    public function create()
    {
        return view('admin.slas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'priority' => 'required|string|unique:sla_rules,priority',
            'response_time_minutes' => 'required|integer|min:1',
            'resolution_time_minutes' => 'required|integer|min:1',
            'color' => 'required|string|max:50',
        ]);

        \App\Models\SlaRule::create([
            'priority' => $validated['priority'],
            'response_time_minutes' => $validated['response_time_minutes'],
            'resolution_time_minutes' => $validated['resolution_time_minutes'],
        ]);

        // Update color mapping
        $colors = $this->getPriorityColors();
        $colors[$validated['priority']] = $validated['color'];
        $this->setPriorityColors($colors);

        return redirect()->route('admin.slas.index')->with('success', 'SLA Rule created successfully.');
    }

    public function edit(string $id)
    {
        $sla = \App\Models\SlaRule::findOrFail($id);
        $colors = $this->getPriorityColors();
        $currentColor = $colors[$sla->priority] ?? 'gray';
        return view('admin.slas.edit', compact('sla', 'currentColor'));
    }

    public function update(Request $request, string $id)
    {
        $sla = \App\Models\SlaRule::findOrFail($id);
        $validated = $request->validate([
            'priority' => ['required', 'string', \Illuminate\Validation\Rule::unique('sla_rules')->ignore($sla->id)],
            'response_time_minutes' => 'required|integer|min:1',
            'resolution_time_minutes' => 'required|integer|min:1',
            'color' => 'required|string|max:50',
        ]);

        $oldPriority = $sla->priority;
        $sla->update($validated);

        // Update color mapping
        $colors = $this->getPriorityColors();
        if ($oldPriority !== $validated['priority']) {
            unset($colors[$oldPriority]);
        }
        $colors[$validated['priority']] = $validated['color'];
        $this->setPriorityColors($colors);

        return redirect()->route('admin.slas.index')->with('success', 'SLA Rule updated successfully.');
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

    private function setPriorityColors(array $colors)
    {
        \App\Models\CmsContent::updateOrCreate(
            ['key' => 'sla_priority_colors'],
            [
                'value' => json_encode($colors),
                'label' => 'SLA Priority Colors Map',
                'type' => 'json'
            ]
        );
    }

    public function destroy(string $id)
    {
        \App\Models\SlaRule::findOrFail($id)->delete();
        return redirect()->route('admin.slas.index')->with('success', 'SLA Rule deleted successfully.');
    }
}
