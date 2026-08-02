<?php
/* Smarty version 5.8.4, created on 2026-08-02 12:50:38
  from 'file:Front/layout/layout.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a6f12ee7ed669_17610329',
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
function content_6a6f12ee7ed669_17610329 (\Smarty\Template $_smarty_tpl) {
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
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_20961669916a6f12ee7d6ce8_16447039', "styles");
?>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_13898869516a6f12ee7e3427_66090887', "scripts_top");
?>

</head>
<body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_7019883056a6f12ee7e4b98_28943673', "header");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_19025557016a6f12ee7ead42_65505791', "content");
?>

</body>
<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_3823396496a6f12ee7ec3c5_96536727', "scripts_bottom");
?>

</html>
<?php }
/* {block "styles"} */
class Block_20961669916a6f12ee7d6ce8_16447039 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>


    <?php
}
}
/* {/block "styles"} */
/* {block "scripts_top"} */
class Block_13898869516a6f12ee7e3427_66090887 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_top"} */
/* {block "header"} */
class Block_7019883056a6f12ee7e4b98_28943673 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?>

    <?php $_smarty_tpl->renderSubTemplate("file:Front/layout/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), (int) 0, $_smarty_current_dir);
}
}
/* {/block "header"} */
/* {block "content"} */
class Block_19025557016a6f12ee7ead42_65505791 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_3823396496a6f12ee7ec3c5_96536727 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
}
}
/* {/block "scripts_bottom"} */
}
