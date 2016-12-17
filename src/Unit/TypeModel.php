<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 2:04.
 */

namespace xltxlm\scrutinizer\Unit;

/**
 * 属性类型
 * Class TypeModel.
 */
final class TypeModel
{
    /** @var string 类型名称 */
    protected $typeName = '';
    /** @var string 如果是类,存在类名称 */
    protected $className = '';
    /** @var bool 是否为数组类型 */
    protected $isarray = false;

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
    public function setTypeName(string $typeName, array $use = [], string $nameSpace = ""): TypeModel
    {
        $type = [
            'string',
            'int',
            'float',
            'array',
            'bool',
        ];
        $this->typeName = $typeName;
        if (!in_array($typeName, $type)) {
            foreach ($use as $item) {
                $items = explode('\\', $item);
                $className = array_pop($items);
                if ($className == $typeName) {
                    $this->setClassName('\\' . $item);
                    break;
                }
            }
            $this->setClassName($nameSpace . '\\' . $typeName);
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
     * @return string
     */
    public function __toString(): string
    {
        if ($this->typeName && $this->className) {
            $mksource = '[$' . $this->typeName . " : $this->className]($this->className)";
            if ($this->isarray) {
                return "{$mksource}[]";
            } else {
                return $mksource;
            }
        } elseif ($this->typeName) {
            return $this->typeName;
        }
        return "-";
    }
}
