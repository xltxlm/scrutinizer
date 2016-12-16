<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 12:46
 */

namespace xltxlm\scrutinizer\Unit;

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
    protected $tests = [];

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
        return $this->comment?:'-';
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
     * @return array
     */
    public function getTests(): array
    {
        return $this->tests;
    }

    /**
     * @param array $tests
     * @return AttributeModel
     */
    public function setTests(array $tests): AttributeModel
    {
        $this->tests = $tests;
        return $this;
    }
}
