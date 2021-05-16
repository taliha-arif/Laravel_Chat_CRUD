<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
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
use Illuminate\Support\Facades\Storage;
use App\Mail\forgetpasswordmail;
use App\Http\Requests\messaging;

use Illuminate\Support\Facades\Mail;

class chatting extends Controller
{
    public function message(messaging $request)
    {
        //function to createmessaeg of sender and receiver
        $token = $request->header('token');
        $id=Str::substr($token, 60);
        $sender = User::where([
                ['token', $token],
                ['id', $id]
            ])->first();
        
        $receiver = User::Where ( 'email', 'LIKE', '%' . $request->email . '%' )->first();
        if ($receiver!=null){
            //in real applicatrion only one type of message can be done at a time
            if(isset($request->audio)&&isset($request->text)){
                return response(['message'=> 'Only one type allowed'],422);
            }
            else if(isset($request->audio)){
                //saving file in storage and then storing that message in db

                
                $music_file = $request->audio;
                $filename=Str::random(9).$music_file->getClientOriginalName();
                Storage::put('audio/'.$filename, file_get_contents($request->audio));
                $message = new Message();
                $message->receiver_id=$receiver->id;
                $message->file=$filename;
                $sender->messages()->save($message);
                return response(['message'=> 'Message successfully sent'],200);
               
            }
            else if(isset($request->text)){
                $message = new Message();
                $message->receiver_id=$receiver->id;
                $message->content=$request->text;
                $sender->messages()->save($message);
                return response(['message'=> 'Message successfully sent'],200);
            }
            else{
                //if nothing is chooosen and sending message then should do one of them to send message
                return response(['message'=> 'Please send any text or audio to send message'],422);
            }
        }
           
        else
            return response(['message'=> 'Receiver does not exist'],404);  
    }
    public function delete(Request $request)
    {
        //deleting the single message of user
        if(Message::where('id', '=',  $request->id)->delete()){
           return response(['message'=> "successfuly deleted the selected message"],200);
        }
        else{
            return response(['message'=> "message you requested to delete not exist"],404);
        }
       
    }
    public function read(Request $request)
    {
       //function to retrive chat between two user if it exists
        $result=Message::where([['sender_id', $request->userid],['receiver_id', $request->id]])->get();
        if (count($result)>0) {
            return response(['message'=> "success",'allchat'=>$result],200);
        }
        else{
            return response(['message'=> "no chat"],404);
        }
    }
    public function update(Request $request)
    {
        //function to update only a single message with a reciever whose id taken by his\her email and message id sent by request
        $receiver = User::Where ( 'email', 'LIKE', '%' . $request->email . '%' )->first();
        if($receiver!=null)
        {
            if( Message::where([['id', $request->id],['receiver_id', $receiver->id],])->update(['content' => $request->text])){
                return response(['message'=> "successfully edit"],200);
            }
            else{
                return response(['message'=> "not found"],404);
            }
        }
    }
}
