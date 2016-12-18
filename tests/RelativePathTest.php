<?php

namespace xltxlm\scrutinizer\tests;

use PHPUnit\Framework\TestCase;
use xltxlm\scrutinizer\tests\Resource\ClassA;
use xltxlm\scrutinizer\tests\Resource\ClassB\Dir\ClassB;
use xltxlm\scrutinizer\tests\Resource\ClassB\Dir2\ClassD;
use xltxlm\scrutinizer\tests\Resource\ClassC;
use xltxlm\scrutinizer\Unit\RelativePath;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-18
 * Time: 下午 3:31.
 */
class RelativePathTest extends TestCase
{
    /**
     * 同级目录测试.
     *
     * @test
     */
    public function test1()
    {
        $RelatiPath = (new RelativePath())
            ->setFirstClassName(ClassA::class)
            ->setSecondClassName(ClassC::class)
            ->__invoke();
        $this->assertEquals('./ClassC.php', $RelatiPath);
    }

    /**
     * 上下级目录测试.
     *
     * @test
     */
    public function test2()
    {
        $RelatiPath = (new RelativePath())
            ->setFirstClassName(ClassA::class)
            ->setSecondClassName(ClassB::class)
            ->__invoke();
        $this->assertEquals('./ClassB/Dir/ClassB.php', $RelatiPath);

        $RelatiPath = (new RelativePath())
            ->setFirstClassName(ClassB::class)
            ->setSecondClassName(ClassA::class)
            ->__invoke();
        $this->assertEquals('./../../ClassA.php', $RelatiPath);
    }

    /**
     * 错开目录测试.
     *
     * @test
     */
    public function test3()
    {
        $RelatiPath = (new RelativePath())
            ->setFirstClassName(ClassD::class)
            ->setSecondClassName(ClassB::class)
            ->__invoke();
        $this->assertEquals('./../Dir/ClassB.php', $RelatiPath);
    }
}
