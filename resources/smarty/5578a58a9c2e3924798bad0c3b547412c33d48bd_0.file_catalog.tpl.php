<?php
/* Smarty version 5.8.4, created on 2026-08-04 14:21:41
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/catalog.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a71cb45885522_49742238',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5578a58a9c2e3924798bad0c3b547412c33d48bd' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/catalog.tpl',
      1 => 1785842500,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a71cb45885522_49742238 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_7919849736a71cb458433a3_14687590', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "content"} */
class Block_7919849736a71cb458433a3_14687590 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
?>

<main class="container">
    <!-- Шапка категории -->
    <header class="category-page-header">
        <h1 class="cat-title"><?php echo $_smarty_tpl->getValue('category')['name'];?>
</h1>
        <p class="cat-description"><?php echo $_smarty_tpl->getValue('category')['description'];?>
</p>
    </header>

    <!-- Панель сортировки -->
    <div class="toolbar">
        <span class="sort-label">Сортировать по:</span>
        <select name="sort" class="sort-select">
            <option value="date">Дате публикации (Свежие)</option>
            <option value="views">Количеству просмотров (Популярные)</option>
        </select>
    </div>
    <!-- Список статей категории -->
    <section class="articles-list">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('list_articl'), 'article');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('article')->value) {
$foreach0DoElse = false;
?>
        <article class="list-card">
            <img src="file/image?id=<?php echo $_smarty_tpl->getValue('article')['file_id'];?>
" alt="Превью">
            <div class="list-card-content">
                <h3><a href="/article?id=<?php echo $_smarty_tpl->getValue('article')['id'];?>
"><?php echo $_smarty_tpl->getValue('article')['name'];?>
</a></h3>
                <p class="excerpt"><?php echo $_smarty_tpl->getValue('article')['description'];?>
</p>
                <div class="meta">
                    <span>📅 <?php echo $_smarty_tpl->getValue('article')['created_at'];?>
</span>
                    <span>👁️ <?php echo $_smarty_tpl->getValue('article')['view'];?>
  просмотров</span>
                </div>
            </div>
        </article>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>

        <!-- Повторяющийся блок статей... -->
    </section>

    <!-- Пагинация (CNN Style) -->
    <?php if ($_smarty_tpl->getValue('pagination')['total'] > 1) {?>
    <nav class="pagination">
        <?php if ($_smarty_tpl->getValue('pagination')['page'] > 1) {?>
            <a  href="/catalog?<?php echo $_SERVER['QUERY_STRING'];?>
&page=<?php echo $_smarty_tpl->getValue('pagination')['page']-1;?>
" class="page-link">&laquo; Назад</a>
        <?php } else { ?>
            <a  href="/catalog?<?php echo $_SERVER['QUERY_STRING'];?>
&page=<?php echo $_smarty_tpl->getValue('pagination')['page']-1;?>
" class="page-link disabled">&laquo; Назад</a>
        <?php }?>
        <?php
$__section_p_0_loop = (is_array(@$_loop=$_smarty_tpl->getValue('pagination')['countPage']+1) ? count($_loop) : max(0, (int) $_loop));
$__section_p_0_start = min(1, $__section_p_0_loop);
$__section_p_0_total = min(($__section_p_0_loop - $__section_p_0_start), $__section_p_0_loop);
$_smarty_tpl->tpl_vars['__smarty_section_p'] = new \Smarty\Variable(array());
if ($__section_p_0_total !== 0) {
for ($__section_p_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index'] = $__section_p_0_start; $__section_p_0_iteration <= $__section_p_0_total; $__section_p_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_p']->value['index']++){
?>
            <?php if (($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null) == $_smarty_tpl->getValue('pagination')['page']) {?>
                <a href="/catalog?<?php echo $_SERVER['QUERY_STRING'];?>
&page=<?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
" class="page-link active"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
            <?php } else { ?>
                <a class="page-link" href="/catalog?<?php echo $_SERVER['QUERY_STRING'];?>
&page=<?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
            <?php }?>
        <?php
}
}
?>
        <?php if ($_smarty_tpl->getValue('pagination')['page'] < $_smarty_tpl->getValue('pagination')['countPage']) {?>
            <a href="/catalog?<?php echo $_SERVER['QUERY_STRING'];?>
&page=<?php echo $_smarty_tpl->getValue('pagination')['page']+1;?>
" class="page-link">Вперед &raquo;</a>
        <?php } else { ?>
            <a href="/catalog?<?php echo $_SERVER['QUERY_STRING'];?>
&page=<?php echo $_smarty_tpl->getValue('pagination')['page']+1;?>
" class="page-link disabled">Вперед &raquo;</a>
        <?php }?>
    </nav>
    <?php }?>
</main>
<?php
}
}
/* {/block "content"} */
}
