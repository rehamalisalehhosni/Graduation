<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

//Route::get('/', 'WelcomeController@index');
/// Back end routting//
Route::group(array("namespace" => "backend"), function() {
    Route::Controller("backend/serviceplace", "ServicePlaceController");
    Route::Controller("backend/serviceplacecategory", "ServicePlaceCategoryController");

    Route::Controller("backend/discussion", "DiscussionController");
    Route::Controller("backend/discussioncomment", "DiscussionCommentController");

    Route::Controller("backend/statistic", "StatisticController");
    Route::Controller("backend/notify", "AdminNotitficationController");

    Route::group(['middleware' => [ 'verifyCsrf']], function() {
        Route::Controller("backend/discussiontopic", "DiscussionTopicController");
        Route::Controller("backend/realstate", "RealstateController");
        Route::Controller("backend/unittype", "UnitTypeController");
        Route::Controller("backend/neighbarhood", "NeighbarhoodController");
        Route::Controller('backend/users', 'UserController');
        Route::Controller('backend/admin', 'AdminController');
    });

});


Route::group(array("namespace" => "api"), function() {
    ####Notifications####
    Route::Controller("notification", "PushNotificationController");
    ####Realestate####
    Route::Controller("realstate", "RealstateController");
    ####realstate comments####
    Route::Controller("realstatecomments", "RealStateCommentsController");

    ####realstate fav####

    Route::Controller("realstatefavorite", "RealStateFavoriteController");
    Route::Controller("discussioncomments", "DiscussionCommentController");
    Route::Controller("discussionfavorite", "DiscussionFavoriteController");

    ####realstate fav####
    Route::Controller("serviceplace", "ServicePlaceController");

    ####realstate fav####
    Route::Controller("serviceplacecategory", "ServiceCategoryController");

    ####routes to application controller functions####
    Route::Controller("application", "ApplicationController");
    ####routes to user controller functions####
    Route::Controller("user", "UserController");
    ####routes to neighborhood controller functions####
    Route::Controller("neighborhood", "NeighborhoodController");
    ####routes to report controller functions####
    Route::Controller("report", "ReportController");
    ####routes to report controller functions####
    Route::Controller("serviceplacefavorite", "ServicePlaceFavoriteController");
    Route::Controller("discussion", "DiscussionController");
    Route::Controller("servicereview", "ServiceReviewController");
});

Route::group(array("namespace" => "landingPage"), function() {

    Route::Controller("app", "PagesController");
    Route::Controller("en", "PagesEnglishController");

});
