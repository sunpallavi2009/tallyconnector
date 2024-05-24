<?php

namespace App\Http\Controllers\App;


use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Item;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Models\SalePurchaseInvoice;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\DataTables\App\BankDataTable;
use App\DataTables\App\ItemDataTable;
use App\DataTables\App\LedgerDataTable;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\DataTables\App\JournalDataTable;
use App\DataTables\App\SalePurchaseDataTable;
// use Illuminate\Support\Facades\Request;
// use Illuminate\Support\Facades\Validator; 



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

    //ledger
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

        // Define the directory and file path
        $directory = storage_path('app');
        $json_file_path = $directory . '/' . $file->getClientOriginalName() . '.json';

        // Ensure the directory exists
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents($json_file_path);
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        $existingPartyNames = Ledger::pluck('party_name')->toArray();

        foreach ($data as $entry) {

            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            $partyName = trim($entry['Party Name']);

            if ($partyName === '') {
                return redirect()->back()->with('error', 'Party Name is required.');
            }

            if ($entry['Party Name'] !== $partyName) {
                return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the party name.');
            }

            if (in_array($partyName, $existingPartyNames)) {
                return redirect()->back()->with('error', 'Party Name "' . $partyName . '" already exists.');
            }

            $groupName = trim($entry['Group Name']);

            if ($groupName === '') {
                return redirect()->back()->with('error', 'Group Name is required.');
            }

            if ($entry['Group Name'] !== $groupName) {
                return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the group name.');
            }

            if (!preg_match("/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]){1}?$/", $entry['GSTIN/UIN'])) {
                return redirect()->back()->with('error', 'Invalid GSTIN/UIN format.');
            }

            // Check if gst_in is provided
            if (!empty($entry['GSTIN/UIN'])) {
                // GSTIN/UIN is provided, so APPLICABLE DATE is required
                if (empty($entry['Applicable Date'])) {
                    // Return error response for empty applicable_date
                    return redirect()->back()->with('error', 'Applicable Date is required when GSTIN/UIN is provided.');
                }

                // Validate applicable_date format
                try {
                    $applicableDate = Carbon::createFromFormat('d/m/Y', $entry['Applicable Date']);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Invalid date format. Date must be in DD/MM/YYYY format.');
                }
            } else {
                // GSTIN/UIN is not provided, so no need to check APPLICABLE DATE
                $applicableDate = null;
            }

            $stateCode = array_search(strtoupper($entry['State']), array_map('strtoupper', $this->states));
            if ($stateCode === false) {
                return redirect()->back()->with('error', 'Invalid state code.');
            }

            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                Ledger::create([
                    'party_name' => $entry['Party Name'],
                    'alias' => $entry['Alias'],
                    'group_name' => $entry['Group Name'],
                    'credit_period' => $entry['Credit Period'],
                    'buyer_name' => $entry['Buyer/Mailing Name'],
                    'address1' => $entry['Address 1'],
                    'address2' => $entry['Address 2'],
                    'address3' => $entry['Address 3'],
                    'country' => $entry['Country'],
                    'state' => $stateCode,
                    'pincode' => $entry['Pincode'],
                    'gst_in' => $entry['GSTIN/UIN'],
                    'gst_reg_type' => $entry['GST Registration Type'],
                    'opening_balance' => number_format($entry['Opening Balance DR/CR'], 2, '.', ''),
                    'applicable_date' => $applicableDate ? $applicableDate->toDateString() : null,
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.ledgers.show')->with('success', __('Ledger Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }
   
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

    //item
    public function itemCreate()
    {
        return view('app.excelImport._item-create');
    }

    public function itemImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        foreach ($data as $entry) {
            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            if (!isset($entry['Item Name']) || empty($entry['Item Name'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Item Name is required.');
            }

            if (!isset($entry['UOM']) || empty($entry['UOM'])) {
                $valid = false;
                return redirect()->back()->with('error', 'UOM is required.');
            }

            if (!isset($entry['GST Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'GST Rate is required.');
            }
            

            if (isset($entry['GST Rate']) && !empty($entry['GST Rate']) && (!isset($entry['Applicable From']) || empty($entry['Applicable From']))) {
                $valid = false;
                return redirect()->back()->with('error', 'Applicable From is required when GST Rate is present.');
            }

            if (!isset($entry['GST Rate']) || !is_numeric($entry['GST Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'GST Rate must be a numeric value.');
            }

            if (!isset($entry['CGST Rate']) || !is_numeric($entry['CGST Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'CGST Rate must be a numeric value.');
            }

            if (!isset($entry['SGST Rate']) || !is_numeric($entry['SGST Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'SGST Rate must be a numeric value.');
            }

            if (!isset($entry['IGST Rate']) || !is_numeric($entry['IGST Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'IGST Rate must be a numeric value.');
            }

            if (!isset($entry['Opening QTY']) || !is_numeric($entry['Opening QTY'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Opening QTY must be a numeric value.');
            }

            if (!isset($entry['Rate']) || !is_numeric($entry['Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Rate must be a numeric value.');
            }

            if (!isset($entry['Amount']) || !is_numeric($entry['Amount'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Amount must be a numeric value.');
            }
            

            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                Item::create([
                    'item_name' => $entry['Item Name'],
                    'uom' => $entry['UOM'],
                    'alias1' => $entry['Item Code / Alias 1'],
                    'alias2' => $entry['Item Code / Alias 2'],
                    'part_no' => $entry['Part No'],
                    'item_desc' => $entry['Item Description'],
                    'hsn_code' => $entry['HSN Code'],
                    'hsn_desc' => $entry['HSN Discription'],
                    'taxability' => $entry['Taxability'],
                    'gst_rate' => $entry['GST Rate'],
                    'applicable_from' => Carbon::createFromFormat('d/m/Y', $entry['Applicable From'])->toDateString(),
                    'cgst_rate' => number_format($entry['CGST Rate'], 2, '.', ''),
                    'sgst_rate' => number_format($entry['SGST Rate'], 2, '.', ''),
                    'igst_rate' => number_format($entry['IGST Rate'], 2, '.', ''),
                    'opening_qty' => number_format($entry['Opening QTY'], 2, '.', ''),
                    'rate' => number_format($entry['Rate'], 2, '.', ''),
                    'amount' => number_format($entry['Amount'], 2, '.', ''),
                    'gst_type_of_supply' => $entry['GST TYPE OF SUPPLY'],
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.items.show')->with('success', __('Item Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function itemShow(ItemDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._item-show');
    }

    public function itemInputStore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:items,id',
            'field' => 'required|in:item_name,uom,alias1,alias2,part_no,item_desc,hsn_code,hsn_desc,taxability,gst_rate,applicable_from,cgst_rate,sgst_rate,igst_rate,opening_qty,rate,amount,gst_type_of_supply',
            'value' => [
                'required',
                $request->field === 'item_name' ? 'regex:/^[^\d]+$/' : '',
            ],
        ]);

        $item = Item::findOrFail($request->id);

        // Update the appropriate field based on the 'field' parameter
        if ($request->field === 'item_name') {
            $item->item_name = $request->value;
        } elseif ($request->field === 'uom') {
            $item->uom = $request->value;
        } elseif ($request->field === 'alias1') {
            $item->alias1 = $request->value;
        } elseif ($request->field === 'alias2') {
            $item->alias2 = $request->value;
        } elseif ($request->field === 'part_no') {
            $item->part_no = $request->value;
        } elseif ($request->field === 'item_desc') {
            $item->item_desc = $request->value;
        } elseif ($request->field === 'hsn_code') {
            $item->hsn_code = $request->value;
        } elseif ($request->field === 'hsn_desc') {
            $item->hsn_desc = $request->value;
        } elseif ($request->field === 'taxability') {
            $item->taxability = $request->value;
        } elseif ($request->field === 'gst_rate') {
            $item->gst_rate = $request->value;
        } elseif ($request->field === 'applicable_from') {
            try {
                // Log the value received from the request
                Log::info('Received date value: ' . $request->value);

                // Parse the date in the correct format
                $applicableFrom = Carbon::createFromFormat('Y-m-d', $request->value);
                // Format the date as "Y-m-d" before saving
                $applicableFrom = $applicableFrom->format('Y-m-d');
                $item->applicable_from = $applicableFrom;
            } catch (\Exception $e) {
                // Handle the exception (e.g., log an error message)
                Log::error('Error parsing applicable From: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing applicable From'], 400);
            }
        } elseif ($request->field === 'cgst_rate') {
            $item->cgst_rate = $request->value;
        } elseif ($request->field === 'sgst_rate') {
            $item->sgst_rate = $request->value;
        } elseif ($request->field === 'igst_rate') {
            $item->igst_rate = $request->value;
        } elseif ($request->field === 'opening_qty') {
            $item->opening_qty = $request->value;
        } elseif ($request->field === 'rate') {
            $item->rate = $request->value;
        } elseif ($request->field === 'amount') {
            $item->amount = $request->value;
        } elseif ($request->field === 'gst_type_of_supply') {
            $item->gst_type_of_supply = $request->value;
        } 


        if ($item->save()) {
            return response()->json(['message' => 'Item updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update Item']);
        }
    }

    public function itemDestroy($id)
    {
        $item = Item::find($id);
        $item->delete();
        return redirect()->route('excelImport.items.show')->with('success', __('Item Data deleted successfully'));
    }

    public function saleCreate()
    {
        return view('app.excelImport._sale-create');
    }

    public function saleImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        foreach ($data as $entry) {
            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            if (!isset($entry['Invoice Date']) || empty($entry['Invoice Date'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Invoice Date is required.');
            }
            if (!isset($entry['Invoice No']) || empty($entry['Invoice No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Invoice No is required.');
            }
            if (!isset($entry['Bill Ref No']) || empty($entry['Bill Ref No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Bill Ref No is required.');
            }
            if (!isset($entry['Voucher Type']) || empty($entry['Voucher Type'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Type is required.');
            }
            if (!isset($entry['Party Name']) || empty($entry['Party Name'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Party Name is required.');
            }
            if (!isset($entry['TAXABLE']) || empty($entry['TAXABLE'])) {
                $valid = false;
                return redirect()->back()->with('error', 'TAXABLE is required.');
            }
            if (!isset($entry['INVOICE AMOUNT']) || empty($entry['INVOICE AMOUNT'])) {
                $valid = false;
                return redirect()->back()->with('error', 'INVOICE AMOUNT is required.');
            }
            if (!preg_match("/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]){1}?$/", $entry['GSTIN/UIN'])) {
                return redirect()->back()->with('error', 'Invalid GSTIN/UIN format.');
            }

            $stateCode = array_search(strtoupper($entry['State']), array_map('strtoupper', $this->states));
            if ($stateCode === false) {
                return redirect()->back()->with('error', 'Invalid state code.');
            }
            
            

            if (!isset($entry['QTY']) || !is_numeric($entry['QTY'])) {
                $valid = false;
                return redirect()->back()->with('error', 'QTY must be a numeric value.');
            }
            if (!isset($entry['Item Rate']) || !is_numeric($entry['Item Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Item Rate must be a numeric value.');
            }
            if (!isset($entry['GST Rate']) || !is_numeric($entry['GST Rate'])) {
                $valid = false;
                return redirect()->back()->with('error', 'GST Rate must be a numeric value.');
            }
            if (!isset($entry['TAXABLE']) || !is_numeric($entry['TAXABLE'])) {
                $valid = false;
                return redirect()->back()->with('error', 'TAXABLE must be a numeric value.');
            }
            if (!isset($entry['CESS']) || !is_numeric($entry['CESS'])) {
                $valid = false;
                return redirect()->back()->with('error', 'CESS must be a numeric value.');
            }
            if (!isset($entry['DISCOUNT']) || !is_numeric($entry['DISCOUNT'])) {
                $valid = false;
                return redirect()->back()->with('error', 'DISCOUNT must be a numeric value.');
            }
            if (!isset($entry['INVOICE AMOUNT']) || !is_numeric($entry['INVOICE AMOUNT'])) {
                $valid = false;
                return redirect()->back()->with('error', 'INVOICE AMOUNT must be a numeric value.');
            }

            $partyName = trim($entry['Party Name']);

            if ($partyName === '') {
                return redirect()->back()->with('error', 'Party Name is required.');
            }

            if ($entry['Party Name'] !== $partyName) {
                return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the party name.');
            }

            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                SalePurchaseInvoice::create([
                    'inv_date' => Carbon::createFromFormat('d/m/Y', $entry['Invoice Date'])->toDateString(),
                    'inv_no' => $entry['Invoice No'],
                    'bill_ref_no' => $entry['Bill Ref No'],
                    'voucher_type' => $entry['Voucher Type'],
                    'party_name' => $entry['Party Name'],
                    'buyer_name' => $entry['Buyer/Mailing Name'],
                    'address1' => $entry['Address 1'],
                    'address2' => $entry['Address 2'],
                    'state' => $stateCode,
                    'country' => $entry['Country'],
                    'gst_in' => $entry['GSTIN/UIN'],
                    'gst_reg_type' => $entry['GST Registration Type'],
                    'place_of_supply' => $entry['Place of Supply'],
                    'company_reg_type' => $entry['Company State/ Registration Type'],
                    'item_name' => $entry['Item Name'],
                    'item_desc' => $entry['Item Description1'],
                    'qty' => number_format($entry['QTY'], 2, '.', ''),
                    'uom' => $entry['UOM'],
                    'item_rate' => number_format($entry['Item Rate'], 2, '.', ''),
                    'gst_rate' => number_format($entry['GST Rate'], 2, '.', ''),
                    'taxable' => number_format($entry['TAXABLE'], 2, '.', ''),
                    'sgst' => number_format($entry['SGST'], 2, '.', ''),
                    'cgst' => number_format($entry['CGST'], 2, '.', ''),
                    'igst' => number_format($entry['IGST'], 2, '.', ''),
                    'cess' => number_format($entry['CESS'], 2, '.', ''),
                    'discount' => number_format($entry['DISCOUNT'], 2, '.', ''),
                    'inv_amt' => number_format($entry['INVOICE AMOUNT'], 2, '.', ''),
                    'narration' => $entry['Narration'],
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.sales.show')->with('success', __('Sale Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function saleShow(SalePurchaseDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._sale-show');
    }

    public function salespurchaseInputStore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sale_purchase_invoices,id',
            'field' => 'required|in:inv_date,inv_no,bill_ref_no,voucher_type,party_name,address1,address2,state,country,gst_in,gst_reg_type,place_of_supply,company_reg_type,item_name,item_desc,qty,uom,item_rate,gst_rate,taxable,sgst,cgst,igst,cess,discount,inv_amt,narration',
            'value' => [
                'required',
                $request->field === 'party_name' ? 'regex:/^[^\d]+$/' : '',
                $request->field === 'gst_in' ? 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/' : '',
            ],
        ]);

        $salespurchase = SalePurchaseInvoice::findOrFail($request->id);

        // Update the appropriate field based on the 'field' parameter
        if ($request->field === 'inv_date') {
            try {
                Log::info('Received date value: ' . $request->value);
                $invDate = Carbon::createFromFormat('Y-m-d', $request->value);
                $invDate = $invDate->format('Y-m-d');
                $salespurchase->inv_date = $invDate;
            } catch (\Exception $e) {
                Log::error('Error parsing invoice date: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing Invoice Date'], 400);
            }
        } elseif ($request->field === 'inv_no') {
            $salespurchase->inv_no = $request->value;
        } elseif ($request->field === 'bill_ref_no') {
            $salespurchase->bill_ref_no = $request->value;
        } elseif ($request->field === 'voucher_type') {
            $salespurchase->voucher_type = $request->value;
        } elseif ($request->field === 'party_name') {
            $salespurchase->party_name = $request->value;
        } elseif ($request->field === 'address1') {
            $salespurchase->address1 = $request->value;
        } elseif ($request->field === 'address2') {
            $salespurchase->address2 = $request->value;
        } elseif ($request->field === 'state') {
            $salespurchase->state = $request->value;
        } elseif ($request->field === 'country') {
            $salespurchase->country = $request->value;
        } elseif ($request->field === 'gst_in') {
            $salespurchase->gst_in = $request->value;
        } elseif ($request->field === 'gst_reg_type') {
            $salespurchase->gst_reg_type = $request->value;
        } elseif ($request->field === 'place_of_supply') {
            $salespurchase->place_of_supply = $request->value;
        } elseif ($request->field === 'company_reg_type') {
            $salespurchase->company_reg_type = $request->value;
        } elseif ($request->field === 'item_name') {
            $salespurchase->item_name = $request->value;
        } elseif ($request->field === 'item_desc') {
            $salespurchase->item_desc = $request->value;
        } elseif ($request->field === 'qty') {
            $salespurchase->qty = $request->value;
        } elseif ($request->field === 'uom') {
            $salespurchase->uom = $request->value;
        } elseif ($request->field === 'item_rate') {
            $salespurchase->item_rate = $request->value;
        }  elseif ($request->field === 'gst_rate') {
            $salespurchase->gst_rate = $request->value;
        } elseif ($request->field === 'taxable') {
            $salespurchase->taxable = $request->value;
        } elseif ($request->field === 'sgst') {
            $salespurchase->sgst = $request->value;
        } elseif ($request->field === 'cgst') {
            $salespurchase->cgst = $request->value;
        } elseif ($request->field === 'igst') {
            $salespurchase->igst = $request->value;
        } elseif ($request->field === 'cess') {
            $salespurchase->cess = $request->value;
        } elseif ($request->field === 'discount') {
            $salespurchase->discount = $request->value;
        } elseif ($request->field === 'inv_amt') {
            $salespurchase->inv_amt = $request->value;
        } elseif ($request->field === 'narration') {
            $salespurchase->narration = $request->value;
        }  


        if ($salespurchase->save()) {
            return response()->json(['message' => 'Sale Purchase updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update Sale Purchase']);
        }
    }

    public function salespurchaseDestroy($id)
    {
        $salespurchase = SalePurchaseInvoice::find($id);
        if ($salespurchase) {
            $salespurchase->delete();
            return redirect()->back()->with('success', __('Sale Purchase Data deleted successfully'));
        } else {
            // Handle the case where the record was not found
            return redirect()->back()->with('error', __('Sale Purchase Data not found'));
        }
    }

    //Purchase

    public function purchaseCreate()
    {
        return view('app.excelImport._purchase-create');
    }

    public function purchaseImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        // $existingPartyNames = SalePurchaseInvoice::pluck('party_name')->toArray();

        foreach ($data as $entry) {

            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            $partyName = trim($entry['Party Name']);

            if ($partyName === '') {
                return redirect()->back()->with('error', 'Party Name is required.');
            }

            if ($entry['Party Name'] !== $partyName) {
                return redirect()->back()->with('error', 'Remove spaces at the beginning and end of the party name.');
            }

            $stateCode = array_search(strtoupper($entry['State']), array_map('strtoupper', $this->states));
            if ($stateCode === false) {
                return redirect()->back()->with('error', 'Invalid state code.');
            }

            if (!isset($entry['Invoice Date']) || empty($entry['Invoice Date'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Invoice Date is required.');
            }
            if (!isset($entry['Invoice No']) || empty($entry['Invoice No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Invoice No is required.');
            }
            if (!isset($entry['Bill Ref No']) || empty($entry['Bill Ref No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Bill Ref No is required.');
            }
            if (!isset($entry['Voucher Type']) || empty($entry['Voucher Type'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Type is required.');
            }
            if (!isset($entry['Party Name']) || empty($entry['Party Name'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Party Name is required.');
            }
            if (!isset($entry['TAXABLE']) || empty($entry['TAXABLE'])) {
                $valid = false;
                return redirect()->back()->with('error', 'TAXABLE is required.');
            }
            if (!isset($entry['INVOICE AMOUNT']) || empty($entry['INVOICE AMOUNT'])) {
                $valid = false;
                return redirect()->back()->with('error', 'INVOICE AMOUNT is required.');
            }
            

            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                SalePurchaseInvoice::create([
                    'inv_date' => Carbon::createFromFormat('d/m/Y', $entry['Invoice Date'])->toDateString(),
                    'inv_no' => $entry['Invoice No'],
                    'bill_ref_no' => $entry['Bill Ref No'],
                    'voucher_type' => $entry['Voucher Type'],
                    'party_name' => $entry['Party Name'],
                    'address1' => $entry['Address 1'],
                    'address2' => $entry['Address 2'],
                    'state' => $stateCode,
                    'country' => $entry['Country'],
                    'gst_in' => $entry['GSTIN/UIN'],
                    'gst_reg_type' => $entry['GST Registration Type'],
                    'place_of_supply' => $entry['Place of Supply'],
                    'company_reg_type' => $entry['Company State/ Registration Type'],
                    'item_name' => $entry['Item Name'],
                    'item_desc' => $entry['Item Description1'],
                    'qty' => number_format($entry['QTY'], 2, '.', ''),
                    'uom' => $entry['UOM'],
                    'item_rate' => number_format($entry['Item Rate'], 2, '.', ''),
                    'gst_rate' => number_format($entry['GST Rate'], 2, '.', ''),
                    'taxable' => number_format($entry['TAXABLE'], 2, '.', ''),
                    'sgst' => number_format($entry['SGST'], 2, '.', ''),
                    'cgst' => number_format($entry['CGST'], 2, '.', ''),
                    'igst' => number_format($entry['IGST'], 2, '.', ''),
                    'cess' => number_format($entry['CESS'], 2, '.', ''),
                    'discount' => number_format($entry['DISCOUNT'], 2, '.', ''),
                    'inv_amt' => number_format($entry['INVOICE AMOUNT'], 2, '.', ''),
                    'narration' => $entry['Narration'],
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.purchase.show')->with('success', __('Purchase Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function purchaseShow(SalePurchaseDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._purchase-show');
    }

    public function bankCreate()
    {
        return view('app.excelImport._bank-create');
    }
    
    public function bankImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        foreach ($data as $entry) {
            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            if (!isset($entry['Ledger Name']) || empty($entry['Ledger Name'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Ledger Name is required.');
            }
            if (!isset($entry['Voucher Type']) || empty($entry['Voucher Type'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Type is required.');
            }
            if (!isset($entry['Transaction Date']) || empty($entry['Transaction Date'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Transaction Date is required.');
            }
            if (!isset($entry['Voucher No']) || empty($entry['Voucher No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher No is required.');
            }
            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher No'],
                    'cheque_no' => $entry['Cheque No'],
                    'description' => $entry['Description / Narration'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'voucher_type' => $entry['Voucher Type'],
                    'ledger_name' => $entry['Ledger Name'],
                    'bank_name' => $entry['Bank Name'],
                    'instrument_date' => Carbon::createFromFormat('d/m/Y', $entry['Instrument Date'])->toDateString(),
                    'transection_type' => $entry['Transaction-type'],
                    'fav_name' => $entry['Favouring Name'],
                    'bank_date' => Carbon::createFromFormat('d/m/Y', $entry['Bank Date'])->toDateString(),
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.bank.show')->with('success', __('Bank Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function bankShow(BankDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._bank-show');
    }

    public function receiptCreate()
    {
        return view('app.excelImport._receipt-create');
    }

    public function receiptImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        foreach ($data as $entry) {
            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            if (!isset($entry['Ledger Name']) || empty($entry['Ledger Name'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Ledger Name is required.');
            }
            if (!isset($entry['Voucher Type']) || empty($entry['Voucher Type'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Type is required.');
            }
            if (!isset($entry['Transaction Date']) || empty($entry['Transaction Date'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Transaction Date is required.');
            }
            if (!isset($entry['Voucher No']) || empty($entry['Voucher No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher No is required.');
            }
            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher No'],
                    'cheque_no' => $entry['Cheque No'],
                    'description' => $entry['Description / Narration'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'voucher_type' => $entry['Voucher Type'],
                    'ledger_name' => $entry['Ledger Name'],
                    'bank_name' => $entry['Bank Name'],
                    'instrument_date' => Carbon::createFromFormat('d/m/Y', $entry['Instrument Date'])->toDateString(),
                    'transection_type' => $entry['Transaction-type'],
                    'fav_name' => $entry['Favouring Name'],
                    'bank_date' => Carbon::createFromFormat('d/m/Y', $entry['Bank Date'])->toDateString(),
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.receipt.show')->with('success', __('Receipt Voucher Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }
   
    public function receiptShow(BankDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._receipt-show');
    }

    public function paymentCreate()
    {
        return view('app.excelImport._payment-create');
    }
   
    public function paymentImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        foreach ($data as $entry) {
            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            if (!isset($entry['Ledger Name']) || empty($entry['Ledger Name'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Ledger Name is required.');
            }
            if (!isset($entry['Voucher Type']) || empty($entry['Voucher Type'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Type is required.');
            }
            if (!isset($entry['Transaction Date']) || empty($entry['Transaction Date'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Transaction Date is required.');
            }
            if (!isset($entry['Voucher No']) || empty($entry['Voucher No'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher No is required.');
            }
            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher No'],
                    'cheque_no' => $entry['Cheque No'],
                    'description' => $entry['Description / Narration'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'voucher_type' => $entry['Voucher Type'],
                    'ledger_name' => $entry['Ledger Name'],
                    'bank_name' => $entry['Bank Name'],
                    'instrument_date' => Carbon::createFromFormat('d/m/Y', $entry['Instrument Date'])->toDateString(),
                    'transection_type' => $entry['Transaction-type'],
                    'fav_name' => $entry['Favouring Name'],
                    'bank_date' => Carbon::createFromFormat('d/m/Y', $entry['Bank Date'])->toDateString(),
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.payment.show')->with('success', __('Payment Voucher Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function paymentShow(BankDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._payment-show');
    }
    
    public function bankReceiptPaymentInputStore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banks,id',
            'field' => 'required|in:trans_date,voucher_no,cheque_no,description,debit_amt,credit_amt,voucher_type,ledger_name,bank_name,instrument_date,transection_type,fav_name,bank_date',
            'value' => [
                'required',
                $request->field === 'ledger_name' ? 'regex:/^[^\d]+$/' : '',
            ],
        ]);

        $bankReceiptPayment = Bank::findOrFail($request->id);

        // Update the appropriate field based on the 'field' parameter
        if ($request->field === 'trans_date') {
            try {
                Log::info('Received date value: ' . $request->value);
                $transDate = Carbon::createFromFormat('Y-m-d', $request->value);
                $transDate = $transDate->format('Y-m-d');
                $bankReceiptPayment->trans_date = $transDate;
            } catch (\Exception $e) {
                Log::error('Error parsing Transaction Date: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing Transaction Date'], 400);
            }
        } elseif ($request->field === 'voucher_no') {
            $bankReceiptPayment->voucher_no = $request->value;
        } elseif ($request->field === 'cheque_no') {
            $bankReceiptPayment->cheque_no = $request->value;
        } elseif ($request->field === 'description') {
            $bankReceiptPayment->description = $request->value;
        } elseif ($request->field === 'debit_amt') {
            $bankReceiptPayment->debit_amt = $request->value;
        } elseif ($request->field === 'credit_amt') {
            $bankReceiptPayment->credit_amt = $request->value;
        } elseif ($request->field === 'voucher_type') {
            $bankReceiptPayment->voucher_type = $request->value;
        } elseif ($request->field === 'ledger_name') {
            $bankReceiptPayment->ledger_name = $request->value;
        } elseif ($request->field === 'bank_name') {
            $bankReceiptPayment->bank_name = $request->value;
        }elseif ($request->field === 'instrument_date') {
            try {
                Log::info('Received date value: ' . $request->value);
                $instrumentDate = Carbon::createFromFormat('Y-m-d', $request->value);
                $instrumentDate = $instrumentDate->format('Y-m-d');
                $bankReceiptPayment->instrument_date = $instrumentDate;
            } catch (\Exception $e) {
                Log::error('Error parsing Instrument Date: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing Instrument Date'], 400);
            }
        } elseif ($request->field === 'transection_type') {
            $bankReceiptPayment->transection_type = $request->value;
        } elseif ($request->field === 'fav_name') {
            $bankReceiptPayment->fav_name = $request->value;
        } elseif ($request->field === 'bank_date') {
            try {
                Log::info('Received date value: ' . $request->value);
                $bankDate = Carbon::createFromFormat('Y-m-d', $request->value);
                $bankDate = $bankDate->format('Y-m-d');
                $bankReceiptPayment->bank_date = $bankDate;
            } catch (\Exception $e) {
                Log::error('Error parsing Bank Date: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing Bank Date'], 400);
            }
        }


        if ($bankReceiptPayment->save()) {
            return response()->json(['message' => 'Bank Receipt Payment updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update Bank Receipt Payment']);
        }
    }

    public function bankReceiptPaymentDestroy($id)
    {
        $bankReceiptPayment = Bank::find($id);
        if ($bankReceiptPayment) {
            $bankReceiptPayment->delete();
            return redirect()->back()->with('success', __('Banks Data deleted successfully'));
        } else {
            // Handle the case where the record was not found
            return redirect()->back()->with('error', __('Banks Data not found'));
        }
    }

    public function journalCreate()
    {
        return view('app.excelImport._journal-create');
    }

    public function journalImport(Request $request)
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

        $json_file_path = storage_path('app/' . $file->getClientOriginalName() . '.json');
        $jsonData = file_put_contents($json_file_path, $json_data);

        $jsonData = file_get_contents(storage_path('app/' . $file->getClientOriginalName() . '.json'));
        $data = json_decode($jsonData, true);

        // Initialize a flag to check if all validations pass
        $valid = true;

        foreach ($data as $entry) {
            $tags = array_key_exists('tags', $entry) ? $entry['tags'] : 'Excel';

            if (!isset($entry['Debit Ledgers (Party Ledger)']) || empty($entry['Debit Ledgers (Party Ledger)'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Debit Ledgers (Party Ledger) is required.');
            }
            if (!isset($entry['Credit Ledgers']) || empty($entry['Credit Ledgers'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Credit Ledgers is required.');
            }
            if (!isset($entry['Voucher Type']) || empty($entry['Voucher Type'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Type is required.');
            }
            if (!isset($entry['Date']) || empty($entry['Date'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Date is required.');
            }
            if (!isset($entry['Voucher Number']) || empty($entry['Voucher Number'])) {
                $valid = false;
                return redirect()->back()->with('error', 'Voucher Number is required.');
            }
            
            if (!$valid) {
                break;
            }
        }

        if ($valid) {
            foreach ($data as $entry) {
                Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher Number'],
                    'voucher_type' => $entry['Voucher Type'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'credit_ledgers' => $entry['Credit Ledgers'],
                    'debit_ledgers' => $entry['Debit Ledgers (Party Ledger)'],
                    'narration' => $entry['Narration'],
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('excelImport.journal.show')->with('success', __('Journal Voucher Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function journalShow(JournalDataTable $dataTable)
    {
        return $dataTable->render('app.excelImport._journal-show');
    }

    
    public function journalInputStore(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banks,id',
            'field' => 'required|in:trans_date,voucher_no,voucher_type,debit_amt,credit_amt,credit_ledgers,debit_ledgers,narration',
            'value' => [
                'required',
            ],
        ]);

        $journal = Bank::findOrFail($request->id);

        // Update the appropriate field based on the 'field' parameter
        if ($request->field === 'trans_date') {
            try {
                Log::info('Received date value: ' . $request->value);
                $transDate = Carbon::createFromFormat('Y-m-d', $request->value);
                $transDate = $transDate->format('Y-m-d');
                $journal->trans_date = $transDate;
            } catch (\Exception $e) {
                Log::error('Error parsing Transaction Date: ' . $e->getMessage());
                return response()->json(['error' => 'Error parsing Transaction Date'], 400);
            }
        } elseif ($request->field === 'voucher_no') {
            $journal->voucher_no = $request->value;
        } elseif ($request->field === 'voucher_type') {
            $journal->voucher_type = $request->value;
        } elseif ($request->field === 'debit_amt') {
            $journal->debit_amt = $request->value;
        } elseif ($request->field === 'credit_amt') {
            $journal->credit_amt = $request->value;
        } elseif ($request->field === 'credit_ledgers') {
            $journal->credit_ledgers = $request->value;
        } elseif ($request->field === 'debit_ledgers') {
            $journal->debit_ledgers = $request->value;
        } elseif ($request->field === 'narration') {
            $journal->narration = $request->value;
        } 


        if ($journal->save()) {
            return response()->json(['message' => 'Journal updated successfully']);
        } else {
            return response()->json(['error' => 'Failed to update Journal']);
        }
    }

}
