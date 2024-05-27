<?php

namespace App\Http\Controllers\App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SalePurchaseInvoice;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\DataTables\App\DebitNoteDataTable;

class DebitNoteController extends Controller
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
    
    public function index(DebitNoteDataTable $dataTable)
    {
        return $dataTable->render('app.debit-note.index');
    }

    public function debitNoteImport(Request $request)
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
                    // 'buyer_name' => $entry['Buyer/Mailing Name'],
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
                    'original_invoice_no' => $entry['Original Invoice No'],
                    'original_invoice_date' => Carbon::createFromFormat('d/m/Y', $entry['Original Invoice Date'])->toDateString(),
                    'reason_code' => $entry['Reason Code'],
                    'supplier_invoice_date' => Carbon::createFromFormat('d/m/Y', $entry['Supplier Invoice Date'])->toDateString(),
                    'tags' => $tags,
                ]);
            }

            return redirect()->route('debit-note.show')->with('success', __('Debit Note Data Save Successfully.'));
        } else {
            return redirect()->back()->with('error', 'Data validation failed.');
        }
    }

    public function debitNoteShow(DebitNoteDataTable $dataTable)
    {
        return $dataTable->render('app.debit-note._show');
    }

    public function debitNoteDestroy($id)
    {
        $debit = SalePurchaseInvoice::find($id);
        $debit->delete();
        return redirect()->route('debit-note.show')->with('success', __('Debit Note Data deleted successfully'));
    }
}
