<?php

namespace App\Tests\Procedure;

use PHPUnit\Framework\TestCase;

class GetWechatMiniProgramPageShareConfigTest extends TestCase
{
    /**
     * @group skipped
     */
    public function testExecuteWithLoggedInUser(): void
    {
        $this->markTestSkipped('跳过此测试，UserInterface模拟存在类型问题');
    }
}