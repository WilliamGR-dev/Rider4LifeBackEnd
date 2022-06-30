<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class NewsController extends Controller
{
    //

    public function create(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'picture' => 'required',
        ]);

        $picture = Cloudinary::upload('data:image/png;base64,'.$request->picture);

        DB::table('news')->insert([
            'description' => $request->description,
            'picture' => $picture->getSecurePath(),
            'picture_id' => $picture->getPublicId(),
            'user_id' => auth()->user()->id,
        ]);

        $news = DB::table('news')->where('id', DB::getPdo()->lastInsertId())->first();


        return response()->json([
            $news
        ], 201);
    }

    public function getAllNews()
    {

        $news = DB::table('news')->orderBy('id', 'desc')->get();

        foreach ($news as $new){
            $likes = DB::table('likes')->count();
            $author = DB::table('users')->where('id', $new->user_id)->first();
            $liked = DB::table('likes')->where('id', auth()->user()->id)->first();
            $new->likes = $likes;
            $new->liked = $liked;
            $new->author = $author;
        }

        return response()->json(
            $news
        , 200);
    }

    public function getNews($id)
    {

        $news = DB::table('news')->where('id', $id)->first();

        $likes = DB::table('likes')->where('news_id', $id)->count();
        $author = DB::table('users')->where('id', $news->user_id)->first();
        $liked = DB::table('likes')->where('user_id', auth()->user()->id)->where('news_id', $id)->first();
        $news->likes = $likes;
        $news->liked = $liked;
        $news->author = $author;

        return response()->json(
            $news
        , 200);
    }

    public function getNewsByUserId($id)
    {
        $news = DB::table('news')->where('user_id', $id)->get();
        $publications = DB::table('news')->where('user_id', $id)->count();

        $author = DB::table('users')->where('id', $id)->first();

        $likes = DB::table('likes')->where('user_id', $id)->count();

        $data = json_decode('{}');
        $data->results = $news;
        $data->author = $author;
        $data->likes = $likes;
        $data->publications = $publications;

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        if (DB::table('news')->where('id', $id)->where('user_id', auth()->user()->id)->first()){

            $request->validate([
                'description' => 'required',
                'picture' => 'required',
            ]);


            DB::table('news')->where('id', $id)->update([
                'description' => $request->description,
            ]);

            $news = DB::table('news')->where('id', $id)->first();

            return response()->json([
                $news
            ], 200);
        }else{
            return response()->json([
                "message"=> "accès interdit."
            ], 401);
        }
    }

    public function delete($id)
    {
        $news= DB::table('news')->where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($news) {
            cloudinary()->destroy($news->picture_id);
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
