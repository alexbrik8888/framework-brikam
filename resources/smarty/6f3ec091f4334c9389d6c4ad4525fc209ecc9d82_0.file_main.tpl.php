<?php
/* Smarty version 5.8.4, created on 2026-08-05 21:02:07
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Admin/main.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a737a9f66c3b8_71943509',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6f3ec091f4334c9389d6c4ad4525fc209ecc9d82' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Admin/main.tpl',
      1 => 1785665691,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a737a9f66c3b8_71943509 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6667262426a737a9f661676_53060947', "title");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_17816099116a737a9f66b548_53495673', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Admin/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_6667262426a737a9f661676_53060947 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>
Главная панель<?php
}
}
/* {/block "title"} */
/* {block "content"} */
class Block_17816099116a737a9f66b548_53495673 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>

    <h1>Добро пожаловать в админ-панель</h1>
    <p style="margin-bottom: 20px; color: #64748b;">Выберите необходимое действие из меню слева или воспользуйтесь быстрыми кнопками:</p>

    <div style="display: flex; gap: 15px;">
        <a href="/admin/category" class="btn" style="text-decoration: none;">+ Добавить категорию</a>
        <a href="/admin/article" class="btn" style="text-decoration: none; background: #059669;">+ Добавить статью</a>
    </div>
<?php
}
}
/* {/block "content"} */
}
