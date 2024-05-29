<?php

namespace App\Http\Controllers\App;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\App\CompanyDataTable;

class CompanyController extends Controller
{
    
    protected $states=[
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

    public function index(CompanyDataTable $dataTable)
    {
        return $dataTable->render('app.company.index');
    }

    public function create()
    {
            $states = $this->states;
            $authToken = Auth::user()->remember_token;
            return view('app.company._create', compact('states','authToken'));
    }

    public function store(Request $request)
    {
            request()->validate([
                "company_name"=> "required",
                "gst_no"=> "required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/",
                "state"=> "required",
            ]);
            Company::create([
                'user_id'   => $request->user_id,
                'company_name'    => $request->company_name,
                'gst_no'     => $request->gst_no,
                'state'     => $request->state,
                'gst_user_name'     => $request->gst_user_name,
                'tally_company_guid'     => $request->tally_company_guid,
                'token_id'     => $request->token_id,
            ]);
            return redirect()->route('companies.index')
                ->with('success', __('Company created successfully.'));

    }

    public function edit($id)
    {
            $states = $this->states;
            $company    = Company::find($id);
            return view('app.company._edit', compact('company','states'));

    }

    public function update(Request $request, $id)
    {
            request()->validate([
                "company_name"=> "required",
                "gst_no"=> "required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/",
                "state"=> "required",
            ]);
            $company            = Company::find($id);
            $company->company_name   = $request->company_name;
            $company->gst_no    = $request->gst_no;
            $company->state     = $request->state;
            $company->gst_user_name     = $request->gst_user_name;
            $company->tally_company_guid     = $request->tally_company_guid;
            $company->token_id     = $request->token_id;
            $company->update();
            return redirect()->route('companies.index')->with('success', __('Company updated successfully.'));

    }

    public function destroy($id)
    {
            $company    = Company::find($id);
            $company->delete();
            return redirect()->route('companies.index')->with('success', __('Company deleted successfully'));

    }

}
