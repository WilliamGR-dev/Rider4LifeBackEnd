<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    //
    public function create(Request $request, $id)
    {
        $request->validate([
            'text' => 'required',
        ]);

        DB::table('comments')->insert([
            'text' => $request->text,
            'news_id' => $id,
            'user_id' => auth()->user()->id,
        ]);

        $news = DB::table('comments')->where('id', DB::getPdo()->lastInsertId())->first();


        return response()->json([
            $news
        ], 201);
    }
    public function getCommentsByNewsId($id)
    {
        $comments = DB::table('comments')->where('news_id', $id)->orderBy('id', 'desc')->get();

        foreach ($comments as $comment){
            $author = DB::table('users')->where('id', $comment->user_id)->first();
            $comment->author = $author;
        }
        return response()->json($comments, 200);
    }
    public function delete($id)
    {
        $comments= DB::table('comments')->where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($comments) {
            DB::table('comments')->delete($id);

            return response()->json([
            ], 204);
        }
        else{
            return response()->json([
                "message"=> "accès interdit."
            ], 401);
        }
    }
}
