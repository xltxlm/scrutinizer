<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 12:46
 */

namespace xltxlm\scrutinizer\Unit;

use xltxlm\scrutinizer\Parser\ClassPaser;
use xltxlm\scrutinizer\Tests\TestUsed;

/**
 * 属性的模型
 * Class MethodModel
 * @package xltxlm\scrutinizer\Unit
 */
final class AttributeModel
{
    /** @var string 属性的名称 */
    protected $name = "";
    /** @var TypeModel 类型 */
    protected $type = "";

    protected $comment = "";
    protected $read = "";
    protected $write = "";
    /** @var string 该属性关联的方法是否被单元测试了 */
    protected $testsString = "";
    /** @var  ClassPaser 属性所属的类 */
    protected $ClassPaser;

    /**
     * @return ClassPaser
     */
    private function getClassPaser(): ClassPaser
    {
        return $this->ClassPaser;
    }

    /**
     * @param ClassPaser $ClassPaser
     * @return AttributeModel
     */
    public function setClassPaser(ClassPaser $ClassPaser): AttributeModel
    {
        $this->ClassPaser = $ClassPaser;
        return $this;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AttributeModel
     */
    public function setName(string $name): AttributeModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return TypeModel
     */
    public function getType(): TypeModel
    {
        return $this->type;
    }

    /**
     * @param TypeModel $type
     * @return AttributeModel
     */
    public function setType(TypeModel $type): AttributeModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment ?: '-';
    }

    /**
     * @param string $comment
     * @return AttributeModel
     */
    public function setComment(string $comment): AttributeModel
    {
        $this->comment = strtr($comment, ["\n" => "<br>"]);
        return $this;
    }

    /**
     * @return string
     */
    public function isRead(): string
    {
        return $this->read;
    }

    /**
     * @param string $read
     * @return AttributeModel
     */
    public function setRead(string $read): AttributeModel
    {
        $this->read = $read;
        return $this;
    }

    /**
     * @return string
     */
    public function isWrite(): string
    {
        return $this->write;
    }

    /**
     * @param string $write
     * @return AttributeModel
     */
    public function setWrite(string $write): AttributeModel
    {
        $this->write = $write;
        return $this;
    }

    /**
     * @return string
     */
    public function getTestsString(): string
    {
        $this->testsString = TestUsed::testMthods($this->getClassPaser()->getClassName(), $this->isRead()) . '<br>' .
            TestUsed::testMthods($this->getClassPaser()->getClassName(), $this->isWrite());
        if ($this->testsString <> '<br>') {
            $this->getClassPaser()->setTests(true);
        }
        return $this->testsString;
    }
}
