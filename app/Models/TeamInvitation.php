<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;
use App\Trait\TenantOrCentalConnection;

class TeamInvitation extends JetstreamTeamInvitation
{
    use TenantOrCentalConnection;
    /**
     * The attributes that are mass assignable.
     *
     * @var string<int, string>
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the team that the invitation belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Jetstream::teamModel());
    }

    

    public function store(Request $request)
    {
         $data = $request->except('_token');

                 $this->validate($request, [
                     'firstname'   => 'required',
                     'lastname'   => 'required',
                     'email'   => 'required|email|unique:users',
                     'pincode_pd'   => 'required',
                     'address'   => 'required',
                 ]);


        try{
              DB::beginTransaction();
                                                                dd($userdata);

                         $userdata['firstname'] = trim($data['firstname']);
                         $userdata['lastname'] = trim($data['lastname']);
                         $userdata['email'] = trim($data['email']);
                         $userdata['pincode'] = trim($data['pincode_pd']);
                         $userdata['address'] = trim($data['address']);

                         $userdata['food_allergies'] = $request->has('food_allergies') ? trim($data['food_allergies']) : Null;
                         $userdata['university_name'] = $request->has('university_name') ? trim($data['university_name']) : Null;
                         $user = DB::table('users')->insert($userdata);
               DB::commit();
               return redirect('/')->with('registered', 1);
        }catch(\Exception $exception)
        {
            DB::rollback();
            return back()->with('error', 1);

        }


    }
}
