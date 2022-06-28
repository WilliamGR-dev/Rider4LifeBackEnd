<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadsController extends Controller
{
    //

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'roads' => 'required',
        ]);

        DB::table('roads')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'short_description' => $request->descrishort_descriptionption,
            'roads' => json_encode($request->roads),
            'user_id' => auth()->user()->id,
        ]);

        $roads = DB::table('roads')->where('id', DB::getPdo()->lastInsertId())->first();


        return response()->json([
            $roads
        ], 201);
    }
    public function getAllRoads()
    {
        $news = DB::table('roads')->get();

        return response()->json([
            $news
        ], 200);
    }
    public function getRoad($id)
    {
        $news = DB::table('roads')->where('id', $id)->get();

        return response()->json([
            $news
        ], 200);
    }
    public function joinRoad($id)
    {

        DB::table('roads_members')->insert([
            'road_id' => $id,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
        ], 201);
    }
    public function getAllMembersRoads($id)
    {
        $roads_members = DB::table('roads_members')->where('id', $id)->get();

        return response()->json([
            $roads_members
        ], 200);
    }
    public function quitRoad($id)
    {

        DB::table('news')->where('road_id',$id)->where('user_id', auth()->user()->id)->delete();

        return response()->json([
        ], 204);
    }
}
