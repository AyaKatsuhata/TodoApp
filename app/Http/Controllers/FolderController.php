<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Http\Requests\CreateFolder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function showCreateForm(){
        return view('folders/create');
    }
    public function create(CreateFolder $request){
        //フォルダモデルのインスタンスを生成
        $folder = new Folder();
        //タイトルに入力値を代入する
        $folder->title = $request->title;

        //インスタンスの状態をデータベースに書き込む
        Auth::user()->folders()->save($folder);

        return redirect()->route('tasks.index',[
            'folder' => $folder,
        ]);
    }
}
