<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Mail\forgetpasswordmail;
use App\Http\Requests\UserRequests;
use App\Http\Requests\Userlogin;
use App\Http\Requests\forgetpassword;
use App\Http\Requests\resetpassword;
use Illuminate\Support\Facades\Mail;

class users extends Controller
{
    //
   
    public function signup(UserRequests $request)
    {
        //sigup for user on successful validation if vlaidation not successfull reponse will be returned back
        $user_model = new User();
        $user_model->username= $request->input('username');
        $user_model->email= $request->input('email');
        $user_model->password= Hash::make($request->input('password'));
        $user_model->save();
        return response()->json(['success'=>true,'message'=>'Successfully signup'],201);

    }
    public function login(Userlogin $request)
    {
            $email = $request->input('email');
            $password = $request->input('password');
            //after succesfull validation and credentials user will be login
            $user = User::where('email', '=', $email)->first();
            if (!$user) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, Email does not exist'],404);
            }
            if (!Hash::check($password, $user->password)) {
                return response()->json(['success'=>false, 'message' => 'Login Fail, Password is wrong'],404);
            }
            $user->token=Str::random(60).(string)$user->id;
            $user->save();
            return response()->json(['success'=>true,'message'=>'success'],200)->header('token',$user->token);
    }
    public function forgetpassword(forgetpassword $request)
    {
        //email sending with expiry time when user forget password
        //extra check not needed in real applications but here just checking to test    
            $token = $request->header('token');
            if($token!=null)
            {
                
               //at the time of forget password token which was assigned at the time of 
               //login will be null so if receiving any token will not do anything
               //return response()->json("for forget password token will be null",400);
            }
            else{
                // $id=Str::substr($token, 60);
                //As when user logout token will be null so in this case we don't have token so when we have
                //million os records then our searching will be slow if we do on based on email so 
                //making it fast we can do non-clustered indexing on email for this i already added unique on email
                //unique will added non clustered index on email col 
                $user = User::where('email', '=', $request->email)->first();
                if($user!=null){
                    $link=Str::random(60).(string)$user->id;
                    //expiry time to set password for the time being to test the exipry setting 1 min 
                    $expiry=new carbon;
                    $expiry=Carbon::parse($expiry)->addSeconds(60);
                    $user->update(['expiry' => $expiry]);
                    $user->update(['link' => $link]);
                    $data = [
                        'name' => $user->first_name,
                        'link'=>$link,//for testing purposes sending here otherwise not needed to show user the link
                        'token' => $link
                    ];
                    //sending email with name of forgotten user and link to reset password
                    Mail::to($request->email)->send(new forgetpasswordmail($data));
                    //Mail::to('wohavi2333@meimanbet.com')->send(new forgetpasswordmail($data));
                    if(count(Mail::failures()) > 0){
                        return response()->json('Mail not sent');
                    }
                    else{
                        return response(['message'=> 'We have e-mailed your password reset link!'],200);
                    }
                }
                else{
                    return response()->json(['success'=>false, 'message' => 'user does not exist'],404);
                }
            }

    }
    public function logout(Request $request)
    {
            //logout user and set token to null
            $user = User::where([['id', $request->userid]])->first();
            $user->update(['token' => null]);
            return response(['message'=> ' successfully logout'],200);
    }
    
    public function resetpassowrd(resetpassword $request,$links)
    {
            if($links!=null)
            {
                $id=Str::substr($links, 60);
                 //checking the expiry time of user by his/her id and sended link to rest password
                $expiredAt = Carbon::now();
                $user = User::where([
                    ['expiry', '>',$expiredAt],
                    ['id',$id],
                    ['link',$links]
                ])->first();
                if($user!=null){
                    //updating password when time is not expired
                    $user->update(['password' =>Hash::make($request->input('password'))]);
                    $user->save();
                    return response(['message'=> 'Password is successfully updated'],200);
                }
                else{
                    return response(['message'=> 'Time is expired to set Password'],410);
                }    
               
            }
            else{
                return response(['message'=> 'Link is empty'],404);
            }

    }
}
