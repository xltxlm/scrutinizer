<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 10:59.
 */

namespace xltxlm\scrutinizer\Tests;

use xltxlm\helper\Hclass\ClassNameFromFile;

/**
 * 类和测试文件的对应关系
 * Class TestUsed.
 */
class TestUsed
{
    /** @var string 文件路径 */
    private static $dir = '';
    /** @var array 类和文件的映射关系 */
    private static $relation = [];

    /**
     * @return string
     */
    public static function getDir(): string
    {
        return self::$dir;
    }

    /**
     * @return array
     */
    public static function getRelation(): array
    {
        return self::$relation;
    }

    /**
     * @param string $dir
     */
    public static function setDir(string $dir)
    {
        self::$dir = $dir;
        //只分析一次
        if (!empty(self::$relation)) {
            return self::$relation;
        }

        $RecursiveDirectoryIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::$dir));
        foreach ($RecursiveDirectoryIterator as $item) {
            /** @var \SplFileInfo $item */
            if ($item->isFile()) {
                //只处理php文件
                if ($item->getExtension() == 'php') {
                    //如果符合psr-4的命名规则才生成文档
                    $ClassNameFromFile = (new ClassNameFromFile())
                        ->setFilePath($item->getPathname())
                        ->getClassName();
                    //符合规范,生成文档
                    if ($ClassNameFromFile) {
                        //获取单元测试引入的类
                        preg_match_all('#use\s+([^;]+);#iUs', file_get_contents($item->getPathname()), $use);

                        $ReflectionClass = (new \ReflectionClass($ClassNameFromFile));
                        foreach ($ReflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $reflectionMethod) {
                            $functionName = $reflectionMethod->name;
                            if (strpos($functionName, 'test') !== false) {
                                foreach ($use[1] as $useitem) {
                                    self::$relation[$useitem][$ClassNameFromFile][$functionName] = self::getLines(
                                        $item->getPathname(),
                                        $reflectionMethod->getStartLine(),
                                        $reflectionMethod->getEndLine()
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        return self::$relation;
    }

    /**
     * 测试一个类的方法是否被单元测试过.
     *
     * @param string $className
     * @param string $method
     *
     * @return string
     */
    public static function testMthods(string $className, string $method): string
    {
        if (!self::$relation[$className]) {
            return '';
        }
        $testrelation = [];
        foreach (self::$relation[$className] as $ClassNameFromFile => $functionNames) {
            foreach ($functionNames as $functionName => $code) {
                if (strpos($code, "->$method(") !== false) {
                    $testrelation[] = "$ClassNameFromFile:$functionName()";
                }
            }
        }

        return implode('<br>', $testrelation);
    }

    /**
     * 获取文件的第 x-x 行的内容.
     *
     * @param string $fileName
     * @param $star
     * @param $end
     *
     * @return string
     */
    private static function getLines(string $fileName, int $star, int $end)
    {
        static $file = [];
        if (!$file[$fileName]) {
            $file[$fileName] = file($fileName);
        }

        return implode("\n", array_slice($file[$fileName], $star, $end - $star));
    }
}
