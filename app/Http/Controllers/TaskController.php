<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //ToDo画面初期表示
    public function index(Folder $folder){
        //ユーザのフォルダを取得する
        $folders = Auth::user()->folders()->get();

        //選ばれたフォルダに紐づくタスクを取得する
        $tasks = $folder->tasks()->get();

        return view('tasks/index',[
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
        ]);
    }

    //タスク作成画面表示
    public function showCreateForm(Folder $folder){
        return view('tasks/create',[
            'folder' => $folder,
        ]);
    }

    //タスク作成
    public function create(Folder $folder, CreateTask $request){
        //タスクモデルのインスタンスを生成
        $task = new Task();
        //入力値を代入する
        $task->title = $request->title;
        $task->due_date = $request->due_date;
        //インスタンスの状態をデータベースに書き込む
        $folder->tasks()->save($task);

        return redirect()->route('tasks.index',[
            'folder' => $folder,
        ]);
    }

    //タスク編集画面表示
    public function showEditForm(Folder $folder, Task $task){
        //リレーションチェック処理
        $this->chkRelation($folder,$task);

        return view('tasks/edit',[
            'folder' => $folder,
            'task' => $task,
        ]);
    }

    //タスク編集
    public function edit(Folder $folder, Task $task, EditTask $request){
        //リレーションチェック処理
        $this->chkRelation($folder,$task);

        //入力値を代入する
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        return redirect()->route('tasks.index',[
            'folder' => $folder,
        ]);
    }

    //リレーションチェック
    private function chkRelation(Folder $folder, Task $task){
        if($folder->id !== $task->folder_id){
            abort(404);
        }
    }
}
