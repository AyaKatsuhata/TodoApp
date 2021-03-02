<?php

namespace Tests\Feature;

use App\Http\Requests\CreateTask;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    // テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    /**
     * 各テストメソッドの実行前に呼ばれる
     */
    public function setup(): void
    {
        parent::setUp();

        //テストケース実行前にフォルダデータを作成する
        $this->seed('FoldersTableSeeder');
    }
    /**
     * 期限日が日付でない場合はバリデーションエラー
     * @test
     */
    public function due_date_should_be_date()
    {
        $respons = $this->post('/folders/1/tasks/create',[
            'title' => 'sample task',
            'due_date' => 123,
        ]);
        $respons->assertSessionHasErrors([
            'due_date' => '期限日 には日付を入力してください。',
        ]);
    }
    /**
     * 期限日が過去日付の場合はバリデーションエラー
     * @test
     */
    public function due_date_should_not_be_past()
    {
        $respons = $this->post('/folders/1/tasks/create',[
            'title' => 'sample task',
            'due_date' => Carbon::yesterday()->format('Y/m/d'),
        ]);
        $respons->assertSessionHasErrors([
            'due_date' => '期限日 には今日以降の日付を入力してください。',
        ]);
    }
}
