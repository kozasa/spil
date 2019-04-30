## 概要
バドミントンサークル「Spil」のLineボットを活用したサークルWebシステム  
サークル活動の通知などをLineグループ上で行っていましたが、  
当時のLineグループの機能はサークル活動には機能が物足りなかったためbotを作成しました。

## できること
- サークルWebシステム
  - 管理画面
    1. LineIDでログイン
    2. イベント登録、編集
    3. サークル新規参加者登録
    4. Lineグループ上でイベントや参加者通知
  - ユーザー画面
    1. イベント日程一覧
    2. イベント参加、不参加登録（Lineログイン）
  - Linebot
    1. 指定した日時にイベント情報をLine通知
    2. イベント日に次回のイベント情報通知
- Webサイト
  - Spilのサークル外向きWebサイト

## フォルダ構成
- bot
  - Linebotの受信機能
- slim
  - slim Frameworkに則ったサークルWebシステム

## Slimのclassesについて

Repositoryパターンを似せた形で作成しています。  

- controller
  - コントローラ部分（Viewとロジックのつなぎ合わせ）
- model
  - モデル層だが、DBアクセスは含まない
- mapper
  - DBアクセスのみ担当
- utility
  - 共通処理

## 使い方
### git clone ###
```
git clone https://kozasa@bitbucket.org/kozasa/spil.git
```

### composer install###
slimフォルダ内にて、composerインストール
```
% pwd
spil_dev/slim
% curl -sS https://getcomposer.org/installer | php

```
### composer update###
slimフォルダ内にて、composer update
```
% php composer.phar update
```

### settings_param.phpを格納 ###
/slim/srcのなかにsettings_param.phpを格納する
```

/**
 * 設置値を格納
 * gitには記載しない
 */
const DB_HOST = 'DBホスト名';
const DB_USER = 'DBユーザ名';
const DB_PASS = 'DBパスワード';
const DB_DBNAME = 'DB名';

const ACCESS_TOKEN = 'LINEアクセストークン';
const PUSH_URL = 'https://api.line.me/v2/bot/message/push';
const PROFILE_URL = 'https://api.line.me/v2/profile';
const GROUP_URL = 'https://api.line.me/v2/bot/group/';
const GROUP_ID = 'グループID';
const PUSH_KEY = 'push_key';

const ROOT_URL = 'URL';
```
これが完了するとトップページはアクセスできる  
開発環境で構築する場合は、ドメインのルートでない場合が多いため、ルートにimgやcssファイルを格納する  
（UI側はルートのファイルを読みにいくため）
