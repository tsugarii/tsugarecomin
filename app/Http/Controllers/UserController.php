<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\SettingRequest;

use App\thread;
use App\comment;
use App\User;

class UserController extends Controller
{
    public function user_page($user_id) {
        if(preg_match('/[a-zA-Z0-9]{7}/',$user_id) == 1) {
            $thread = new thread;
            $comment = new comment;

            if($thread::where('user_id', $user_id)->exists()) {
                $thread_datas = $thread::where('user_id',$user_id)
                ->orderby('time', 'desc')
                ->SimplePaginate(10);
            }
            
            if($comment::where('user_id', $user_id)->exists()) {
                $reses = $comment::where('user_id',$user_id)
                ->orderby('epoch_time', 'desc')
                ->SimplePaginate(10);
            }

            return view('user_page', compact('thread_datas', 'reses'));
        }
    }

    public function settings() {
        if(Auth::check()) {
            return view('settings');
        } else {
            return "ログインしていないと入れません。";
        }
    }

    public function settings_update(SettingRequest $request) {
        if(Auth::check()) {
            $user = $request->user();

            $user->name = $request->name;
            $user->save();

            return redirect('settings');
        }
    }
    
}
