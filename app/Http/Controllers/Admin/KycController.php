<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $page_title = 'Identity Verification (KYC)';

        // 1. Base Query with Relations
        $query = Kyc::with('user');

        // 2. Base Filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 3. Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search by user attributes
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('username', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                })
                    // Search by KYC attributes
                    ->orWhere('document_type', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        // 4. Pagination
        $pagination = getSetting('pagination');
        $kycs = $query->paginate($pagination)->appends($request->query());

        // 5. Statistics (based on all KYCs, not just the paginated result, optionally respecting user_id filter if present)
        $statsQuery = Kyc::query();
        if ($request->filled('user_id')) {
            $statsQuery->where('user_id', $request->user_id);
        }

        $totalSubmissions = (clone $statsQuery)->count();
        $pendingCount = (clone $statsQuery)->where('status', 'pending')->count();
        $approvedCount = (clone $statsQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $statsQuery)->where('status', 'rejected')->count();

        // 6. Graph Data: Status Distribution mapping
        $statusDistribution = [
            'labels' => ['Pending', 'Approved', 'Rejected'],
            'data' => [$pendingCount, $approvedCount, $rejectedCount],
            'colors' => ['#f59e0b', '#10b981', '#ef4444'] // Yellow, Green, Red
        ];

        $template = config('site.template');

        return view("templates.$template.blades.admin.kyc.index", compact(
            'page_title',
            'kycs',
            'totalSubmissions',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'statusDistribution'
        ));
    }

    public function viewKyc($id)
    {
        $kyc = Kyc::with('user')->findOrFail($id);
        $page_title = 'KYC Details - ' . $kyc->user->username;
        $template = config('site.template');

        return view("templates.$template.blades.admin.kyc.view", compact('page_title', 'kyc'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|nullable'
        ]);

        $kyc = Kyc::findOrFail($id);
        $user = $kyc->user;

        $kyc->status = $request->status;

        if ($request->status === 'rejected') {
            $kyc->rejection_reason = $request->rejection_reason;
        }

        $kyc->save();

        $kyc->refresh();

        $subject = $request->status === 'approved' ? 'Your KYC has been approved' : 'Your KYC has been rejected';
        sendKycEmail($subject, $kyc);
        $title = 'KYC Status Update';
        $body = $request->status === 'approved' ? 'Your KYC has been approved' : 'Your KYC has been rejected';
        recordNotificationMessage($user, $title, $body);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'KYC status updated successfully to ' . $request->status
            ]);
        }

        return back()->with('success', 'KYC status updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $kyc = Kyc::findOrFail($id);

        $kyc->delete();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'KYC record deleted successfully'
            ]);
        }

        return redirect()->route('admin.kyc.index')->with('success', 'KYC record deleted successfully');
    }
}
