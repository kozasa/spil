<?php
namespace Tests\Classes\Utility;

use PHPUnit\Framework\TestCase;
use Classes\Utility;

class LineBotPushTest extends TestCase
{
    protected function tearDown()
    {
        test::clean(); // 登録したテストダブルをすべて削除
    }

    
}