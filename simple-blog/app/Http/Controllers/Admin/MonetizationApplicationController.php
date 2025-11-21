<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonetizationApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = \App\Models\MonetizationApplication::with(['user', 'reviewer'])
            ->orderByDesc('created_at');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $applications = $query->paginate(20);
        
        // Get counts for filter tabs
        $counts = [
            'all' => \App\Models\MonetizationApplication::count(),
            'pending' => \App\Models\MonetizationApplication::pending()->count(),
            'approved' => \App\Models\MonetizationApplication::approved()->count(),
            'rejected' => \App\Models\MonetizationApplication::rejected()->count(),
        ];
        
        return view('admin.monetization-applications.index', compact('applications', 'status', 'counts'));
    }

    public function show($id)
    {
        $application = \App\Models\MonetizationApplication::with(['user', 'reviewer'])
            ->findOrFail($id);
        
        return view('admin.monetization-applications.show', compact('application'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        $application = \App\Models\MonetizationApplication::findOrFail($id);
        
        // Check if already processed
        if ($application->status !== \App\Models\MonetizationApplication::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'This application has already been processed.');
        }
        
        // Update application
        $application->update([
            'status' => \App\Models\MonetizationApplication::STATUS_APPROVED,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        
        // Enable monetization for user
        $application->user->update([
            'monetization_enabled' => true,
        ]);
        
        return redirect()->route('admin.monetization-applications.index')
            ->with('success', 'Application approved successfully. User monetization has been enabled.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10|max:500',
        ], [
            'admin_notes.required' => 'Please provide a reason for rejection.',
            'admin_notes.min' => 'Rejection reason must be at least 10 characters.',
        ]);
        
        $application = \App\Models\MonetizationApplication::findOrFail($id);
        
        // Check if already processed
        if ($application->status !== \App\Models\MonetizationApplication::STATUS_PENDING) {
            return redirect()->back()
                ->with('error', 'This application has already been processed.');
        }
        
        // Update application
        $application->update([
            'status' => \App\Models\MonetizationApplication::STATUS_REJECTED,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        
        return redirect()->route('admin.monetization-applications.index')
            ->with('success', 'Application rejected successfully.');
    }
}
