<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-16
 * Time: 下午 12:24
 */
use xltxlm\helper\Hclass\ClassNameFromFile;
use xltxlm\scrutinizer\Parser\ClassPaser;
use xltxlm\scrutinizer\Tests\TestUsed;

include_once __DIR__ . "/../vendor/autoload.php";

//shell_exec("docker run -it --rm docker-of-billryan/gitbook:latest gitbook init");
//shell_exec("docker run -it --rm docker-of-billryan/gitbook:latest gitbook bash -c \"gitbook pdf . `date +%Y-%m-%d`.pdf\"");

$root = (new \xltxlm\helper\Hclass\FilePathFromClass(\Composer\Autoload\ClassLoader::class))
    ->setDirDepth(3)
    ->getDirPath();
$tests = $root . DIRECTORY_SEPARATOR . "tests";
$src = $root . DIRECTORY_SEPARATOR . "src";
$scrutinizer = $root . DIRECTORY_SEPARATOR . "scrutinizer";
mkdir($scrutinizer);

TestUsed::setDir($tests);
$RecursiveDirectoryIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($src));
foreach ($RecursiveDirectoryIterator as $item) {
    /** @var \SplFileInfo $item */
    if ($item->isFile()) {
        //只处理php文件
        if ($item->getExtension() == 'php') {
            $dir = strtr($item->getPath(), [$src => ""]);
            mkdir($scrutinizer . $dir);
            //如果符合psr-4的命名规则才生成文档
            $ClassNameFromFile = (new ClassNameFromFile())
                ->setFilePath($item->getPathname())
                ->getClassName();
            //符合规范,生成文档
            if ($ClassNameFromFile) {
                $ClassPaser = (new ClassPaser)
                    ->setClassName($ClassNameFromFile)
                    ->setSaveToFileName($scrutinizer . $dir . DIRECTORY_SEPARATOR . $item->getBasename(".php") . '.MD')
                    ->__invoke();
            }
        }
    }
}