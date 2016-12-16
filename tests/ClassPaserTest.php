<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 1:51
 */

namespace xltxlm\scrutinizer\tests;


use PHPUnit\Framework\TestCase;
use xltxlm\scrutinizer\Parser\ClassPaser;

class ClassPaserTest extends TestCase
{

    public function test1()
    {
        $ClassPaser = (new ClassPaser(ClassPaser::class));
        echo "<pre>-->";
        print_r($ClassPaser->getAttributeModel());
        echo "<--@in " . __FILE__ . " on line " . __LINE__ . "\n";
    }
    public function test2()
    {
        $ClassPaser = (new ClassPaser(ClassPaser::class));
        echo "<pre>-->";
        print_r($ClassPaser->getMethodModel());
        echo "<--@in " . __FILE__ . " on line " . __LINE__ . "\n";
    }

}