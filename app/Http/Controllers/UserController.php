<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use App\Roles;
use App\States;
use App\Jurisdictions;
use Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
     public function __construct()
    {
         $this->middleware('auth');
        // $userid = Auth::id();
        
    }


public function index()
    {
         $users =User::leftjoin('roles', 'roles.id', '=', 'users.role_id')->leftjoin('states', 'states.id', '=', 'users.state_id')->leftjoin('jurisdictions', 'jurisdictions.id', '=', 'users.jurisdiction_id')->orderby('users.id','desc') ->select('users.*','states.name as states','jurisdictions.name as jurisdictions','roles.name as role')->get();
      


        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles =Roles::where('status',1)
         ->orderBy('id','DESC')
        ->select("id","name")->orderBy('name')->get();
        $states =states::where('status',1)
         ->orderBy('id','DESC')
        ->select("states.id","states.name")->orderBy('name')->get();
        
        return view('users.create',compact('states','roles'));
        
    }

public function store (Request $request)
{
$status=1;
 /* Validator::make($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]); */
$input['email'] = $request->get('email');

// Must not already exist in the `email` column of `users` table
$rules = array('email' => 'unique:users,email');

$validator = Validator::make($input, $rules);

if ($validator->fails()) {
    $message = 'That email address is already registered. You sure you don\'t have an account?';
 return redirect()->intended('/users/create')->with("message",$message);

}
else {
$userinsert=new User;
 $userinsert->name=$request->get('name');
        $userinsert->email=$request->get('email');
        $userinsert->jurisdiction_id=$request->get('jurisdiction_id');
         $userinsert->password=Hash::make($request->get('password'));
         $userinsert->state_id=$request->get('state_id');
         $userinsert->role_id=$request->get('role_id');
         
        $userinsert->status=$status;
        $userinsert->save();
 $data = [
                'email'   => $request->input('email'),
                'subject' => 'User Create',
                'name'    => $request->input('name'),
                'email_user'    => $request->input('email'),
                'password'    => $request->input('password')
              
            ];
  Mail::send('email.newuser', $data, function ($message) use ($data) {
                $message->from('hafsa@carmatec.in');
                $message->to($data['email'],$data['name']);
                $message->subject($data['subject']);
            });
            if (count(Mail::failures()) > 0) {
                $message= 'Failed to send the email';
                      }
else{ $message="User Created";}
   return redirect()->intended('/users')->with("message",$message);

          
}       
   
      
}
public function show($id)
{
  
$users =User::leftjoin('roles', 'roles.id', '=', 'users.role_id')->leftjoin('states', 'states.id', '=', 'users.state_id')->leftjoin('jurisdictions', 'jurisdictions.id', '=', 'users.jurisdiction_id')->where ('users.id',$id) ->select('users.*','states.name as state','jurisdictions.name as jurisdiction','roles.name as role')->first();
      
        return view('users.show',compact('users'));
      
}
 public function edit($id)
    {
        
        $users =User::find($id);
        $jid=$users->jurisdiction_id;
        $roles =Roles::where('status',1)
         ->orderBy('id','DESC')
        ->select("id","name")->orderBy('name')->get();
        $states =States::select("states.id","states.name")->orderBy('name')->get();
         $juridictions=Jurisdictions::where('id','=',$jid)->select('id','name')->get();;
     
        return view('users.edit',compact('users','states','roles','juridictions'));
      
        
    }

    public function update(Request $request, $id)
    {
        $status=1;
        
        $userinsert=User::find($id);
       
         
        $userinsert->name=$request->get('name');
        $userinsert->email=$request->get('email');
        $userinsert->jurisdiction_id=$request->get('jurisdiction_id');
        
         $userinsert->state_id=$request->get('state_id');
         $userinsert->role_id=$request->get('role_id');
         
        $userinsert->status=$status;
        $userinsert->save();
       

        
       $message="User Updated";
         return redirect()->intended('/users')->with("message",$message);

    }
    public function destroy($id)
    {
       
        User::where('id', $id)->delete();
        $message="User Deleted";
         return redirect()->intended('/users')->with("message",$message);
    }

    public function manage(Request $request)
    {
        $user =User::find($request->get('users_id'));
        if($request->get('action')){
            $user->status=1;
            $message = "User is activated";
        }
        else{
            $user->status=0;
            $message = "User is De-activated";
        }
        $user->save();

        return redirect()->intended('users')->with("message",$message);
    }

}
