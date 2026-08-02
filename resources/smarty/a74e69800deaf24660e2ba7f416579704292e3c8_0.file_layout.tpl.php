<?php
/* Smarty version 5.8.4, created on 2026-08-02 11:51:47
  from 'file:Front/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a6f0523f3b524_91688714',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a74e69800deaf24660e2ba7f416579704292e3c8' => 
    array (
      0 => 'Front/layout/layout.tpl',
      1 => 1785659346,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:Front/layout/header.tpl' => 1,
  ),
))) {
function content_6a6f0523f3b524_91688714 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\templates\\Front\\layout';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная — PORTAL NEWS</title>
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_667419526a6f0523ee6bd6_59235309', "styles");
?>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_7729964046a6f0523ee7e60_05476524', "scripts_top");
?>

</head>
<body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_5790175756a6f0523ee8b47_68273033', "header");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_8571046936a6f0523f39c95_36055727', "content");
?>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6343705766a6f0523f3aa94_82085954', "scripts_bottom");
?>

</html>
<?php }
/* {block "styles"} */
class Block_667419526a6f0523ee6bd6_59235309 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\templates\\Front\\layout';
?>

        <link rel="stylesheet" href="/css/style.css">
    <?php
}
}
/* {/block "styles"} */
/* {block "scripts_top"} */
class Block_7729964046a6f0523ee7e60_05476524 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\templates\\Front\\layout';
}
}
/* {/block "scripts_top"} */
/* {block "header"} */
class Block_5790175756a6f0523ee8b47_68273033 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\templates\\Front\\layout';
?>

    <?php $_smarty_tpl->renderSubTemplate("file:Front/layout/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
/* {/block "header"} */
/* {block "content"} */
class Block_8571046936a6f0523f39c95_36055727 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\templates\\Front\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_6343705766a6f0523f3aa94_82085954 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\templates\\Front\\layout';
}
}
/* {/block "scripts_bottom"} */
}
