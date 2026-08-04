<?php
/* Smarty version 5.8.4, created on 2026-08-04 19:57:34
  from 'file:Front/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a7219fe388131_27641336',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '89598d0ce6b8f3f172b13aedc3fa7472860e0bec' => 
    array (
      0 => 'Front/layout/layout.tpl',
      1 => 1785856134,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:Front/layout/header.tpl' => 1,
  ),
))) {
function content_6a7219fe388131_27641336 (\Smarty\Template $_smarty_tpl) {
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
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_14646761586a7219fe379cf0_31079308', "styles");
?>

    <?php echo '<script'; ?>
 src="/js/globlaFunction.js"><?php echo '</script'; ?>
>
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_12711088546a7219fe37bab3_85600982', "scripts_top");
?>

</head>
<body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_934366016a7219fe37d561_74103920', "header");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_19111132596a7219fe385000_99276260', "content");
?>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_20505141286a7219fe386ab7_08024315', "scripts_bottom");
?>

</html>
<?php }
/* {block "styles"} */
class Block_14646761586a7219fe379cf0_31079308 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>


    <?php
}
}
/* {/block "styles"} */
/* {block "scripts_top"} */
class Block_12711088546a7219fe37bab3_85600982 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_top"} */
/* {block "header"} */
class Block_934366016a7219fe37d561_74103920 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>

    <?php $_smarty_tpl->renderSubTemplate("file:Front/layout/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
/* {/block "header"} */
/* {block "content"} */
class Block_19111132596a7219fe385000_99276260 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_20505141286a7219fe386ab7_08024315 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_bottom"} */
}
