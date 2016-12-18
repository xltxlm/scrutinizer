<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 1:34.
 */

namespace xltxlm\scrutinizer\Parser;

use xltxlm\helper\filesystem\Template;
use xltxlm\scrutinizer\Unit\AttributeModel;
use xltxlm\scrutinizer\Unit\MethodModel;
use xltxlm\scrutinizer\Unit\TypeModel;

/**
 * 解析类的使用,取出有set/get对应方法的属性 和 单独的公开方法,并且取出注释
 * Class ClassPaser.
 */
final class ClassPaser extends Template
{
    /** @var \ReflectionMethod[] 类分析出来的公开方法 */
    private $methods = [];
    /** @var string 类的名称 */
    protected $className = '';

    /** @var AttributeModel[] 属性类的列表 */
    private $attributeModel = [];
    /** @var MethodModel[] 方法类的列表 */
    private $MethodModel = [];
    /** @var \ReflectionClass 反射对象 */
    private $reflect_object;
    /** @var bool 是否有单元测试案例 */
    protected $tests = false;

    /**
     * @param bool $tests
     */
    public function setTests(bool $tests)
    {
        $this->tests = $tests;
    }

    /**
     * @return bool
     */
    public function isTests(): bool
    {
        return $this->tests;
    }

    /**
     * @return \ReflectionClass
     */
    public function getReflectObject(): \ReflectionClass
    {
        return $this->reflect_object;
    }

    /**
     * 设置需要处理的类名称
     * ClassPaser constructor.
     *
     * @param string $className
     */
    public function __construct(string $className = '')
    {
        if ($className) {
            $this->setClassName($className);
        }
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return ClassPaser
     */
    public function setClassName(string $className): ClassPaser
    {
        $this->className = $className;
        $this->reflect_object = (new \ReflectionClass($this->className));

        return $this;
    }

    /**
     * 得到有对应写入或者读取的属性名称数组.
     *
     * @return AttributeModel[]
     */
    public function getAttributeModel(): array
    {
        //属性
        $properties = $this->reflect_object->getProperties();
        //方法
        $ReflectionMethods = $this->reflect_object->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($ReflectionMethods as $key => $method) {
            $strtolowerMethod = strtolower($method->getName());
            $this->methods[$strtolowerMethod] = $method;
        }
        //分析出类文件头部引入的use数组
        preg_match_all('#use\s+([^;]+);#iUs', file_get_contents($this->reflect_object->getFileName()), $use);
        $useClass = $use[1];
        foreach ($properties as $attributeModel) {
            //继承来的属性需要再取出父类的引入
            $attributeObject = $attributeModel->getDeclaringClass();
            if ($attributeObject->getName() != $this->className) {
                preg_match_all('#use\s+([^;]+);#iUs', file_get_contents($attributeObject->getFileName()), $use);
                $useClass = array_merge($useClass, $use[1]);
            }
            //确定有读写功能
            $name = $attributeModel->getName();
            $propertieName = strtr(strtolower($name), ['_' => '']);
            $readPower1 = $this->methods['is'.$propertieName];
            $readPower2 = $this->methods['get'.$propertieName];
            /** @var \ReflectionMethod $readPower */
            $readPower = $readPower1 ?? $readPower2;
            /** @var \ReflectionMethod $writePower */
            $writePower = $this->methods['set'.$propertieName];
            //如果没有读,也没有写权限,那么跳过
            if (!$readPower && !$writePower) {
                continue;
            }
            if ($readPower) {
                unset($this->methods[strtolower($readPower->getName())]);
            }
            if ($writePower) {
                unset($this->methods[strtolower($writePower->getName())]);
            }

            $AttributeModelObject = (new AttributeModel())
                ->setClassPaser($this);
            $AttributeModelObject->setName($name);
            if ($readPower) {
                $AttributeModelObject->setRead($readPower->getName());
            }
            if ($writePower) {
                $AttributeModelObject->setWrite($writePower->getName());
            }
            $comment = $attributeModel->getDocComment();
            //解析当前属性的数字, 分拆成 类型+解释
            preg_match("#@var\s+([^\s]+)\s+(.*)\*/#i", $comment, $out);
            $comment = $out[2];
            $TypeModel = (new TypeModel())
                ->setClassPaser($this);
            //属性类型为注释的第2个参数
            $type = $out[1];
            $isArray = (bool) strpos($type, '[]');
            //如果是数组类型的,需要纠正下
            if ($isArray) {
                $TypeModel->setIsarray($isArray);
                $type = substr($type, 0, -2);
            }
            $TypeModel->setTypeName($type ?: 'string', $useClass, $this->reflect_object->getNamespaceName());
            $AttributeModelObject->setType($TypeModel);

            $AttributeModelObject->setComment((string) $comment);
            $this->attributeModel[] = $AttributeModelObject;
        }
        uasort($this->attributeModel, [$this, 'sortAttributeModel']);

        return $this->attributeModel;
    }

    /**
     * 按照 可设置 - 只读取 排序.
     *
     * @param AttributeModel $a
     * @param AttributeModel $b
     *
     * @return int
     */
    private function sortAttributeModel(AttributeModel $a, AttributeModel $b)
    {
        return strlen($b->isWrite()) <=> strlen($a->isWrite());
    }

    /**
     * @return MethodModel[]
     */
    public function getMethodModel(): array
    {
        if ($this->MethodModel) {
            return $this->MethodModel;
        }
        //初始化数据
        if (!$this->attributeModel && !$this->methods) {
            $this->getAttributeModel();
        }
        //循环类的方法,(已经去掉了set/get这些方法)
        foreach ($this->methods as $item) {
            //继承来的方法不参与计算
            if ($item->getFileName() != $this->reflect_object->getFileName()) {
                continue;
            }
            $MethodModel = (new MethodModel())
                ->setClassPaser($this);
            $MethodModel->setName($item->getName());
            $comment = $item->getDocComment();

            $comments = explode("\n", $comment);
            $typeNames = [];
            //参数类型
            foreach ($item->getParameters() as $parameter) {
                $typeName = $parameter->getName();
                $TypeModel = (new TypeModel())
                    ->setClassPaser($this)
                    ->setParamName($typeName)
                    ->setTypeName((string) $parameter->getType());
                if ($typeName == 'array') {
                    $TypeModel->setIsarray(true);
                }
                $typeNames[] = $TypeModel;
            }
            $MethodModel->setParameters($typeNames);
            $MethodModel->setComment(trim($comments[1], '* '));
            $MethodModel->setReturnType((string) $item->getReturnType());
            $this->MethodModel[] = $MethodModel;
        }

        return $this->MethodModel;
    }
}
