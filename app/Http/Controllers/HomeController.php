<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        //ログインユーザを取得
        $user = Auth::user();

        //ユーザに紐づくフォルダ情報を取得
        $folder = $user->folders()->first();
        
        //フォルダが未作成の場合、Homeへ遷移
        if(is_null($folder)){
            return view('home');            
        }

        //フォルダ作成済みの場合、タスク一覧へ遷移
        return redirect()->route('tasks.index',[
            'folder' => $folder,
        ]);
    }
}
