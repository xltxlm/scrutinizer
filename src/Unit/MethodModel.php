<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 12:49
 */

namespace xltxlm\scrutinizer\Unit;

/**
 * 方法属性
 * Class MethodModel
 * @package xltxlm\scrutinizer\Unit
 */
final class MethodModel
{
    /** @var string 属性的名称 */
    protected $name = "";
    /** @var string 类型 */
    protected $type = "";
    /** @var TypeModel[] 参数类型 */
    protected $parameters = [];
    /** @var string 注释说明 */
    protected $comment = "";
    protected $tests = [];

    /**
     * @return TypeModel[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param TypeModel[] $parameters
     * @return MethodModel
     */
    public function setParameters(array $parameters): MethodModel
    {
        $this->parameters = $parameters;
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
     * @return MethodModel
     */
    public function setName(string $name): MethodModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return MethodModel
     */
    public function setType(string $type): MethodModel
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return MethodModel
     */
    public function setComment(string $comment): MethodModel
    {
        $this->comment = strtr($comment, ["\n" => "<br>"]);
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
     * @return MethodModel
     */
    public function setTests(array $tests): MethodModel
    {
        $this->tests = $tests;
        return $this;
    }
}
