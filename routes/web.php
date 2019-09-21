<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return "sdfsdf";
});

$router->post('/signIn',"userController@_signIn");

$router->post('/signUp','userController@_signUp');

$router->get('/userInfo/{id}','userController@_userInfo');

$router->post('/search','userController@_search');

$router->get('/activities/{id}','userController@_myActivities');

$router->get('/otherActivities/{id}','userController@_otherActivities');

$router->get('/follower/{id}','userController@_follower');

$router->get('/following/{id}','userController@_following');

$router->get('/story/{id}','StoryController@_getStory');

$router->get('/postLike/{id}','LikeController@_postLike');

$router->post('/like','LikeController@_like');

$router->get('/postComment/{id}','CommentController@_postComment');

$router->post('/comment','CommentController@_comment');

$router->post('/unLike','LikeController@_unLike');

$router->post('/isLiked','LikeController@_isLiked');

$router->put('/updateName','userController@_updateName');

$router->put('/updateBio','userController@_updateBio');

$router->put('/updatePass','userController@_updatePass');

$router->get('/showPostFuser/{id}','userController@_showPostFuser');

$router->get('/myPosts/{id}','userController@_myPosts');

$router->post('/insertUser','userController@_insertUser');

$router->post('/deleteUser','userController@_deleteUser');

$router->post('/isFollow','userController@_isFollow');

$router->post('/checkLike','LikeController@_checkLike');

$router->post('/upload','userController@_upload');

$router->post('/uploadProfilePic','userController@_uploadProfilePic');

$router->post('/uploadStory','StoryController@_uploadStory');