<?php
namespace App\Http\Controllers;

use App\Models\DiaryPost;
use Illuminate\Http\Request;

class DiaryPostController extends Controller
{
    
    public function index()
    {
        // show all diary
        $diarys = DiaryPost::all();
        return view('diary.index', [
            'diarys' => $diarys,
        ]);
    }

    public function create()
    {
        //show form to create diary
        return view('diary.create');
    }

   
    public function store(Request $request)
    {
        //store a new diary
        $newPost = DiaryPost::create([
           // 'title' => $request->title,
            'diary_text' => $request->body,
            'user_id' => 1
        ]);

        return redirect('diary/' . $newPost->id);
    }

    public function show(DiaryPost $diaryPost)
    {
        //show diary
        return view('diary.show', [
        'diary' => $diaryPost
        ]);
    }

    
    public function edit(DiaryPost $diaryPost)
    {
        //show form to edit the diary
        return view('diary.edit', [
            'diary' => $diaryPost,
            ]);
    }

    
    public function update(Request $request, DiaryPost $diaryPost)
    {
        //save the edited diary
        $diaryPost->update([
            //'title' => $request->title,
            'diary_text' => $request->diary_text
        ]);

        return redirect('diary/' . $diaryPost->id);
    }

    
    public function destroy(DiaryPost $diaryPost)
    {
        //delete diary
        $diaryPost->delete();

        return redirect('/diary');
    }
}
