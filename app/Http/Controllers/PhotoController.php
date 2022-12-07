<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaryPost;
use Illuminate\Support\Facades\Auth;

define("CONST_LINES_PAGE", 5);
define("CONST_PUBLIC_IMG",'img/public');

class PhotoController extends Controller
{
    //検索
    public function index()  // this function show data in Table
    {
        $rlst = DiaryPost::where('user_id', Auth::id())->paginate(CONST_LINES_PAGE);
        return $rlst;
    }

    //新規作成
    public function store(Request $request)
    {
        $file = $request->file('photo');
        $fileName = '';
        if (!is_null($file)) {
            $name = $file->getClientOriginalName();
            $fileName = Auth::id() . '_' . time() . '_' . $name;
            $request->photo->move(public_path(CONST_PUBLIC_IMG), $fileName);
        }

        DiaryPost::create([
            'user_id' => Auth::id(),
            'diary_text' => $request['diaryText'],
            'photo' => $fileName,
        ]);

        return ['message' => 'Success'];
    }
    
    //単行表示
    public function show(Request $request, $id)
    {
        return DiaryPost::find($id);
    }
    
    //編集
    public function update(Request $request)
    {
        $upload = DiaryPost::find($request->id);
        $file = $request->file('photo');
        $fileName = '';
        if (!is_null($file)) {
            $name = $file->getClientOriginalName();
            $fileName = Auth::id() . '_' . time() . '_' . $name;
            $request->photo->move(public_path(CONST_PUBLIC_IMG), $fileName);

            $upload->update([
                'diary_text' => $request['diaryText'],
                'photo' => $fileName,
            ]);
        }else{
            $upload->update([
                'diary_text' => $request['diaryText'],
            ]); 
        }
        return ['message' => 'Success'];
    }

    //削除
    public function destroy($id)
    {
        $upload = DiaryPost::findOrFail($id);

        $upload->delete();
        return [
            'message' => 'Photo deleted successfully'
        ];
    }
}
