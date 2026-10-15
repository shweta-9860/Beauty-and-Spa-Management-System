<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceVariation;
use Illuminate\Http\Request;

class ServiceVariationController extends Controller
{
    /**
     * Display all service variations.
     */
    public function index(Request $request)
    {
        $query = ServiceVariation::with('service');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Service filter
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        $variations = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $services = Service::orderBy('name')->get();

        return view(
            'admin.service_variations.index',
            compact('variations', 'services')
        );
    }

    /**
     * Show create form.
     */
    public function create(Request $request)
    {
        $services = Service::where('status', true)
            ->orderBy('name')
            ->get();

        $selectedService = null;

        if ($request->filled('service_id')) {
            $selectedService = Service::find($request->service_id);
        }

        return view(
            'admin.service_variations.create',
            compact('services', 'selectedService')
        );
    }

    /**
     * Store new variation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'name' => [
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
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        // Prevent duplicate variation for same service
        $exists = ServiceVariation::where(
            'service_id',
            $validated['service_id']
        )
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This variation already exists for the selected service.',
                ]);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceVariation::create($validated);

        return redirect()
            ->route('admin.service-variations.index')
            ->with(
                'success',
                'Service variation added successfully.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(ServiceVariation $serviceVariation)
    {
        $services = Service::orderBy('name')->get();

        return view(
            'admin.service_variations.edit',
            compact('serviceVariation', 'services')
        );
    }

    /**
     * Update variation.
     */
    public function update(
        Request $request,
        ServiceVariation $serviceVariation
    ) {
        $validated = $request->validate([
            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'name' => [
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
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        // Prevent duplicate variation
        $exists = ServiceVariation::where(
            'service_id',
            $validated['service_id']
        )
            ->where('name', $validated['name'])
            ->where('id', '!=', $serviceVariation->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'This variation already exists for the selected service.',
                ]);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $serviceVariation->update($validated);

        return redirect()
            ->route('admin.service-variations.index')
            ->with(
                'success',
                'Service variation updated successfully.'
            );
    }

    /**
     * Delete variation.
     */
    public function destroy(ServiceVariation $serviceVariation)
    {
        $serviceVariation->delete();

        return redirect()
            ->route('admin.service-variations.index')
            ->with(
                'success',
                'Service variation deleted successfully.'
            );
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(ServiceVariation $serviceVariation)
    {
        $serviceVariation->update([
            'status' => !$serviceVariation->status,
        ]);

        return redirect()
            ->route('admin.service-variations.index')
            ->with(
                'success',
                'Service variation status updated successfully.'
            );
    }
}

