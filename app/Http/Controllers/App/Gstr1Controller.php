<?php

namespace App\Http\Controllers\App;


use GuzzleHttp\Client;
use App\Models\Company;
use App\Models\GstAuth;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use App\DataTables\App\B2BDataTable;
use App\DataTables\App\EXPDataTable;
use App\DataTables\App\NILDataTable;
use App\Http\Controllers\Controller;
use App\DataTables\App\B2CLDataTable;
use App\DataTables\App\B2CSDataTable;
use App\DataTables\App\CDNRDataTable;
use App\DataTables\App\CDNURDataTable;
use GuzzleHttp\Exception\GuzzleException;
use App\DataTables\App\SalesInvoiceDataTable;

class Gstr1Controller extends Controller
{
    public function index(Request $request, SalesInvoiceDataTable $dataTable)
    {
        $txnId = GstAuth::value('txn');
        $retperiod = $request->input('retperiod'); // Default to January 2022 if not provided
        $ipAddress = request()->ip();

        //***gstAuth Data***
        $companies = Company::get();

        return $dataTable->render('app.gstr1.index', compact('companies', 'txnId', 'ipAddress'));
    }


    public function gstAuthData(Request $request)
    {
        $txnId = GstAuth::value('txn');
        $retperiod = $request->input('retperiod'); // Default to January 2022 if not provided
        $ipAddress = request()->ip();

        //***gstAuth Data***
        $params = [
            'gstin' => '27AAGFI0474G1ZG',
            'retperiod' => $retperiod,
            'email' => 'irriion@gmail.com',
            'smrytyp' => 'L',
        ];

        $headers = [
            'gst_username' => 'aagfi0474g1',
            'state_cd' => '27',
            'ip_address' => $ipAddress, // Use the provided IP address
            'txn' => $txnId,
            'retperiod' => $retperiod,
        ];

        $response = $this->doRequest('gstr1/retsum', ['query' => $params, 'headers' => $headers]);
        $data = $response->getBody()->getContents();
        $jsonData = json_decode($data, true);

        //***gstAuth Data***

        return view('app.gstr1.gstAuth.index', compact('jsonData'));
    }

    public function b2bData(Request $request, B2BDataTable $dataTable)
    {
        $tags = $request->tags;
        return $dataTable->with('tags', $tags)->render('app.gstr1._b2b');
    }

    public function b2clData(B2CLDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._b2cl');
    }

    public function b2csData(B2CSDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._b2cs');
    }

    public function cdnrData(CDNRDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._cdnr');
    }

    public function cdnurData(CDNURDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._cdnur');
    }
    public function expData(EXPDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._exp');
    }
    
    public function nilData(NILDataTable $dataTable)
    {
        return $dataTable->render('app.gstr1._nil');
    }

    const BASE_URI = 'https://api.mastergst.com/';
    const CLIENT_ID = '80ca845d-4164-474d-b542-f35f8ff4d767';
    const CLIENT_SECRET = '058c5791-25d1-46ff-9676-aae38340f2c1';

    private $IP_ADDRESS;
    private $client;

    public function __construct() {
        $this->IP_ADDRESS = \Request::ip();
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'client_id' => self::CLIENT_ID,
                'client_secret' => self::CLIENT_SECRET,
            ]
        ]);
    }

    

    private function doRequest(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = 'GET'
    ): Response {
        try {
            $response = $this->client->request($requestMethod, $uriEndpoint, $params);
        } catch (GuzzleException $e) {
            Log::error('GST API Guzzle Exception ERROR: ' . $e->getMessage());
            $response = new Response($e->getCode(), [], $e->getMessage());
        } catch (\Exception $e) {
            Log::error('GST API ERROR: ' . $e->getMessage());
            $response = new Response($e->getCode(), [], $e->getMessage());
        }
        return $response;
    }
}
