<?php

namespace App\Http\Controllers\App;


use Carbon\Carbon;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\DataTables\App\JournalDataTable;
use App\DataTables\App\SalePurchaseDataTable;
use App\DataTables\App\LedgerDataTable;
use App\DataTables\App\BankDataTable;
use App\DataTables\App\ItemDataTable;

use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Request;
// use Illuminate\Support\Facades\Validator; 


use App\Http\Controllers\Controller;

class ExcelImportController extends Controller
{
    protected $states = [
        '01' => 'JAMMU AND KASHMIR',
        '02' => 'HIMACHAL PRADESH',
        '03' => 'PUNJAB',
        '04' => 'CHANDIGARH',
        '05' => 'UTTARAKHAND',
        '06' => 'HARYANA',
        '07' => 'DELHI',
        '08' => 'RAJASTHAN',
        '09' => 'UTTAR PRADESH',
        '10' => 'BIHAR',
        '11' => 'SIKKIM',
        '12' => 'ARUNACHAL PRADESH',
        '13' => 'NAGALAND',
        '14' => 'MANIPUR',
        '15' => 'MIZORAM',
        '16' => 'TRIPURA',
        '17' => 'MEGHALAYA',
        '18' => 'ASSAM',
        '19' => 'WEST BENGAL',
        '20' => 'JHARKHAND',
        '21' => 'ODISHA',
        '22' => 'CHATTISGARH',
        '23' => 'MADHYA PRADESH',
        '24' => 'GUJARAT',
        '25' => 'CHATTISGARH',
        '26' => 'DADRA AND NAGAR HAVELI AND DAMAN AND DIU (NEWLY MERGED UT)',
        '27' => 'MAHARASHTRA',
        '28' => 'ANDHRA PRADESH(BEFORE DIVISION)',
        '29' => 'KARNATAKA',
        '30' => 'GOA',
        '31' => 'LAKSHADWEEP',
        '32' => 'KERALA',
        '33' => 'TAMIL NADU',
        '34' => 'PUDUCHERRY',
        '35' => 'ANDAMAN AND NICOBAR ISLANDS',
        '36' => 'TELANGANA',
        '37' => 'ANDHRA PRADESH (NEWLY ADDED)',
        '97' => 'OTHER TERRITORY',
        '99' => 'CENTRE JURISDICTION',
    ];

    public function index()
    {
        return view('app.excelImport.index');
    }

    public function ledgerCreate()
    {
        return view('app.excelImport._ledger-create');
    }

    private function getTenantIdFromDomain($domain)
    {
        $subdomain = explode('.', $domain)[0];
        return $subdomain;
    }
    public function ledgerImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);
    
        $file = $request->file('file');
    
        $spreadsheet = IOFactory::load($file);
    
        $worksheet = $spreadsheet->getActiveSheet();
    
        $dataArray = $worksheet->toArray(null, true, true, true);
    
        $headings = array_shift($dataArray);
    
        $records = [];
    
        foreach ($dataArray as $row) {
            $record = array_combine($headings, $row);
            $records[] = $record;
        }
    
        $json_data = json_encode($records);
    
        $domain = $request->getHost();
        $tenantId = $this->getTenantIdFromDomain($domain);
    
        $tenantDirectory = 'app/' . $tenantId;
    
        if (!Storage::exists($tenantDirectory)) {
            Storage::makeDirectory($tenantDirectory);
        }
    
        $json_file_path = storage_path('app/' . $tenantDirectory . '/' . $file->getClientOriginalName() . '.json');
    
        $jsonData = file_put_contents($json_file_path, $json_data);
    
        $jsonData = file_get_contents($json_file_path);
        $data = json_decode($jsonData, true);
    
        $existingPartyNames = Ledger::pluck('party_name')->toArray();
    
        foreach ($data as $entry) {
    
            if (array_key_exists('tags', $entry)) {
                $tags = $entry['tags'];
            } else {
                // Set default value for 'tags' if not provided in Excel
                $tags = 'Excel';
            }
    
            // Trim leading and trailing spaces from party name
            $partyName = trim($entry['Party Name']);
    
            // Check if party name is empty after trimming
            if ($partyName === '') {
                return redirect()->back()->with('error', 'Party Name is required.');
            }
    
            // Check if party name has leading or trailing spaces
            if ($entry['Party Name'] !== $partyName) {
                return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the party name.');
            }
    
             // Check if party name already exists in the database
             if (in_array($partyName, $existingPartyNames)) {
                return redirect()->back()->with('error', 'Party Name "' . $partyName . '" already exists.');
            }
    
            // Trim leading and trailing spaces from party name
            $groupName = trim($entry['Group Name']);
    
            // Check if party name is empty after trimming
            if ($groupName === '') {
                return redirect()->back()->with('error', 'Group Name is required.');
            }
    
            // Check if party name has leading or trailing spaces
            if ($entry['Group Name'] !== $groupName) {
                return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the group name.');
            }
    
            // Check if applicable_date is empty
            if (empty($entry['Applicable Date'])) {
                // Return error response for empty applicable_date
                return redirect()->back()->with('error', 'Applicable Date is required.');
            }
    
            // Validate applicable_date format
            try {
                $applicableDate = Carbon::createFromFormat('d/m/Y', $entry['Applicable Date']);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Invalid date format. Date must be in DD/MM/YYYY format.');
            }
    
            // Check if gst_reg_type is provided
            if (empty($entry['GST Registration Type'])) {
                return redirect()->back()->with('error', 'GST Registration Type is required.');
            }
    
            // Check if gst_in is provided
            if (empty($entry['GSTIN/UIN'])) {
                return redirect()->back()->with('error', 'GSTIN/UIN is required.');
            }
    
            // Validate GSTIN format
            if (!preg_match("/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]){1}?$/", $entry['GSTIN/UIN'])) {
                return redirect()->back()->with('error', 'Invalid GSTIN/UIN format.');
            }
    
            // Get state code from the list of states
            $stateCode = array_search(strtoupper($entry['State']), array_map('strtoupper', $this->states));
            if ($stateCode === false) {
                return redirect()->back()->with('error', 'Invalid state code.');
            }
    
            // Create ledger record if all validations pass
            Ledger::create([
                'party_name' => $partyName,
                'alias' => $entry['Alias'],
                'group_name' => $groupName,
                'credit_period' => $entry['Credit Period'],
                'buyer_name' => $entry['Buyer/Mailing Name'],
                'address1' => $entry['Address 1'],
                'address2' => $entry['Address 2'],
                'address3' => $entry['Address 3'],
                'country' => $entry['Country'],
                'state' => $stateCode, // Save state code instead of state name
                'pincode' => $entry['Pincode'],
                'gst_in' => $entry['GSTIN/UIN'],
                'gst_reg_type' => $entry['GST Registration Type'],
                'opening_balance' => $entry['Opening Balance DR/CR'],
                'applicable_date' => $applicableDate->toDateString(),
                'tags' => $tags,
            ]);
    
        }
    
        // If everything is successful, redirect with success message
        return redirect()->route('excelImport.ledgers.show')->with('success', __('Ledger Data Save Successfully.'));
    }
    

    // public function ledgerImport(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file'
    //     ]);

    //     $file = $request->file('file');

    //     $spreadsheet = IOFactory::load($file);

    //     $worksheet = $spreadsheet->getActiveSheet();

    //     $dataArray = $worksheet->toArray(null, true, true, true);

    //     $headings = array_shift($dataArray);

    //     $records = [];

    //     foreach ($dataArray as $row) {
    //         $record = array_combine($headings, $row);
    //         $records[] = $record;
    //     }

    //     $json_data = json_encode($records);

    //     $domain = $request->getHost();
    //     $tenantId = $this->getTenantIdFromDomain($domain);

    //     $tenantDirectory = 'app/' . $tenantId;

    //     if (!Storage::exists($tenantDirectory)) {
    //         Storage::makeDirectory($tenantDirectory);
    //     }
    

    //     $json_file_path = storage_path('app/json-import' . $tenantDirectory . '/' . $file->getClientOriginalName() . '.json');

    //     $jsonData = file_put_contents($json_file_path, $json_data);

    //     $jsonData = file_get_contents($json_file_path);
    //     $data = json_decode($jsonData, true);

    //     $existingPartyNames = Ledger::pluck('party_name')->toArray();

    //     foreach ($data as $entry) {

    //         if (array_key_exists('tags', $entry)) {
    //             $tags = $entry['tags'];
    //         } else {
    //             // Set default value for 'tags' if not provided in Excel
    //             $tags = 'Excel';
    //         }

    //         // Trim leading and trailing spaces from party name
    //         $partyName = trim($entry['Party Name']);

    //         // Check if party name is empty after trimming
    //         if ($partyName === '') {
    //             return redirect()->back()->with('error', 'Party Name is required.');
    //         }

    //         // Check if party name has leading or trailing spaces
    //         if ($entry['Party Name'] !== $partyName) {
    //             return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the party name.');
    //         }

    //          // Check if party name already exists in the database
    //          if (in_array($partyName, $existingPartyNames)) {
    //             return redirect()->back()->with('error', 'Party Name "' . $partyName . '" already exists.');
    //         }

    //         // Trim leading and trailing spaces from party name
    //         $groupName = trim($entry['Group Name']);

    //         // Check if party name is empty after trimming
    //         if ($groupName === '') {
    //             return redirect()->back()->with('error', 'Group Name is required.');
    //         }

    //         // Check if party name has leading or trailing spaces
    //         if ($entry['Group Name'] !== $groupName) {
    //             return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the group name.');
    //         }

    //         // Check if applicable_date is empty
    //         if (empty($entry['Applicable Date'])) {
    //             // Return error response for empty applicable_date
    //             return redirect()->back()->with('error', 'Applicable Date is required.');
    //         }

    //         // Validate applicable_date format
    //         try {
    //             $applicableDate = Carbon::createFromFormat('d/m/Y', $entry['Applicable Date']);
    //         } catch (\Exception $e) {
    //             return redirect()->back()->with('error', 'Invalid date format. Date must be in DD/MM/YYYY format.');
    //         }

    //         // Check if gst_reg_type is provided
    //         if (empty($entry['GST Registration Type'])) {
    //             return redirect()->back()->with('error', 'GST Registration Type is required.');
    //         }

    //         // Check if gst_in is provided
    //         if (empty($entry['GSTIN/UIN'])) {
    //             return redirect()->back()->with('error', 'GSTIN/UIN is required.');
    //         }

    //         // Validate GSTIN format
    //         if (!preg_match("/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]){1}?$/", $entry['GSTIN/UIN'])) {
    //             return redirect()->back()->with('error', 'Invalid GSTIN/UIN format.');
    //         }

    //         // Get state code from the list of states
    //         $stateCode = array_search(strtoupper($entry['State']), array_map('strtoupper', $this->states));
    //         if ($stateCode === false) {
    //             return redirect()->back()->with('error', 'Invalid state code.');
    //         }

    //         Ledger::create([
    //             'party_name' => $partyName,
    //             'alias' => $entry['Alias'],
    //             'group_name' => $groupName,
    //             'credit_period' => $entry['Credit Period'],
    //             'buyer_name' => $entry['Buyer/Mailing Name'],
    //             'address1' => $entry['Address 1'],
    //             'address2' => $entry['Address 2'],
    //             'address3' => $entry['Address 3'],
    //             'country' => $entry['Country'],
    //             'state' => $stateCode, // Save state code instead of state name
    //             'pincode' => $entry['Pincode'],
    //             'gst_in' => $entry['GSTIN/UIN'],
    //             'gst_reg_type' => $entry['GST Registration Type'],
    //             'opening_balance' => $entry['Opening Balance DR/CR'],
    //             'applicable_date' => $applicableDate->toDateString(),
    //             'tags' => $tags,
    //         ]);

    //     }

    //     // If everything is successful, redirect with success message
    //     return redirect()->route('excelImport.ledgers.show')->with('success', __('Ledger Data Save Successfully.'));
    // }

    public function ledgerShow(LedgerDataTable $dataTable)
    {
            return $dataTable->render('app.excelImport._ledger-show');
    }
    public function ledgerDestroy($id)
    {
        $ledger = Ledger::find($id);
        $ledger->delete();
        return redirect()->route('excelImport.ledgers.show')->with('success', __('Ledger Data deleted successfully'));
    }
    public function ledgerInputStore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ledgers,id',
            'field' => 'required|in:party_name,alias,group_name,credit_period,buyer_name,address1,address2,address3,country,state,pincode,gst_in,gst_reg_type,opening_balance,applicable_date',
            'value' => [
                'required',
                $request->field === 'party_name' ? 'regex:/^[^\d]+$/' : '',
                $request->field === 'buyer_name' ? 'regex:/^[^\d]+$/' : '',
                $request->field === 'gst_in' ? 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/' : '',
            ],
        ]);

        $ledger = Ledger::findOrFail($request->id);

        // Update the appropriate field based on the 'field' parameter
        if ($request->field === 'party_name') {
            $ledger->party_name = $request->value;
        } elseif ($request->field === 'alias') {
            $ledger->alias = $request->value;
        } elseif ($request->field === 'group_name') {
            $ledger->group_name = $request->value;
        } elseif ($request->field === 'credit_period') {
            $ledger->credit_period = $request->value;
        } elseif ($request->field === 'buyer_name') {
            $ledger->buyer_name = $request->value;
        } elseif ($request->field === 'address1') {
            $ledger->address1 = $request->value;
        } elseif ($request->field === 'address2') {
            $ledger->address2 = $request->value;
        } elseif ($request->field === 'address3') {
            $ledger->address3 = $request->value;
        } elseif ($request->field === 'country') {
            $ledger->country = $request->value;
        } elseif ($request->field === 'state') {
            $ledger->state = $request->value;
        } elseif ($request->field === 'pincode') {
            $ledger->pincode = $request->value;
        } elseif ($request->field === 'gst_in') {
            $ledger->gst_in = $request->value;
        } elseif ($request->field === 'gst_reg_type') {
            $ledger->gst_reg_type = $request->value;
        } elseif ($request->field === 'opening_balance') {
            $ledger->opening_balance = $request->value;
        }  elseif ($request->field === 'applicable_date') {
            try {
                // Log the value received from the request
                Log::info('Received date value: ' . $request->value);

                // Parse the date in the correct format
                $applicableDate = Carbon::createFromFormat('Y-m-d', $request->value);
                // Format the date as "Y-m-d" before saving
                $formattedDate = $applicableDate->format('Y-m-d');
                $ledger->applicable_date = $formattedDate;
            } catch (\Exception $e) {
                // Handle the exception (e.g., log an error message)
                Log::error('Error parsing applicable date: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing applicable date'], 400);
            }
        }




        if ($ledger->save()) {
            return response()->json(['message' => 'Ledger updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update ledger']);
        }
    }

}
