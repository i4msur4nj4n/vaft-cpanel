<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $totalCapital = $projects->sum('capital');
        $totalReturns = $projects->sum('returns');
        $netRoi = $totalReturns - $totalCapital;

        return view('projects.index', compact('projects', 'totalCapital', 'totalReturns', 'netRoi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,paused',
            'capital' => 'required|numeric|min:0',
            'returns' => 'nullable|numeric|min:0',
        ]);

        Project::create($validated);
        AuditLog::record('CREATE', 'Created project: ' . $validated['name'] . ' with capital ' . $validated['capital'] . ' BDT');
        return redirect('/projects')->with('success', 'Project created successfully!');
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,paused',
            'capital' => 'required|numeric|min:0',
            'returns' => 'nullable|numeric|min:0',
        ]);

        $project->update($validated);
        AuditLog::record('UPDATE', 'Updated project: ' . $validated['name'] . ' (status: ' . $validated['status'] . ')');
        return redirect('/projects')->with('success', 'Project updated!');
    }

    public function destroy(Project $project)
    {
        $name = $project->name;
        $project->delete();
        AuditLog::record('DELETE', 'Deleted project: ' . $name);
        return redirect('/projects')->with('success', 'Project deleted!');
    }
}
