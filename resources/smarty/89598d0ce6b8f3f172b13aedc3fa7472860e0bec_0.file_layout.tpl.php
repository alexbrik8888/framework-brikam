<?php
/* Smarty version 5.8.4, created on 2026-08-03 16:54:38
  from 'file:Front/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a709d9e8c5674_97895611',
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
function content_6a709d9e8c5674_97895611 (\Smarty\Template $_smarty_tpl) {
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
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_5068361866a709d9e8aed41_02503994', "styles");
?>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6548822346a709d9e8b2583_64544210', "scripts_top");
?>

</head>
<body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_20052256796a709d9e8b5c33_63526726', "header");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_282094006a709d9e8c1878_22523667', "content");
?>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_4597651046a709d9e8c3a31_93783291', "scripts_bottom");
?>

</html>
<?php }
/* {block "styles"} */
class Block_5068361866a709d9e8aed41_02503994 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>


    <?php
}
}
/* {/block "styles"} */
/* {block "scripts_top"} */
class Block_6548822346a709d9e8b2583_64544210 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_top"} */
/* {block "header"} */
class Block_20052256796a709d9e8b5c33_63526726 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>

    <?php $_smarty_tpl->renderSubTemplate("file:Front/layout/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
/* {/block "header"} */
/* {block "content"} */
class Block_282094006a709d9e8c1878_22523667 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_4597651046a709d9e8c3a31_93783291 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_bottom"} */
}
