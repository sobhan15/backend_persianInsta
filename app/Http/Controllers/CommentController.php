<?php
/**
 * Created by PhpStorm.
 * User: Sobi
 * Date: 18/01/2019
 * Time: 02:46 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function _postComment($id){
        $r=DB::table("tbl_comments")
            ->where("tbl_comments.post_id",'=',$id)
            ->join('tbl_users','tbl_users.id','=','tbl_comments.user_id')
            ->select('tbl_users.id as user_id','tbl_users.name','tbl_users.image as profilePic','tbl_comments.comments','tbl_comments.id')
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

    public function _comment(Request $request){
        $post_id=$request->input('post_id');
        $user_id=$request->input('user_id');
        $comment=$request->input('comment');

        DB::table('tbl_comments')
            ->insert(['post_id'=>$post_id,
                       'user_id'=>$user_id,
                        'comments'=>$comment ]);

        DB::table('tbl_posts')
            ->where("id",'=',$post_id)
            ->increment("comments");

        $lc=DB::table('tbl_comments')->where('user_id','=',$user_id)->take(1)
            ->join("tbl_users",'tbl_users.id','=','tbl_comments.user_id')
            ->select('tbl_comments.user_id','tbl_comments.id','tbl_users.name','tbl_users.image as profilePic','tbl_comments.comments')
            ->orderByDesc('time')
            ->get();


        if($lc->count()>0){
            $res="1";
        }else{
            $res="0";
        }

        return \response()->json(
            [
                "res"=>$res,
                "data"=>$lc
            ],Response::HTTP_OK
        );
    }
}