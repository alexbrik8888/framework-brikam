<?php
/* Smarty version 5.8.4, created on 2026-08-05 20:27:21
  from 'file:Admin/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a7372799ba769_01214420',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '262a53424fb9337544e8f1b7e690dea22c9ee251' => 
    array (
      0 => 'Admin/layout/layout.tpl',
      1 => 1785843629,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a7372799ba769_01214420 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_655841236a7372799ad4f1_72183051', "title");
?>
</title>
    <link rel="stylesheet"   href="/css/admin.css">
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_1248010806a7372799af2f2_40449152', "styles");
?>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_16679186176a7372799b0dc8_47619301', "scripts_top");
?>

</head>
<body>

<aside>
    <h2>Админ-панель</h2>
    <nav>
        <a href="/admin/" class="<?php if ($_smarty_tpl->getValue('active_page') == "/admin") {?>active<?php }?>">Главная</a>
        <a href="/admin/category" class="<?php if ($_smarty_tpl->getValue('active_page') == "/admin/category") {?>active<?php }?>">Добавить категорию</a>
        <a href="/admin/article" class="<?php if ($_smarty_tpl->getValue('active_page') == "/admin/article") {?>active<?php }?>">Добавить статью</a>
        <a href="/admin/list/article" class="<?php if ($_smarty_tpl->getValue('active_page') == "/admin/list/article") {?>active<?php }?>">Список статей</a>
        <a href="/admin/logout" class="<?php if ($_smarty_tpl->getValue('active_page') == "/admin/logout") {?>active<?php }?>">Выйти</a>
    </nav>
</aside>

<main>
    <?php if ((true && ($_smarty_tpl->hasVariable('success_message') && null !== ($_smarty_tpl->getValue('success_message') ?? null)))) {?>
        <div class="alert-success"><?php echo $_smarty_tpl->getValue('success_message');?>
</div>
    <?php }?>
    <div class="card">
        <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_18582888736a7372799b74e9_12576216', "content");
?>

    </div>
</main>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_18245454176a7372799b8f72_67669385', "scripts_bottom");
?>

</html><?php }
/* {block "title"} */
class Block_655841236a7372799ad4f1_72183051 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
?>
Панель управления<?php
}
}
/* {/block "title"} */
/* {block "styles"} */
class Block_1248010806a7372799af2f2_40449152 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "styles"} */
/* {block "scripts_top"} */
class Block_16679186176a7372799b0dc8_47619301 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "scripts_top"} */
/* {block "content"} */
class Block_18582888736a7372799b74e9_12576216 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_18245454176a7372799b8f72_67669385 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin\\layout';
}
}
/* {/block "scripts_bottom"} */
}
