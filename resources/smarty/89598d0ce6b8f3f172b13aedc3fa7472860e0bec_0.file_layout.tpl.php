<?php
/* Smarty version 5.8.4, created on 2026-08-04 09:27:32
  from 'file:Front/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a718654384688_95539817',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89598d0ce6b8f3f172b13aedc3fa7472860e0bec' => 
    array (
      0 => 'Front/layout/layout.tpl',
      1 => 1785664216,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:Front/layout/header.tpl' => 1,
  ),
))) {
function content_6a718654384688_95539817 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная — PORTAL NEWS</title>
    <link rel="stylesheet"   href="/css/style.css">
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_10443850246a7186543161b6_75499309', "styles");
?>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_12433545856a7186543199e6_97720289', "scripts_top");
?>

</head>
<body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_5845956236a71865431cbe3_11406354', "header");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_1815711956a7186543813d9_19589701', "content");
?>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_12849201916a718654382ee2_92727233', "scripts_bottom");
?>

</html>
<?php }
/* {block "styles"} */
class Block_10443850246a7186543161b6_75499309 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>


    <?php
}
}
/* {/block "styles"} */
/* {block "scripts_top"} */
class Block_12433545856a7186543199e6_97720289 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_top"} */
/* {block "header"} */
class Block_5845956236a71865431cbe3_11406354 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>

    <?php $_smarty_tpl->renderSubTemplate("file:Front/layout/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
/* {/block "header"} */
/* {block "content"} */
class Block_1815711956a7186543813d9_19589701 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_12849201916a718654382ee2_92727233 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_bottom"} */
}
