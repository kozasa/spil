<?php

use Slim\Http\Request;
use Slim\Http\Response;

use Classes\Controller;

/**
 * HOMEPAGE
 */

// トップページ
$app->get('/', Controller\HomeController::class. ':home');

/**
 * MEMBER PAGE
 */

// イベント情報画面
$app->get('/event/{id}', Controller\MemberController::class. ':event');

// 直近イベント日程画面
$app->get('/latest/', Controller\MemberController::class. ':latest');

/**
 * ADMIN PAGE
 */

// 管理者画面 ログイン画面 get
$app->get('/admin/', Controller\AdminController::class. ':topGet');

// 管理者画面 ログイン画面 post
$app->post('/admin/', Controller\AdminController::class. ':topPost');

// 管理者画面 メニュー画面 get
$app->get('/admin/menu/', Controller\AdminController::class. ':menuGet');

// 管理者画面 イベント投稿画面 get
$app->get('/admin/eventpost/', Controller\AdminController::class. ':eventPostGet');

// 管理者画面 イベント投稿画面 post
$app->post('/admin/eventpost/', Controller\AdminController::class. ':eventPostPost');

// 管理者画面 新規者登録 get
$app->get('/admin/newpost/', Controller\AdminController::class. ':newPostGet');

// 管理者画面 新規者登録 post
$app->post('/admin/newpost/', Controller\AdminController::class. ':newPostPost');

// 管理者画面 ログアウト
$app->get('/admin/logout/', Controller\AdminController::class. ':logout');

// グループ通知機能
$app->get('/push/{key}', Controller\AdminController::class. ':push');