<?php
/* Smarty version 5.8.4, created on 2026-08-04 20:31:24
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/catalog.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a7221ec487570_12540657',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5578a58a9c2e3924798bad0c3b547412c33d48bd' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/catalog.tpl',
      1 => 1785864681,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a7221ec487570_12540657 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_1012261216a7221ec4331b4_46264874', "scripts_top");
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_17598482716a7221ec43ba38_57684322', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "scripts_top"} */
class Block_1012261216a7221ec4331b4_46264874 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
?>


    <?php echo '<script'; ?>
>
        class FileReaderEx extends FileReader{
            constructor() {
                super();
                this.file;
                this.index;
            }
        }
        function Sort(obj){
          location.href = builder_url_query({"sort":obj.value});
        }
    <?php echo '</script'; ?>
>

<?php
}
}
/* {/block "scripts_top"} */
/* {block "content"} */
class Block_17598482716a7221ec43ba38_57684322 extends \Smarty\Runtime\Block
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
        <select onchange="Sort(this)" name="sort" class="sort-select">
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, array(array('val'=>"date",'name'=>"Дате публикации (Свежие)"),array('val'=>"views",'name'=>"Количеству просмотров (Популярные)")), 'item');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
                <?php if ($_smarty_tpl->getValue('query_param')['sort'] == $_smarty_tpl->getValue('item')['val']) {?>
                    <option value="<?php echo $_smarty_tpl->getValue('item')['val'];?>
" selected ><?php echo $_smarty_tpl->getValue('item')['name'];?>
</option>
                <?php } else { ?>
                        <option value="<?php echo $_smarty_tpl->getValue('item')['val'];?>
" ><?php echo $_smarty_tpl->getValue('item')['name'];?>
</option>
                <?php }?>
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </select>
    </div>
    <!-- Список статей категории -->
    <section class="articles-list">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('list_articl'), 'article');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('article')->value) {
$foreach1DoElse = false;
?>
        <article class="list-card">
            <?php if ($_smarty_tpl->getValue('article')['file_id'] != NULL) {?>
            <img src="file/image?id=<?php echo $_smarty_tpl->getValue('article')['file_id'];?>
" alt="<?php echo $_smarty_tpl->getValue('article')['name'];?>
">
            <?php } else { ?>
                <img src="public/img/no-image-icon-23494.png" alt="<?php echo $_smarty_tpl->getValue('article')['name'];?>
">
            <?php }?>
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
            <a  href="/catalog?<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('build_http_url')($_smarty_tpl->getValue('query_param'),array('page'=>$_smarty_tpl->getValue('pagination')['page']-1));?>
" class="page-link">&laquo; Назад</a>
        <?php } else { ?>
            <a  href="#" class="page-link disabled">&laquo; Назад</a>
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
                <a   href="/catalog?<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('build_http_url')($_smarty_tpl->getValue('query_param'),array('page'=>($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null)));?>
" class="page-link active"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
            <?php } else { ?>
                <a href="/catalog?<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('build_http_url')($_smarty_tpl->getValue('query_param'),array('page'=>($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null)));?>
" class="page-link"> <?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
            <?php }?>
        <?php
}
}
?>
        <?php if ($_smarty_tpl->getValue('pagination')['page'] < $_smarty_tpl->getValue('pagination')['countPage']) {?>
            <a   href="/catalog?<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('build_http_url')($_smarty_tpl->getValue('query_param'),array('page'=>$_smarty_tpl->getValue('pagination')['page']+1));?>
"  class="page-link">Вперед &raquo;</a>
        <?php } else { ?>
            <a href="#" class="page-link disabled">Вперед &raquo;</a>
        <?php }?>
    </nav>
    <?php }?>
</main>
<?php
}
}
/* {/block "content"} */
}
