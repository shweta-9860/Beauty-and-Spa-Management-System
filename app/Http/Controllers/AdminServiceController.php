<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminServiceController extends Controller
{
    /**
     * Display all services
     */
    public function index(Request $request)
    {
        $query = Service::with('salon')
            ->withCount('variations');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Salon Filter
        if ($request->filled('salon_id')) {
            $query->where('salon_id', $request->salon_id);
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('status', 0);
            }
        }

        // Pagination
        $services = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Categories
        $categories = Category::orderBy('name')->get();

        // Active Salons
        $salons = Salon::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.services.index',
            compact(
                'services',
                'categories',
                'salons'
            )
        );
    }


    /**
     * Show create service form
     */
    public function create()
    {
        $salons = Salon::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.services.create',
            compact('salons')
        );
    }


    /**
     * Store new service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'salon_id' => [
                'required',
                'integer',
                Rule::exists('salons', 'id')
                    ->where('status', true),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'duration' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        Service::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Service added successfully.'
            );
    }


    /**
     * Show edit service form
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);

        $salons = Salon::where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.services.edit',
            compact(
                'service',
                'salons'
            )
        );
    }


    /**
     * Update existing service
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'salon_id' => [
                'required',
                'integer',
                Rule::exists('salons', 'id')
                    ->where('status', true),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'required',
                'string',
                'max:255',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'duration' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Service updated successfully.'
            );
    }


    /**
     * Delete service
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with(
                'success',
                'Service deleted successfully.'
            );
    }
}

