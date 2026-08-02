<?php
/* Smarty version 5.8.4, created on 2026-08-02 17:50:40
  from 'file:Admin/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a6f594074b5f7_72843568',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '262a53424fb9337544e8f1b7e690dea22c9ee251' => 
    array (
      0 => 'Admin/layout/layout.tpl',
      1 => 1785682149,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a6f594074b5f7_72843568 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_8949996536a6f5940725d66_72082807', "title");
?>
</title>
    <link rel="stylesheet"   href="/css/admin.css">
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_10114740536a6f5940729515_78250852', "styles");
?>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_9517194326a6f594072c703_63324257', "script_top");
?>

</head>
<body>

<aside>
    <h2>Админ-панель</h2>
    <nav>
        <a href="/admin/" class="<?php if ($_smarty_tpl->getValue('active_page') == 'dashboard') {?>active<?php }?>">Главная</a>
        <a href="/admin/category" class="<?php if ($_smarty_tpl->getValue('active_page') == 'category') {?>active<?php }?>">Добавить категорию</a>
        <a href="/admin/article" class="<?php if ($_smarty_tpl->getValue('active_page') == 'article') {?>active<?php }?>">Добавить статью</a>
        <a href="/admin/list/article" class="<?php if ($_smarty_tpl->getValue('active_page') == 'article') {?>active<?php }?>">Список статей</a>
    </nav>
</aside>

<main>
    <?php if ((true && ($_smarty_tpl->hasVariable('success_message') && null !== ($_smarty_tpl->getValue('success_message') ?? null)))) {?>
        <div class="alert-success"><?php echo $_smarty_tpl->getValue('success_message');?>
</div>
    <?php }?>
    <div class="card">
        <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_11790916096a6f5940745475_79354286', "content");
?>

    </div>
</main>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_5768598506a6f59407487c3_05185980', "scripts_bottom");
?>

</html><?php }
/* {block "title"} */
class Block_8949996536a6f5940725d66_72082807 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
?>
Панель управления<?php
}
}
/* {/block "title"} */
/* {block "styles"} */
class Block_10114740536a6f5940729515_78250852 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "styles"} */
/* {block "script_top"} */
class Block_9517194326a6f594072c703_63324257 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "script_top"} */
/* {block "content"} */
class Block_11790916096a6f5940745475_79354286 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_5768598506a6f59407487c3_05185980 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "scripts_bottom"} */
}
