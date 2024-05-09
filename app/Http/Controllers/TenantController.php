<?php

namespace App\Http\Controllers;

use Rules\Password;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\DataTables\TenantDataTable;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index(TenantDataTable $dataTable)
    {
        return $dataTable->render('tenants.index');
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'domain_name' => ['required', 'string', 'alpha', 'between:4,10', 'max:255', 'unique:domains,domain'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Set the tenant context
            $tenant = Tenant::create($validatedData);
            tenancy()->initialize($tenant);

            // Create user associated with the tenant
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Release the tenant context
            tenancy()->end();

            // Create domain for the tenant
            $tenant->domains()->create([
                'domain' => $validatedData['domain_name'].'.'.config('app.domain')
            ]);

            return redirect()->route('tenants.index')->with('success', __('Tenant created successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('The domain name is already occupied by another tenant. Please choose a different domain name.'));
        }
    }


    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
    //         'domain_name' => ['required', 'string', 'alpha', 'between:4,10', 'max:255', 'unique:domains,domain'],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     // Set the tenant context
    //     $tenant = Tenant::create($validatedData);
    //     tenancy()->initialize($tenant);

    //     // Create user associated with the tenant
    //     $user = User::create([
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'password' => Hash::make($validatedData['password']),
    //     ]);

    //     // Release the tenant context
    //     tenancy()->end();

    //     // Create domain for the tenant
    //     $tenant->domains()->create([
    //         'domain' => $validatedData['domain_name'].'.'.config('app.domain')
    //     ]);

    //     return redirect()->route('tenants.index')->with('success', __('Tenant created successfully.'));
    // }



    // public function store(Request $request)
    // {
    //     //dd($request->all());
    //     $validatedData = $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
    //         'domain_name' => 'required|string|max:255|unique:domains,domain',
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $tenant = Tenant::create($validatedData);

    //     $tenant->domains()->create([
    //         'domain' => $validatedData['domain_name'].'.'.config('app.domain')
    //     ]);


    //     return redirect()->route('tenants.index');

    // }
}
