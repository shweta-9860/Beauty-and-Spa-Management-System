<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PackageController extends Controller
{
    /**
     * Display all packages.
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->salon_id) {
            return redirect()
                ->route('admin.dashboard')
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Automatically deactivate expired packages
        |--------------------------------------------------------------------------
        |
        | Package expiry is calculated using:
        | created_at + validity_days
        |
        | Example:
        | Created: 01 Sep
        | Validity: 30 days
        | Expiry: 01 Oct
        |
        */

        $packages = Package::where('salon_id', $user->salon_id)
            ->latest()
            ->get();

        foreach ($packages as $package) {

            $expiryDate = Carbon::parse($package->created_at)
                ->addDays((int) $package->validity_days);

            /*
            |--------------------------------------------------------------------------
            | If expiry date/time has passed
            |--------------------------------------------------------------------------
            */

            if (
                $package->status == 1 &&
                now()->greaterThanOrEqualTo($expiryDate)
            ) {
                $package->update([
                    'status' => 0,
                ]);

                // Update local object also
                $package->status = 0;
            }
        }

        return view(
            'admin.packages.index',
            compact('packages')
        );
    }


    /**
     * Show create package form.
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->salon_id) {
            return redirect()
                ->route('admin.packages.index')
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        $services = Service::where('salon_id', $user->salon_id)
            ->where('status', 1)
            ->with('variations')
            ->orderBy('name')
            ->get();

        return view(
            'admin.packages.create',
            compact('services')
        );
    }


    /**
     * Store a new package.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'validity_days' => [
                'required',
                'integer',
                'min:1',
            ],

            'total_uses' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'package_services' => [
                'required',
                'array',
                'min:1',
            ],

            'package_services.*.service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'package_services.*.service_variation_id' => [
                'nullable',
                'integer',
                'exists:service_variations,id',
            ],

            'package_services.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $user = auth()->user();

        if (!$user->salon_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        DB::transaction(function () use ($request, $user) {

            $package = Package::create([
                'salon_id' => $user->salon_id,
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'price' => $request->price,
                'validity_days' => $request->validity_days,
                'total_uses' => $request->total_uses,

                /*
                |--------------------------------------------------------------------------
                | Package Status
                |--------------------------------------------------------------------------
                */

                'status' => $request->has('status') ? 1 : 0,
            ]);

            foreach ($request->package_services as $item) {

                $service = Service::where('id', $item['service_id'])
                    ->where('salon_id', $user->salon_id)
                    ->first();

                if (!$service) {
                    continue;
                }

                $package->packageServices()->create([
                    'service_id' => $service->id,

                    'service_variation_id' =>
                        $item['service_variation_id'] ?? null,

                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Package created successfully.'
            );
    }


    /**
     * Display package details.
     */
    public function show(Package $package)
    {
        $this->checkPackageAccess($package);

        /*
        |--------------------------------------------------------------------------
        | Automatically deactivate if expired
        |--------------------------------------------------------------------------
        */

        $expiryDate = Carbon::parse($package->created_at)
            ->addDays((int) $package->validity_days);

        if (
            $package->status == 1 &&
            now()->greaterThanOrEqualTo($expiryDate)
        ) {
            $package->update([
                'status' => 0,
            ]);

            $package->status = 0;
        }

        $package->load([
            'packageServices.service',
            'packageServices.serviceVariation',
        ]);

        return view(
            'admin.packages.show',
            compact('package')
        );
    }


    /**
     * Show edit package form.
     */
    public function edit(Package $package)
    {
        $this->checkPackageAccess($package);

        /*
        |--------------------------------------------------------------------------
        | Automatically deactivate if expired
        |--------------------------------------------------------------------------
        */

        $expiryDate = Carbon::parse($package->created_at)
            ->addDays((int) $package->validity_days);

        if (
            $package->status == 1 &&
            now()->greaterThanOrEqualTo($expiryDate)
        ) {
            $package->update([
                'status' => 0,
            ]);

            $package->status = 0;
        }

        $user = auth()->user();

        if (!$user->salon_id) {
            return redirect()
                ->route('admin.packages.index')
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get salon services for dropdown
        |--------------------------------------------------------------------------
        */

        $services = Service::where('salon_id', $user->salon_id)
            ->where('status', 1)
            ->with('variations')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Load existing package services
        |--------------------------------------------------------------------------
        */

        $package->load([
            'packageServices.service',
            'packageServices.serviceVariation',
        ]);

        return view(
            'admin.packages.edit',
            compact(
                'package',
                'services'
            )
        );
    }


    /**
     * Update package.
     */
    public function update(
        Request $request,
        Package $package
    ) {
        $this->checkPackageAccess($package);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'validity_days' => [
                'required',
                'integer',
                'min:1',
            ],

            'total_uses' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'package_services' => [
                'required',
                'array',
                'min:1',
            ],

            'package_services.*.service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'package_services.*.service_variation_id' => [
                'nullable',
                'integer',
                'exists:service_variations,id',
            ],

            'package_services.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $user = auth()->user();

        if (!$user->salon_id) {
            return back()
                ->withInput()
                ->withErrors([
                    'salon_id' => 'Your account is not linked to a salon.'
                ]);
        }

        DB::transaction(function () use ($request, $package, $user) {

            /*
            |--------------------------------------------------------------------------
            | Check expiry before saving status
            |--------------------------------------------------------------------------
            */

            $expiryDate = Carbon::parse($package->created_at)
                ->addDays((int) $request->validity_days);

            $newStatus = $request->has('status') ? 1 : 0;

            /*
            |--------------------------------------------------------------------------
            | Expired package cannot remain active
            |--------------------------------------------------------------------------
            */

            if (now()->greaterThanOrEqualTo($expiryDate)) {
                $newStatus = 0;
            }

            $package->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'price' => $request->price,
                'validity_days' => $request->validity_days,
                'total_uses' => $request->total_uses,
                'status' => $newStatus,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Remove old services
            |--------------------------------------------------------------------------
            */

            $package->packageServices()->delete();

            /*
            |--------------------------------------------------------------------------
            | Add updated services
            |--------------------------------------------------------------------------
            */

            foreach ($request->package_services as $item) {

                $service = Service::where('id', $item['service_id'])
                    ->where('salon_id', $user->salon_id)
                    ->first();

                if (!$service) {
                    continue;
                }

                $package->packageServices()->create([
                    'service_id' => $service->id,

                    'service_variation_id' =>
                        $item['service_variation_id'] ?? null,

                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Package updated successfully.'
            );
    }


    /**
     * Delete package.
     */
    public function destroy(Package $package)
    {
        $this->checkPackageAccess($package);

        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with(
                'success',
                'Package deleted successfully.'
            );
    }


    /**
     * Check package belongs to logged-in user's salon.
     */
    private function checkPackageAccess(Package $package)
    {
        if (
            $package->salon_id !== auth()->user()->salon_id
        ) {
            abort(
                403,
                'Unauthorized access to this package.'
            );
        }
    }
}