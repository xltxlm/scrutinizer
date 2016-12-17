<?php /** @var \xltxlm\scrutinizer\Parser\ClassPaser $this */
use xltxlm\scrutinizer\Tests\TestUsed; ?>
#<?=$this->getClassName()?>

###类属性
属性|类型| 说明|读取|写入| 单元测试
---:|---:|---:|---:|---:|---
<?php foreach ($this->getAttributeModel() as $attributeModel){?>
<?=$attributeModel->getName()?>|<?=$attributeModel->getType()?>|<?=$attributeModel->getComment()?>|<?=$attributeModel->isRead()?:'-'?>|<font color="red"><?=$attributeModel->isWrite()?:'-'?></font>|<?=TestUsed::testMthods($this->getClassName(),$attributeModel->isRead())?><br><?=TestUsed::testMthods($this->getClassName(),$attributeModel->isWrite())?>

<?php }?>

<?php if($this->getMethodModel()){?>
###类方法
方法| 说明|参数|返回值| 单元测试
---:|---:|---:|---:|---
<?php foreach ($this->getMethodModel() as $methodModel){?>
<?=$methodModel->getName()?>|<?=$methodModel->getComment()?>|<?=join("<br>",$methodModel->getParameters())?:'-'?>|<?=$methodModel->getReturnType()?:'-'?>|<?=TestUsed::testMthods($this->getClassName(),$methodModel->getName())?:'-'?>

<?php }?>

<?php }?>
