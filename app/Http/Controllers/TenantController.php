<?php

namespace App\Http\Controllers;

use Artisan;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
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
        return view('tenants._create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'domain_name' => ['required', 'string', 'alpha', 'between:4,10', 'max:255', 'unique:domains,domain'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Create the tenant
            $tenant = Tenant::create($validatedData);

            // Generate a valid database name
            // $uuid = (string) Str::uuid();
            // $databaseName = 'tenant_' . str_replace('-', '_', $uuid);

            // // Create tenant database
            // DB::statement("CREATE DATABASE `$databaseName`");

            // // Set tenant database configuration
            // config(['database.connections.tenant' => [
            //     'driver' => 'mysql',
            //     'host' => env('TENANT_DB_HOST', '127.0.0.1'),
            //     'port' => env('TENANT_DB_PORT', '3306'),
            //     'database' => $databaseName,
            //     'username' => env('TENANT_DB_USERNAME', 'irriion'),
            //     'password' => env('TENANT_DB_PASSWORD', '2}=DPm=P23t'),
            //     'charset' => 'utf8mb4',
            //     'collation' => 'utf8mb4_unicode_ci',
            //     'prefix' => '',
            //     'strict' => true,
            //     'engine' => null,
            // ]]);

            // Set the tenant context
            tenancy()->initialize($tenant);

            // Run tenant migrations
            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id]
            ]);

            // Create user associated with the tenant
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                // 'password' => Hash::make($validatedData['password']),
                'remember_token' => Str::random(60),
            ]);

            // Release the tenant context
            tenancy()->end();

            // Create domain for the tenant
            $tenant->domains()->create([
                'domain' => $validatedData['domain_name'] . '.' . config('app.domain')
            ]);

            return redirect()->route('tenants.index')->with('success', __('Tenant created successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('An error occurred: ') . $e->getMessage());
        }
    }

    public function edit($id)
    {
            $tenant    = Tenant::find($id);
            return view('tenants._edit', compact('tenant'));

    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            // 'domain_name' => ['required', 'string', 'alpha', 'between:4,10', 'max:255', 'unique:domains,domain'],
            // 'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $tenant = Tenant::findOrFail($id);

            tenancy()->initialize($tenant);

            $tenant->name = $validatedData['name'];
            $tenant->email = $validatedData['email'];
            // $tenant->domain_name = $validatedData['domain_name'];

            // Optionally update the password if provided
            if ($request->filled('password')) {
                $tenant->password = Hash::make($request->password);
            }

            // Find the associated user and update their information
            $user = User::where('email', $tenant->email)->first();

            if ($user) {
                $user->name = $validatedData['name'];
                $user->email = $validatedData['email'];
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                    $user->remember_token = Str::random(60);
                }
                $user->save();
            }

            // Save the tenant
            $tenant->save();

            // Release the tenant context
            tenancy()->end();

            return redirect()->route('tenants.index')->with('success', __('Tenant updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('An error occurred: ') . $e->getMessage());
        }
    }

    public function destroy($id)
    {
            $tenant    = Tenant::find($id);
            $tenant->delete();
            return redirect()->route('tenants.index')->with('success', __('Tenant deleted successfully'));

    }
}
