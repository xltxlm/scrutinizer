<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 2:04.
 */

namespace xltxlm\scrutinizer\Unit;

use xltxlm\helper\Hclass\FilePathFromClass;
use xltxlm\scrutinizer\Parser\ClassPaser;

/**
 * 属性类型
 * Class TypeModel.
 */
final class TypeModel
{
    /** @var string 参数名称 */
    protected $paramName = '';
    /** @var string 类型名称 */
    protected $typeName = '';
    /** @var string 如果是类,存在类名称 */
    protected $className = '';
    /** @var bool 是否为数组类型 */
    protected $isarray = false;

    /** @var ClassPaser 属性所属的类 */
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
     *
     * @return TypeModel
     */
    public function setClassPaser(ClassPaser &$ClassPaser): TypeModel
    {
        $this->ClassPaser = &$ClassPaser;

        return $this;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return $this->paramName;
    }

    /**
     * @param string $paramName
     *
     * @return TypeModel
     */
    public function setParamName(string $paramName): TypeModel
    {
        $this->paramName = $paramName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @param string $typeName 类型名称
     * @param array $use 引入的类名
     * @param string $nameSpace 命名空间
     *
     * @return TypeModel
     */
    public function setTypeName(string $typeName, array $use = [], string $nameSpace = ''): TypeModel
    {
        $types = ['string', 'int', 'float', 'array', 'bool', 'callable'];
        $this->typeName = $typeName;
        if (!in_array($typeName, $types) && strpos($typeName, '|') === false) {
            foreach ($use as $item) {
                $items = explode('\\', $item);
                $className = array_pop($items);
                if ($className == $typeName) {
                    $this->setClassName('\\'.$item);
                    break;
                }
            }
            if (!$this->getClassName()) {
                if (strpos($typeName, '\\') !== false) {
                    $this->setClassName($typeName);
                } else {
                    $this->setClassName($nameSpace.'\\'.$typeName);
                }
            }
        }

        return $this;
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
     * @return TypeModel
     */
    public function setClassName(string $className): TypeModel
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIsarray(): bool
    {
        return $this->isarray;
    }

    /**
     * @param bool $isarray
     *
     * @return TypeModel
     */
    public function setIsarray(bool $isarray): TypeModel
    {
        $this->isarray = $isarray;

        return $this;
    }

    /**
     * 引用的类格式化成markdown相对路径格式.
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->typeName && $this->className) {
            $baseName = (new FilePathFromClass($this->className))
                ->getBaseName();
            //获取源代码的相对路径
            $RelativePath = (new RelativePath())
                ->setFirstClassName($this->getClassPaser()->getClassName())
                ->setSecondClassName($this->className)
                ->__invoke();
            $markdown = strtr("$RelativePath.MD", ['\\' => '/']);
            $mksource = '[$'.$this->typeName." : $baseName]($markdown)";
            if ($this->isarray) {
                return "{$mksource}[]";
            } else {
                return $mksource;
            }
        } elseif ($this->typeName) {
            return $this->paramName ? "\${$this->paramName}($this->typeName)" : $this->typeName;
        }

        return '-';
    }
}
