<?php

/**
 * パスワードハッシュ
 * 引数１：パスワード
 */

echo $argv[1]."\n";
//echo password_hash($argv[0],PASSWORD_DEFAULT)."\n";
echo password_hash('',PASSWORD_DEFAULT)."\n";