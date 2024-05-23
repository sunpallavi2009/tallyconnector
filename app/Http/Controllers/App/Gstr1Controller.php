<?php

namespace App\Http\Controllers\App;


use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\Company;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
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
        //***gstAuth Data***
        $params = [
            'gstin' => '27AAGFI0474G1ZG',
            'retperiod' => '012024',
            'email' => 'irriion@gmail.com',
            'smrytyp' => 'L',
        ];
        $headers = [
            'gst_username' => 'aagfi0474g1',
            'state_cd' => '27',
            'ip_address' => '$this->IP_ADDRESS',
            'txn' => 'e0067f9c60024100a72ba3d60e7d9efa',
            'retperiod' => '012024',
    
        ];
        $response = $this->doRequest('gstr1',['query' => $params,'headers' => $headers]);
        $data = $response->getBody()->getContents();
        //return $data;
    
        $jsonData = json_decode($data, true);
        //dd( $jsonData);
        //***gstAuth Data***

        $companies = Company::get();
        return $dataTable->render('app.gstr1.index', compact('companies','jsonData'));
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

    public function connectToGSTgetData(Request $request)
    {
        $companyId = $request->input('company_id');
        $company = Company::find($companyId);

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        return response()->json([
            'success' => true,
            'email' => $company->email,
            'gst_no' => $company->gst_no,
            'gst_username' => $company->gst_user_name,
            'state' => $company->state,
            'token_id' => $company->token_id,
        ]);
    }

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

    public function otpRequest($args = []){
        //dd($args);
        $params = ['email' => 'irriion@gmail.com'];
        $headers = [
            'gst_username' => 'aagfi0474g1',
            'state_cd' => '27',
            'ip_address' => $this->IP_ADDRESS,
        ];

        $response = $this->doRequest('authentication/otprequest',['query' => $params,'headers' => $headers]);
        $data = $response->getBody()->getContents();
        // return $data;
    }

    public function otpVerify(Request $request){
        $otp = $request->input('otp');
        $params = ['email' => 'irriion@gmail.com','otp' => $otp ];
        $headers = [
            'gst_username' => 'aagfi0474g1',
            'state_cd' => '27',
            'ip_address' => $this->IP_ADDRESS,
            //'txn' => $args['txn'],
        ];
        $response = $this->doRequest('authentication/authtoken',['query' => $params,'headers' => $headers]);
        $data = $response->getBody()->getContents();
        return $data;
    }

    private function doRequest(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = 'GET'
        ): Response {
            try {
                $response = $this->client->request($requestMethod,$uriEndpoint,$params);
            } catch (GuzzleException $e) {
                Log::error('GST API Guzzle Exception ERROR : '.$e->getMessage());
                $response = new Response($e->getCode(),[],$e->getMessage());
            } catch (\Exception $e) {
                Log::error('GST API ERROR : '.$e->getMessage());
                $response = new Response($e->getCode(),[],$e->getMessage());
            }
            return $response;
    }

    // public function getGSTR1Summary(){
    //     $params = [
    //         'gstin' => '27AAGFI0474G1ZG',
    //         'retperiod' => '012024',
    //         'email' => 'irriion@gmail.com',
    //         'smrytyp' => 'L',
    //     ];
    //     $headers = [
    //         'gst_username' => 'aagfi0474g1',
    //         'state_cd' => '27',
    //         'ip_address' => '$this->IP_ADDRESS',
    //         'txn' => 'e0067f9c60024100a72ba3d60e7d9efa',
    //         'retperiod' => '012024',
    
    //     ];
    //     $response = $this->doRequest('gstr1',['query' => $params,'headers' => $headers]);
    //     $data = $response->getBody()->getContents();
    //     //return $data;
    
       
    //       $jsonData = json_decode($data, true);
    //     //dd( $jsonData);
    //     // // Pass the JSON data to the view
    //     return view('app.gstr1._gstAuth', compact('jsonData'));
    // }
}
