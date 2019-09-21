<?php
/**
 * Created by PhpStorm.
 * User: Sobi
 * Date: 18/01/2019
 * Time: 11:22 AM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{

    public function _postLike($id){
      $r= DB::table("tbl_likes")
           ->where("post_id",'=',$id)
           ->join("tbl_users",'tbl_users.id','=','tbl_likes.user_id')
           ->select("tbl_users.name",'tbl_users.image as profilePic','tbl_users.id as user_id','tbl_likes.id')
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

    public function _like(Request $request){
        $post_id=$request->input("post_id");
        $user_id=$request->input("user_id");

        $r=DB::table("tbl_likes")
            ->where([['post_id','=',$post_id],["user_id",'=',$user_id]])
            ->get();

        if ($r->count()==0){

            DB::table("tbl_likes")
                ->insert(["post_id"=>$post_id,"user_id"=>$user_id]);

            DB::table("tbl_posts")
                ->where('id','=',$post_id)
                ->increment('likes');


        }else{

        }

    }

    public function _checkLike(Request $request){
        $post_id=$request->input("post_id");
        $user_id=$request->input("user_id");

        $r=DB::table("tbl_likes")
            ->where([['post_id','=',$post_id],["user_id",'=',$user_id]])
            ->get();

        if ($r->count()>0){

            $res="1";

        }else{

            $res="0";
        }

        return \response()->json(
            [
                "res"=>$res
            ],Response::HTTP_OK
        );

    }


    public function _unLike(Request $request){
        $post_id=$request->input("post_id");
        $user_id=$request->input("user_id");

        DB::table("tbl_likes")
            ->where([['post_id','=',$post_id],['user_id','=',$user_id]])
            ->delete();

        DB::table("tbl_posts")
            ->where("id",'=',$post_id)
            ->decrement('likes');
    }

    public function _isLiked(Request $request){
        $post_id=$request->input("post_id");
        $user_id=$request->input("user_id");

        $sql=DB::table("tbl_likes")
            ->where([['post_id','=',$post_id],['user_id','=',$user_id]])
            ->get();
        if($sql->count()==0){
            return "0";

        }else{
            return "1";
        }
    }
}