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
            'short_description' => $request->short_description,
            'routes' => json_encode($request->roads),
            'user_id' => auth()->user()->id,
        ]);

        $roads = DB::table('roads')->where('id', DB::getPdo()->lastInsertId())->first();


        return response()->json([
            $roads
        ], 201);
    }
    public function getAllRoads()
    {
        $roads = DB::table('roads')->get();

        foreach ($roads as $road){
            $road->routes = json_decode($road->routes);
        }
        return response()->json($roads, 200);
    }
    public function getRoad($id)
    {
        $road = DB::table('roads')->where('id', $id)->first();
        $joined = DB::table('roads_members')->where('user_id', auth()->user()->id)->first();
        $author = DB::table('users')->where('id', $road->user_id)->first();

        $road->joined = $joined;
        $road->author = $author;
        $road->distance = rand(1, 25);
        $road->location = 'Paris';
        return response()->json($road, 200);
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

        return response()->json(
            $roads_members
        , 200);
    }
    public function quitRoad($id)
    {

        DB::table('news')->where('road_id',$id)->where('user_id', auth()->user()->id)->delete();

        return response()->json([
        ], 204);
    }
}
