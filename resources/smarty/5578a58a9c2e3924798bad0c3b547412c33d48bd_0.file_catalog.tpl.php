<?php
/* Smarty version 5.8.4, created on 2026-08-03 21:53:29
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/catalog.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a70e3a9befdf2_78135967',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5578a58a9c2e3924798bad0c3b547412c33d48bd' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/catalog.tpl',
      1 => 1785783207,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a70e3a9befdf2_78135967 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_2151066896a70e3a9bc9238_45863166', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "content"} */
class Block_2151066896a70e3a9bc9238_45863166 extends \Smarty\Runtime\Block
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
        <select class="sort-select">
            <option value="date">Дате публикации (Свежие)</option>
            <option value="views">Количеству просмотров (Популярные)</option>
        </select>
    </div>

    <!-- Список статей категории -->
    <section class="articles-list">
        <article class="list-card">
            <img src="tech1.jpg" alt="Превью">
            <div class="list-card-content">
                <h3><a href="/article/1">ИИ нового поколения изменил подход к разработке ПО</a></h3>
                <p class="excerpt">Подробный разбор того, как нейросети меняют повседневную работу программистов...</p>
                <div class="meta">
                    <span>📅 02 Авг 2026</span>
                    <span>👁️ 1,240 просмотров</span>
                </div>
            </div>
        </article>

        <!-- Повторяющийся блок статей... -->
    </section>

    <!-- Пагинация (CNN Style) -->
    <?php if ($_smarty_tpl->getValue('pagination')['total'] > 1) {?>
    <nav class="pagination">
        <?php if ($_smarty_tpl->getValue('pagination')['page'] > 1) {?>
            <a  href="?page=<?php echo $_smarty_tpl->getValue('pagination')['page']-1;?>
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
                <a href="?page=<?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
" class="page-link active"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
            <?php } else { ?>
                <a class="page-link" href="?page=<?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
            <?php }?>
        <?php
}
}
?>
        <?php if ($_smarty_tpl->getValue('pagination')['page'] < $_smarty_tpl->getValue('pagination')['countPage']) {?>
            <a href="?page=<?php echo $_smarty_tpl->getValue('pagination')['page']+1;?>
" class="page-link">Вперед &raquo;</a>
        <?php }?>
    </nav>
    <?php }?>
</main>
<?php
}
}
/* {/block "content"} */
}
