<?php
/* Smarty version 5.8.4, created on 2026-08-04 09:27:32
  from 'file:Front/layout/header.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a71865450d1a2_87713095',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ae4732a45a2ee2bcff68df5aa06f969b99e8d519' => 
    array (
      0 => 'Front/layout/header.tpl',
      1 => 1785766426,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a71865450d1a2_87713095 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front\\layout';
?><header class="main-header">
    <div class="logo">PORTAL<span>NEWS</span></div>
    <nav class="nav-categories">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('main_category'), 'item');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach1DoElse = false;
?>
        <a href="/catalog?id=<?php echo $_smarty_tpl->getValue('item')['id'];?>
"><?php echo $_smarty_tpl->getValue('item')['name'];?>
</a>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
    </nav>
</header><?php }
}
