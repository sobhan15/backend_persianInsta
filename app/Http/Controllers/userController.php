<?php
/**
 * Created by PhpStorm.
 * User: Sobi
 * Date: 17/01/2019
 * Time: 02:23 PM
 */

namespace App\Http\Controllers;



use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;

class userController extends Controller
{
public function _signIn(Request $request){

    $email=$request->input("email");
    $pass=$request->input("pass");
    $hashPass=md5($pass);
    $r=DB::table("tbl_users")
        ->where([['email','=',$email],['password','=',$hashPass]])
        ->select('tbl_users.id')
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

public  function _signUp(Request $request){
    $name=$request->input("name");
    $email=$request->input("email");
    $pass=$request->input("pass");
    $image="http://localhost/images/profile/d.jpg";
    $hashPass=md5($pass);

    $sqlCheckMember=DB::table("tbl_users")
        ->where("email",'=',$email)
        ->get();

   if($sqlCheckMember->count()>0){
       $res="0";
       $sqlInserMember=null;

   }else{
       $sqlInserMember= DB::table("tbl_users")->
       insert(["name"=>$name,
           "email"=>$email,
           "password"=>$hashPass,
           "image"=>$image]);
       $res="1";
   }
    return \response()->json(
        [
            "res"=>$res,
            "data"=>$sqlInserMember
        ],Response::HTTP_OK
    );
}

public function _search(Request $request){
    $name=$request->input("name");
    $r=DB::table("tbl_users")
        ->where([["name",'like',"%".$name."%"],['status','=',1]])
        ->select('id','name','image')
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

public function _myActivities($id){
   $r= DB::table("activities")
       ->where('user_id','=',$id)
       ->join("tbl_users","tbl_users.id",'=','activities.fuser_id')
       ->select('fuser_id','name','image','activities.id')
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


    public function _otherActivities($id){
        $r= DB::table("activities")
            ->where('activities.user_id','=',$id)

            ->select('fuser_id','name','image')
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

public function _follower($id){
   $r= DB::table('activities')
        ->where('fuser_id','=',$id)
       ->join('tbl_users','tbl_users.id','=','activities.user_id')
       ->select('name','image','tbl_users.id')
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

public  function _following($id){
    $r= DB::table('activities')
        ->where('user_id','=',$id)
        ->join('tbl_users','tbl_users.id','=','activities.fuser_id')
        ->select('name','image','tbl_users.id')
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


    public function _myPosts($id){
        $sql=DB::table("tbl_users")
            ->where([['tbl_users.id','=',$id],['status','=',1]])

            ->join("tbl_posts",'tbl_posts.user_id','=','tbl_users.id')
            ->select(

                'tbl_users.name',
                'tbl_users.image as profilePic',

                'tbl_posts.*')
            ->orderByDesc("postDate")

            ->get();
        if($sql->count()>0){
            $res="1";
        }else{
            $res="0";
        }
        return response()->json(
            [
                "res"=>$res,
                "data"=>$sql
            ],Response::HTTP_OK
        );
    }

   

public function _userInfo($id){
    $sql=DB::table("tbl_users")
        ->where([['tbl_users.id','=',$id],['status','=',1]])

        ->join("tbl_posts",'tbl_posts.user_id','=','tbl_users.id')
        ->select(
            'tbl_users.id',
            'tbl_users.name',
            'tbl_users.image as profilePic',
            'tbl_users.followers',
            'tbl_users.followings',
            'tbl_users.posts',
            'tbl_users.bio',
            'tbl_posts.*')
        ->orderByDesc("postDate")

        ->get();
    if($sql->count()>0){
        $res="1";
    }else{
        $res="0";
    }
    return response()->json(
        [
            "res"=>$res,
            "data"=>$sql
        ],Response::HTTP_OK
    );
}

public function _updateName(Request $request){
    $name=$request->input("name");
    $id=$request->input("id");
    $sql=DB::table('tbl_users')
        ->where("id",'=',$id)
        ->update(['name'=>$name]);
    return $sql;
}

    public function _updateBio(Request $request){
        $bio=$request->input("bio");
        $id=$request->input("id");
        $sql=DB::table('tbl_users')
            ->where("id",'=',$id)
            ->update(['bio'=>$bio]);
        return $sql;
    }


    public function _updatePass(Request $request){
        $oldPass=$request->input("oldPass");
        $newPass=$request->input("newPass");
        $id=$request->input("id");
        $sql=DB::table('tbl_users')
            ->where([["id",'=',$id],["password","=",md5($oldPass)]])
            ->get();
            if ($sql->count()==0){
                return "2";
            }else{
                DB::table("tbl_users")
                    ->where('id',"=",$id)
                    ->update(["password"=>md5($newPass)]);
                return "1";
            }


    }

    public function _showPostFuser($id){
    $sql=DB::table("activities")
        ->where([['activities.user_id','=',$id]])
        ->join('tbl_posts','tbl_posts.user_id','=','activities.fuser_id')
        ->join('tbl_users','tbl_users.id','=','activities.fuser_id')
        ->select('tbl_posts.*','tbl_users.name','tbl_users.image as profilePic')
        ->orderByDesc("postDate")
        ->get();

    if($sql->count()>0){
        $res="1";
    }else{
        $res="0";
    }
    return response()->json(
        [
            'res'=>$res,
            'data'=>$sql,
        ],Response::HTTP_OK
    );
    }

    public function _isFollow(Request $request){
    $user_id=$request->input("user_id");
    $fuser_id=$request->input("fuser_id");
    $sql= DB::table('activities')->where([['activities.user_id','=',$user_id],['activities.fuser_id','=',$fuser_id]])
        ->get();

    if($sql->count()>0){
        $res="1";
    }else{
        $res="0";
    }
    return \response()->json(
    [
        'res'=>$res,
        'data'=>$sql
    ],Response::HTTP_OK
    );
    }

    public function _insertUser(Request $request){
        $user_id=$request->input("user_id");
        $fuser_id=$request->input("fuser_id");
   DB::table("activities")->insert(['user_id'=>$user_id,'fuser_id'=>$fuser_id]);

   DB::table("tbl_users")->where('id','=',$user_id)->increment('followings');
        DB::table("tbl_users")->where('id','=',$fuser_id)->increment('followers');
    }

    public function _deleteUser(Request $request){
        $user_id=$request->input("user_id");
        $fuser_id=$request->input("fuser_id");
        DB::table("activities")->where([['user_id','=',$user_id],['fuser_id','=',$fuser_id]])->delete();

        DB::table("tbl_users")->where('id','=',$user_id)->decrement('followings');
        DB::table("tbl_users")->where('id','=',$fuser_id)->decrement('followers');
    }



    public function _upload(Request $request){

    $user_id=$request->input('user_id');
    $caption=$request->input('caption');
        if($request->hasFile('postImage')){
           $image= $request->file("postImage");
           $name=$user_id." - ".time().".".$image->getClientOriginalExtension();
           $urlImage="http://192.168.43.15/lumen/storage/app/images/post/".$name;
            $image->move(storage_path('/app/images/post'),$name);

            DB::table('tbl_posts')
                ->insert(["user_id"=>$user_id
                    ,"image"=>$urlImage
                ,"caption"=>$caption
                ,"likes"=>0
                ,"comments"=>0]);
            $x="1";

        }else{
            $x="0";
        }

        return \response()->json([
           "res"=>$x
        ]);
    }

    public function _uploadProfilePic(Request $request){

    $user_id=$request->input("user_id");
    if($request->hasFile("profilePic")){
        $image=$request->file("profilePic");
        $name=time().".".$image->getClientOriginalExtension();
        $urlImage="http://192.168.43.15/lumen/storage/app/images/profile/".$name;
           $image ->move(storage_path('/app/images/profile'),$name);
           DB::table("tbl_users")->where('id','=',$user_id)
               ->update(["image"=>$urlImage]);
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