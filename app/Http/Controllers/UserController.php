<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePost;
use App\Http\Requests\SubscribeRequest;
use App\Http\Resources\NewPostResource;
use App\Http\Resources\SubscribeResource;
use App\Models\Post;
use App\Models\User;
use App\Notifications\SendEmailNotification;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function Subscribe(SubscribeRequest $request){

       $user=User::create($request->all());
       if($user){
        return new SubscribeResource($user);
       }else{
        return response()->json(['error'=>'something went to wrong!']);

       }

    }


    public function newpost(CreatePost $request){

        $posts=Post::create($request->all());

        $users=User::where('is_subscribe',1)->get();

        foreach($users as $emailuser){
            $emailuser->notify(new SendEmailNotification($posts));
        }

        if($posts){
            return new NewPostResource($posts);

        }else{
           return response()->json(['error'=>'something went to wrong!']);
        }

     }
}
