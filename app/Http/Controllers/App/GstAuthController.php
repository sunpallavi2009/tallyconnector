<?php

namespace App\Http\Controllers\App;

use GuzzleHttp\Client;
use App\Models\Company;
use App\Models\GstAuth;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Models\GstTransaction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;

class GstAuthController extends Controller
{
    public function index()
    {
        $companies = Company::get();
        return view('app.gstAuth.index', compact('companies'));
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
            'email' =>'irriion@gmail.com',
            'gst_no' => $company->gst_no,
            'gst_username' => $company->gst_user_name,
            'state' => $company->state,
            'token_id' => $company->token_id,
        ]);
    }

    public function otpRequest(Request $request)
    {
        $params = ['email' => 'irriion@gmail.com'];
        $headers = [
            'gst_username' => 'aagfi0474g1',
            'state_cd' => '27',
            'ip_address' => $this->IP_ADDRESS,
        ];
    
        $response = $this->doRequest('authentication/otprequest', ['query' => $params, 'headers' => $headers]);
        $data = $response->getBody()->getContents();
        $jsonData = json_decode($data, true);
    
        if (isset($jsonData['header']['txn'])) {
            $txn = $jsonData['header']['txn'];

            // GstAuth::where('user_id', $request->input('user_id'))->delete();
    
            // Save txn to the database
            $gstTransaction = GstAuth::updateOrCreate(
                ['user_id' => $request->input('user_id')],
                ['txn' => $txn]
            );
    
            return response()->json([
                'message' => 'OTP requested successfully',
                'status_cd' => '1',
                'data' => $jsonData,
                'txn' => $txn
            ]);
        } else {
            return response()->json(['message' => 'Failed to request OTP', 'status_cd' => '0'], 400);
        }
    }

    public function otpVerify(Request $request)
    {
        $otp = $request->input('otp');
        $txn = $request->input('txn');
        $params = ['email' => 'irriion@gmail.com', 'otp' => $otp];
        $headers = [
            'gst_username' => 'aagfi0474g1',
            'state_cd' => '27',
            'ip_address' => $this->IP_ADDRESS,
            'txn' => $txn,
        ];

        $response = $this->doRequest('authentication/authtoken', ['query' => $params, 'headers' => $headers]);
        $data = $response->getBody()->getContents();
        
        // Check if the OTP verification is successful
        $jsonData = json_decode($data, true);
        if (isset($jsonData['status']) && $jsonData['status'] == 'success') {
            return response()->json([
                'message' => 'OTP verified successfully',
                'data' => $jsonData,
                'success' => true,
                'redirect_url' => route('gstr1.index'), // URL to redirect to
            ]);
        } else {
            return response()->json(['message' => 'Failed to verify OTP', 'data' => $jsonData], 400);
        }
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
