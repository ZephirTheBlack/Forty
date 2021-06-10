<?php

Route::view('/','welcome')->name('home');
Route::view('/who','who')->name('who');
Route::view('/contact','contact')->name('contact');

Route::get('statuses', 'StatusesController@index')->name('statuses.index');
Route::get('statuses/{status}', 'StatusesController@show')->name('statuses.show');
Route::post('statuses','StatusesController@store')->name('statuses.store')->middleware('auth');

Route::post('statuses/{status}/likes', 'StatusLikesController@store')->name('statuses.likes.store')->middleware('auth');
Route::delete('statuses/{status}/likes', 'StatusLikesController@destroy')->name('statuses.likes.destroy')->middleware('auth');

Route::post('statuses/{status}/comments', 'StatusCommentsController@store')->name('statuses.comments.store')->middleware('auth');

Route::post('comments/{comment}/likes','CommentLikeController@store')->name('comments.likes.store')->middleware('auth');
Route::delete('comments/{comment}/likes','CommentLikeController@destroy')->name('comments.likes.destroy')->middleware('auth');

Route::get('@{user}','UsersController@show')->name('users.show');
Route::get('@{user}/edit','UsersController@edit')->name('users.edit');
Route::patch('@{user}/update','UsersController@update')->name('users.update');

Route::get('users/{user}/statuses','UsersStatusController@index')->name('users.statuses.index');

Route::get('friends','FriendsController@index')->name('friends.index')->middleware('auth');
Route::get('friendships/{recipient}','FriendshipsController@show')->name('friendships.show')->middleware('auth');

Route::post('friendships/{recipient}','FriendshipsController@store')->name('friendships.store')->middleware('auth');
Route::delete('friendships/{user}','FriendshipsController@destroy')->name('friendships.destroy')->middleware('auth');


Route::get('friends/requests','AcceptFriendshipsController@index')->name('accept-friendships.index')->middleware('auth');
Route::post('accept-friendships/{sender}','AcceptFriendshipsController@store')->name('accept-friendships.store')->middleware('auth');
Route::delete('accept-friendships/{sender}','AcceptFriendshipsController@destroy')->name('accept-friendships.destroy')->middleware('auth');

Route::get('notifications','NotificationsController@index')->name('notifications.index')->middleware('auth');

Route::post('read-notifications/{notification}','ReadNotificationsController@store')->name('read-notifications.store')->middleware('auth');
Route::delete('read-notifications/{notification}','ReadNotificationsController@destroy')->name('read-notifications.destroy')->middleware('auth');


Route::auth();
