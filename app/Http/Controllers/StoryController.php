<?php
/**
 * Created by PhpStorm.
 * User: Sobi
 * Date: 17/01/2019
 * Time: 09:19 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class StoryController extends Controller
{


    public function _getStory($id){
        $r= DB::table("activities")
            ->where('activities.user_id','=',$id)
           ->join('tbl_users',"activities.fuser_id",'=','tbl_users.id')
            ->join('tbl_story','tbl_story.user_id','=','activities.fuser_id')
            ->select(
                'tbl_story.id',
                'activities.fuser_id',
                'tbl_users.image as profilePic',
                'tbl_users.name',
                'tbl_story.image as storyImage',
                'tbl_story.time')

            ->get();

        if($r->count()>0){
            $res="1";
        }else{
            $res="0";
        }

        return \response()->json(
            [
                "res"=>$res,
                "data"=>$r
            ],Response::HTTP_OK
        );
    }

    public function _uploadStory(Request $request){

        $user_id=$request->input("user_id");

        if($request->hasFile("imageStory")){

            $image=$request->file("imageStory");
            $name=$user_id."-".time().'.'.$image->getClientOriginalExtension();
            $urlImage="http://192.168.43.15/lumen/storage/app/images/story/".$name;
            $image->move(storage_path('/app/images/story'),$name);

            DB::table("tbl_story")
                ->insert(['user_id'=>$user_id,
                            'image'=>$urlImage,
                    'time'=>"2h"]);

            $x="1";

        }else{
            $x="0";
        }

        return \response()->json(
            [
                "res"=>$x
            ]
        );

    }
}