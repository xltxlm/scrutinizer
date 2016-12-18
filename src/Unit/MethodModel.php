<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 12:49
 */

namespace xltxlm\scrutinizer\Unit;

use xltxlm\scrutinizer\Parser\ClassPaser;
use xltxlm\scrutinizer\Tests\TestUsed;

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
    protected $returnType = "";
    /** @var TypeModel[] 参数类型 */
    protected $parameters = [];
    /** @var string 注释说明 */
    protected $comment = "";
    /** @var string 测试案例的markdown */
    protected $testsString = "";
    /** @var  ClassPaser */
    protected $ClassPaser;

    /**
     * @param ClassPaser $ClassPaser
     * @return MethodModel
     */
    public function setClassPaser(ClassPaser &$ClassPaser): MethodModel
    {
        $this->ClassPaser = &$ClassPaser;
        return $this;
    }

    /**
     * @return ClassPaser
     */
    public function getClassPaser(): ClassPaser
    {
        return $this->ClassPaser;
    }

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
    public function getReturnType(): string
    {
        return $this->returnType;
    }

    /**
     * @param string $returnType
     * @return MethodModel
     */
    public function setReturnType(string $returnType): MethodModel
    {
        $this->returnType = $returnType;
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
     * @return string
     */
    public function getTestsString(): string
    {
        $this->testsString = TestUsed::testMthods($this->getClassPaser()->getClassName(), $this->getName());
        if ($this->testsString) {
            $this->getClassPaser()->setTests(true);
        }
        return $this->testsString;
    }
}
