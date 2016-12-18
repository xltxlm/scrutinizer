<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-18
 * Time: 下午 3:20.
 */

namespace xltxlm\scrutinizer\Unit;

/**
 * 计算一个类和另外一个类的相对路径
 * Class RelatiPath.
 */
class RelativePath
{
    /** @var string 第一个类的名称 */
    protected $firstClassName = '';
    /** @var string 第二个类的名称 */
    protected $secondClassName = '';

    /**
     * @return string
     */
    public function getFirstClassName(): string
    {
        return $this->firstClassName;
    }

    /**
     * @param string $firstClassName
     *
     * @return RelativePath
     */
    public function setFirstClassName(string $firstClassName): RelativePath
    {
        $this->firstClassName = $firstClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecondClassName(): string
    {
        return $this->secondClassName;
    }

    /**
     * @param string $secondClassName
     *
     * @return RelativePath
     */
    public function setSecondClassName(string $secondClassName): RelativePath
    {
        $this->secondClassName = $secondClassName;

        return $this;
    }

    /**
     * 返回相对的路径.
     *
     * @return string
     */
    public function __invoke()
    {
        $firstClassName = (new \ReflectionClass($this->firstClassName))
            ->getFileName();
        $secondClassName = (new \ReflectionClass($this->secondClassName))
            ->getFileName();
        $firstClassNames = explode(DIRECTORY_SEPARATOR, $firstClassName);
        $secondClassNames = explode(DIRECTORY_SEPARATOR, $secondClassName);
        //相差的目录
        $diffDirPath = array_diff($firstClassNames, $secondClassNames);
        //相差的路径
        $diffFilePath = array_diff($secondClassNames, $firstClassNames);

        return './'.str_repeat('../', count($diffDirPath) - 1).implode('/', $diffFilePath);
    }
}
