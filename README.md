* Create a MySQL database
* Download composer
* Copy `.env.example` file to `.env` .
* Open the console and cd your project root directory
* Run `composer install or php composer.phar install`
* Run `php artisan key:generate`
* Run `php artisan migrate`
* php artisan migrate:tenants
* php artisan migrate:rollback --batch 4
* Make Test Domain Like `central.test` 
* Change `APP_URL` to `http://central.test`in `.env`
* Run `php artisan db:Seed --class=TenantSeeder`

in cpanal tenant workable steps 
2}=DPm=P23t}

User: irriion
Database: irriion

User: irriion
Database: tenant_b07b0971-f830-4ee1-91d8-570ff0760278

on those two datatable has same user and password

and changes on .env(sample-.env.serverexample) files and tenancy or other domain changes in code

in local Imporsonate server error
http://pristm.preciseca.com:8000/

changes on _impersonate.blade.php
and changes on main db domain table subdomain name and in tenant db users record fill

This command will forcefully remove the .trash directory and all its contents, including any files or subdirectories it contains. Be cautious when using the -rf flags, as they can cause irreversible data loss if used incorrectly.

After removing the .trash directory, it will be permanently deleted from your server. Make sure that you don't need any files or data stored within this directory before proceeding with the deletion.

rm -rf /home/preciseca/.trash
rm -rf /home/preciseca/public_html/laraveltenanttallyconnector/.git


api through import
Step 1: Set Up Tenant Middleware
php
Copy code
// SetTenantConnection.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

class SetTenantConnection
{
    public function handle($request, Closure $next)
    {
        $tenantId = $request->header('Tenant-ID');

        if (!$tenantId) {
            return response()->json(['error' => 'Tenant ID not provided'], 400);
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        Log::info('Switching to tenant database: ' . $tenant->database_name);

        config(['database.connections.tenant.database' => $tenant->database_name]);
        DB::setDefaultConnection('tenant');

        return $next($request);
    }
}
Step 2: Create Route and Controller
php
Copy code
// routes/api.php

use App\Http\Controllers\LedgerController;

Route::post('/ledgers/import', [LedgerController::class, 'import']);
Step 3: Parse and Validate JSON Data
php
Copy code
// LedgerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Ledger;
use Carbon\Carbon;

class LedgerController extends Controller
{
    public function import(Request $request)
    {
        // Extract JSON data from request
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);

        // Validate JSON data structure and content
        // Implement your validation logic here

        // Example validation: Check if 'ledgers' key exists
        if (!isset($data['ledgers'])) {
            return response()->json(['error' => 'No ledger data found'], 400);
        }

        // Process and save ledger data
        // Implement your logic to save data to the tenant database
    }
}
Step 4: Save Data to Tenant Database
In the import method of LedgerController, implement the logic to save the validated JSON data to the appropriate tables in the tenant database using Eloquent models.

Step 5: Handle Errors
Implement error handling mechanisms in the import method to handle validation errors, database errors, or any other issues that may occur during the import process. Log errors for debugging purposes and return appropriate error responses to the client.

By following these steps, you should be able to set up JSON data import functionality in your multi-tenant Laravel application. Adjust the code according to your specific requirements and application architecture.
