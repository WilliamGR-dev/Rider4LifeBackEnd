<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikesController extends Controller
{
    //
    public function create($id)
    {
        ///Check si il a pas deja like

        DB::table('likes')->insert([
            'news_id' => $id,
            'user_id' => auth()->user()->id,
        ]);

        $likes = DB::table('likes')->where('id', DB::getPdo()->lastInsertId())->first();


        return response()->json([
            $likes
        ], 201);
    }
    public function getLikesByNewsId($id)
    {
        $news = DB::table('news')->where('user_id', $id)->get();

        return response()->json([
            $news
        ], 200);
    }
    public function delete($id)
    {
        $likes= DB::table('likes')->where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($likes) {

            DB::table('news')->delete($id);

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
