<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    /**
     * Display list of custom order requests with metrics and filtering
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $search = trim($request->query('search', ''));

        $query = CustomOrderRequest::query();

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('selected_palette', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(15)->appends($request->all());

        // Overview metrics
        $metrics = [
            'total' => CustomOrderRequest::count(),
            'pending' => CustomOrderRequest::where('status', 'pending')->count(),
            'in_review' => CustomOrderRequest::where('status', 'in_review')->count(),
            'quoted' => CustomOrderRequest::where('status', 'quoted')->count(),
            'in_progress' => CustomOrderRequest::where('status', 'in_progress')->count(),
            'completed' => CustomOrderRequest::where('status', 'completed')->count(),
        ];

        return view('admin.custom_orders.index', compact('requests', 'metrics', 'status', 'search'));
    }

    /**
     * Retrieve single request details via JSON
     */
    public function show($id): JsonResponse
    {
        $order = CustomOrderRequest::with('customer')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Update request status, quoted price, and artisan notes
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in_review,quoted,approved,in_progress,completed,rejected',
            'quoted_price' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $order = CustomOrderRequest::findOrFail($id);
        $order->status = $request->input('status');
        if ($request->has('quoted_price')) {
            $order->quoted_price = $request->input('quoted_price') !== '' ? (float)$request->input('quoted_price') : null;
        }
        if ($request->has('admin_notes')) {
            $order->admin_notes = $request->input('admin_notes');
        }
        $order->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Custom order #{$order->reference_id} updated successfully!",
                'data' => $order,
            ]);
        }

        return redirect()->back()->with('success', "Custom order #{$order->reference_id} updated successfully!");
    }

    /**
     * Delete a custom order request
     */
    public function destroy($id)
    {
        $order = CustomOrderRequest::findOrFail($id);
        $ref = $order->reference_id;
        $order->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Custom order request #{$ref} deleted.",
            ]);
        }

        return redirect()->back()->with('success', "Custom order request #{$ref} deleted.");
    }
}
