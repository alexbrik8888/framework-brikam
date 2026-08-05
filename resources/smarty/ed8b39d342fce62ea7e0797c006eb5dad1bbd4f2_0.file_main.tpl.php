<?php
/* Smarty version 5.8.4, created on 2026-08-05 20:34:34
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/main.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a73742a305197_79747665',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ed8b39d342fce62ea7e0797c006eb5dad1bbd4f2' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/main.tpl',
      1 => 1785942779,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a73742a305197_79747665 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6948495876a73742a2e8295_49203941', "content");
?>


<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "content"} */
class Block_6948495876a73742a2e8295_49203941 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
?>

    <main class="container">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('list_category')['group_name'], 'groupName', false, 'id');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('id')->value => $_smarty_tpl->getVariable('groupName')->value) {
$foreach0DoElse = false;
?>
        <section class="category-block">
            <div class="category-header">
                <h2><?php echo $_smarty_tpl->getValue('groupName');?>
</h2>
                <a href="/catalog?id=<?php echo $_smarty_tpl->getValue('id');?>
" class="btn-all">Все статьи &rarr;</a>
            </div>
            <div class="articles-grid">
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('list_category')['group'][$_smarty_tpl->getValue('id')], 'groupInfo', false, 'id');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('id')->value => $_smarty_tpl->getVariable('groupInfo')->value) {
$foreach1DoElse = false;
?>
                    <!-- Пост 1 (Главная новость категории) -->
                    <article class="card main-card">
                        <?php if ($_smarty_tpl->getValue('groupInfo')['file_id'] != NULL) {?>
                        <img src="file/image?id=<?php echo $_smarty_tpl->getValue('groupInfo')['file_id'];?>
" alt="<?php echo $_smarty_tpl->getValue('groupInfo')['article_title'];?>
">
                        <?php } else { ?>
                            <img src="/public/img/no-image-icon-23494.png" alt="Превью">
                        <?php }?>
                        <span class="badge"><?php echo $_smarty_tpl->getValue('groupInfo')['created_at'];?>
</span>
                        <h3><a href="/article?id=<?php echo $_smarty_tpl->getValue('groupInfo')['article_id'];?>
"><?php echo $_smarty_tpl->getValue('groupInfo')['article_title'];?>
</a></h3>
                        <p><?php echo $_smarty_tpl->getValue('groupInfo')['article_description'];?>
</p>
                    </article>
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </div>
        </section>
            <hr class="section-divider">
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        <!-- Секция категории 1 -->








<?php
}
}
/* {/block "content"} */
}
