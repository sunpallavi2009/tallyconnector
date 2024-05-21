<?php

namespace App\Http\Controllers\App;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Ledger;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\DataTables\App\BankDataTable;
use App\DataTables\App\ItemDataTable;
use App\DataTables\App\SaleDataTable;
use App\DataTables\App\LedgerDataTable;
use Illuminate\Support\Facades\Storage;
use App\DataTables\App\JournalDataTable;
use App\DataTables\App\PurchaseDataTable;
use App\Models\Bank;
use App\Models\SalePurchaseInvoice;
use Illuminate\Support\Facades\Validator;

class JsonImportController extends Controller
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
    
    public function index(LedgerDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport.index');
    }

    public function ledgerJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'ledger_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            $existingPartyNames = Ledger::pluck('party_name')->toArray();
            foreach ($data as $entry) {

                $tags = 'Tally'; 
            

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

                $newData = Ledger::create([
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
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Ledger data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function itemShow(ItemDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._item');
    }

    public function itemJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'item_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                    $tags = 'Tally'; 

                if (strlen($entry['Item Code / Alias 1']) > 255) {
                    throw new \Exception('Data too long for column "alias1".');
                }

                $newData = Item::create([
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
                    'applicable_from' => $entry['Applicable From'],
                    'cgst_rate' => $entry['CGST Rate'],
                    'sgst_rate' => $entry['SGST Rate'],
                    'igst_rate' => $entry['IGST Rate'],
                    'opening_qty' => $entry['Opening QTY'],
                    'rate' => $entry['Rate'],
                    'amount' => $entry['Amount'],
                    'gst_type_of_supply' => $entry['GST TYPE OF SUPPLY'],
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Item data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saleShow(SaleDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._sale');
    }

    public function saleJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'sale_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                    $tags = 'Tally'; 

                $newData = SalePurchaseInvoice::create([
                    'inv_date' => Carbon::createFromFormat('d/m/Y', $entry['Invoice Date'])->toDateString(),
                    'inv_no' => $entry['Invoice No'],
                    'bill_ref_no' => $entry['Bill Ref No'],
                    'voucher_type' => $entry['Voucher Type'],
                    'party_name' => $entry['Party Name'],
                    'buyer_name' => $entry['Buyer/Mailing Name'],
                    'address1' => $entry['Address 1'],
                    'address2' => $entry['Address 2'],
                    'state' => $entry['State'],
                    'country' => $entry['Country'],
                    'gst_in' => $entry['GSTIN/UIN'],
                    'gst_reg_type' => $entry['GST Registration Type'],
                    'place_of_supply' => $entry['Place of Supply'],
                    'company_reg_type' => $entry['Company State/ Registration Type'],
                    'item_name' => $entry['Item Name'],
                    'item_desc' => $entry['Item Description1'],
                    'qty' => $entry['QTY'],
                    'uom' => $entry['UOM'],
                    'item_rate' => $entry['Item Rate'],
                    'gst_rate' => $entry['GST Rate'],
                    'taxable' => $entry['TAXABLE'],
                    'sgst' => $entry['SGST'],
                    'cgst' => $entry['CGST'],
                    'igst' => $entry['IGST'],
                    'cess' => $entry['CESS'],
                    'discount' => $entry['DISCOUNT'],
                    'inv_amt' => $entry['INVOICE AMOUNT'],
                    'narration' => $entry['Narration'],
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Sale data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function purchaseShow(PurchaseDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._purchase');
    }

    public function purchaseJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'purchase_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                    $tags = 'Tally'; 

                $newData = SalePurchaseInvoice::create([
                    'inv_date' => Carbon::createFromFormat('d/m/Y', $entry['Invoice Date'])->toDateString(),
                    'inv_no' => $entry['Invoice No'],
                    'bill_ref_no' => $entry['Bill Ref No'],
                    'voucher_type' => $entry['Voucher Type'],
                    'party_name' => $entry['Party Name'],
                    'address1' => $entry['Address 1'],
                    'address2' => $entry['Address 2'],
                    'state' => $entry['State'],
                    'country' => $entry['Country'],
                    'gst_in' => $entry['GSTIN/UIN'],
                    'gst_reg_type' => $entry['GST Registration Type'],
                    'place_of_supply' => $entry['Place of Supply'],
                    'company_reg_type' => $entry['Company State/ Registration Type'],
                    'item_name' => $entry['Item Name'],
                    'item_desc' => $entry['Item Description1'],
                    'qty' => $entry['QTY'],
                    'uom' => $entry['UOM'],
                    'item_rate' => $entry['Item Rate'],
                    'gst_rate' => $entry['GST Rate'],
                    'taxable' => $entry['TAXABLE'],
                    'sgst' => $entry['SGST'],
                    'cgst' => $entry['CGST'],
                    'igst' => $entry['IGST'],
                    'cess' => $entry['CESS'],
                    'discount' => $entry['DISCOUNT'],
                    'inv_amt' => $entry['INVOICE AMOUNT'],
                    'narration' => $entry['Narration'],
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Purchase data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bankShow(BankDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._bank');
    }

    public function bankJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'bank_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                    $tags = 'Tally'; 

                $newData = Bank::create([
                    'trans_date' => $entry['Transaction Date'],
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher No'],
                    'cheque_no' => $entry['Cheque No'],
                    'description' => $entry['Description / Narration'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'voucher_type' => $entry['Voucher Type'],
                    'ledger_name' => $entry['Ledger Name'],
                    'bank_name' => $entry['Bank Name'],
                    'instrument_date' => $entry['Instrument Date'],
                    'transection_type' => $entry['Transaction-type'],
                    'fav_name' => $entry['Favouring Name'],
                    'bank_date' => Carbon::createFromFormat('d/m/Y', $entry['Bank Date'])->toDateString(),
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Bank data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function receiptShow(BankDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._receipt');
    }

    public function receiptJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'receipt_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                    $tags = 'Tally'; 

                $newData = Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher No'],
                    'cheque_no' => $entry['Cheque No'],
                    'description' => $entry['Description / Narration'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'voucher_type' => $entry['Voucher Type'],
                    'ledger_name' => $entry['Ledger Name'],
                    'bank_name' => $entry['Bank Name'],
                    'instrument_date' => $entry['Instrument Date'],
                    'transection_type' => $entry['Transaction-type'],
                    'fav_name' => $entry['Favouring Name'],
                    'bank_date' => Carbon::createFromFormat('d/m/Y', $entry['Bank Date'])->toDateString(),
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Receipt data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function paymentShow(BankDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._payment');
    }

    public function paymentJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'payment_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                    $tags = 'Tally'; 

                $newData = Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher No'],
                    'cheque_no' => $entry['Cheque No'],
                    'description' => $entry['Description / Narration'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'voucher_type' => $entry['Voucher Type'],
                    'ledger_name' => $entry['Ledger Name'],
                    'bank_name' => $entry['Bank Name'],
                    'instrument_date' => $entry['Instrument Date'],
                    'transection_type' => $entry['Transaction-type'],
                    'fav_name' => $entry['Favouring Name'],
                    'bank_date' => Carbon::createFromFormat('d/m/Y', $entry['Bank Date'])->toDateString(),
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Payment data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function journalShow(JournalDataTable $dataTable)
    {
        return $dataTable->render('app.jsonImport._journal');
    }

    public function journalJsonImport(Request $request)
    {
        try {
            $jsonData = $request->getContent();
            $fileName = 'journal_data_' . date('YmdHis') . '.json'; 
            
            $jsonFilePath = storage_path('app/' . $fileName);
            file_put_contents($jsonFilePath, $jsonData);

            $jsonData = file_get_contents($jsonFilePath);
            $data = json_decode($jsonData, true);

            if (!$data) {
                throw new \Exception('Invalid JSON data.');
            }

            foreach ($data as $entry) {

                $tags = 'Tally'; 

                $newData = Bank::create([
                    'trans_date' => Carbon::createFromFormat('d/m/Y', $entry['Transaction Date'])->toDateString(),
                    'voucher_no' => $entry['Voucher Number'],
                    'voucher_type' => $entry['Voucher Type'],
                    'debit_amt' => $entry['Debit Amount'],
                    'credit_amt' => $entry['Credit Amount'],
                    'credit_ledgers' => $entry['Credit Ledgers'],
                    'debit_ledgers' => $entry['Debit Ledgers (Party Ledger)'],
                    'narration' => $entry['Narration'],
                    'tags' => $tags
                ]);

                if (!$newData) {
                    throw new \Exception('Failed to create data record.');
                }
            }

            return response()->json(['message' => 'Journal data saved successfully.']);

        } catch (\Exception $e) {
            Log::error('Error importing data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
