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
$app->get('/event/{id}[/{action}]', Controller\MemberController::class. ':event');

// 直近イベント日程画面
$app->get('/latest/', Controller\MemberController::class. ':latest');

// LINEログイン
// {page}:ページ名称
// {arg1}{arg2}:引数
$app->get('/auth/{page}[/{arg1}[/{arg2}]]', Controller\MemberController::class. ':auth');

// LINEログインコールバック
$app->get('/auth_callback/', Controller\MemberController::class. ':authCallback');

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

// 管理者画面 イベント編集一覧画面 get
$app->get('/admin/eventedit/', Controller\AdminController::class. ':eventEditListGet');

// 管理者画面 イベント編集画面 get
$app->get('/admin/eventedit/update/{event_id}', Controller\AdminController::class. ':eventEditGet');

// 管理者画面 イベント編集画面 post
$app->post('/admin/eventedit/update/{event_id}', Controller\AdminController::class. ':eventEditPost');

// 管理者画面 イベント削除 get
$app->get('/admin/eventedit/delete/{event_id}', Controller\AdminController::class. ':eventDeleteGet');

// 管理者画面 新規者登録 get
$app->get('/admin/newpost/', Controller\AdminController::class. ':newPostGet');

// 管理者画面 新規者登録 post
$app->post('/admin/newpost/', Controller\AdminController::class. ':newPostPost');

// 管理者画面 ログアウト
$app->get('/admin/logout/', Controller\AdminController::class. ':logout');

// グループ通知機能
$app->get('/push/{key}', Controller\AdminController::class. ':push');

// 直近イベント開催通知機能
$app->get('/latestpush/{key}', Controller\AdminController::class. ':latestpush');
