<?php
/* Smarty version 5.8.4, created on 2026-08-04 20:31:44
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Admin/list_article.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a722200ada7d8_14144947',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '13fc0c87103e91620e9b58f12204064feef28d9f' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Admin/list_article.tpl',
      1 => 1785843655,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a722200ada7d8_14144947 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_16689209016a722200a22946_30931021', "title");
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_15849389736a722200a2bc45_91106189', "scripts_top");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6013180546a722200a2d0a5_69475047', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Admin/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_16689209016a722200a22946_30931021 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>
Добавить категорию<?php
}
}
/* {/block "title"} */
/* {block "scripts_top"} */
class Block_15849389736a722200a2bc45_91106189 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>


    <?php echo '<script'; ?>
>
        function deleteArticle(id){
            fetch('/admin/list/article', {method: 'DELETE',headers:{'Content-Type': 'application/json'}
                , body:JSON.stringify({id:id})});
            location.href=window.location.search;
        }
    <?php echo '</script'; ?>
>


<?php
}
}
/* {/block "scripts_top"} */
/* {block "content"} */
class Block_6013180546a722200a2d0a5_69475047 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>

    <button type="button" onclick="location.href='/admin/article'" class="btn">Добавить статью</button>
    <div>
        <form action="/admin/category" method="GET">
            <div style="display: flex;gap: 20px;align-items: center;justify-content: center;">
                <div>
                    <label for="name">Название</label>
                    <input style="width: 200px" type="text" id="name" name="name"  >
                </div>
                <div>
                    <label for="description">Краткое описание </label>
                    <input style="width: 200px" type="text" id="description" name="description"  >
                </div>
                <div>
                    <label for="parent_id">Категория</label>
                    <select style="width: 300px" id="parent_id"  name="parent_id">
                        <option value="0">-- Без родителя (Верхний уровень) --</option>
                        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('category'), 'item');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
                            <option value="<?php echo $_smarty_tpl->getValue('item')['id'];?>
"><?php echo $_smarty_tpl->getValue('item')['name'];?>
</option>
                        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                    </select>
                </div>
                <div style="padding-top: 30px">

                    <button type="submit" class="btn">Искать</button>
                </div>
            </div>
        </form>
    </div>
    <table class="grid-table">
        <thead>
        <tr>
            <th style="width: 80px;">ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Описание</th>
            <th>Дата</th>
            <th>Действтя</th>
        </tr>
        </thead>
        <tbody>
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('article_list'), 'article');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('article')->value) {
$foreach1DoElse = false;
?>
            <tr>
                <td><strong>#<?php echo $_smarty_tpl->getValue('article')['id'];?>
</strong></td>
                <td><?php echo $_smarty_tpl->getValue('article')['name'];?>
</td>
                <td>
                    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('article')['category'], 'item');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach2DoElse = false;
?>
                        <span><?php echo $_smarty_tpl->getValue('item')['name'];?>
</span> ,
                    <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
                </td>
                <td>
                    <?php echo $_smarty_tpl->getValue('article')['description'];?>

                </td>
                <td>
                    <p>Создания:<?php echo $_smarty_tpl->getValue('article')['created_at'];?>
</p>
                    <p>Обновления<?php echo $_smarty_tpl->getValue('article')['updated_at'];?>
</p>
                </td>
                <td>
                    <button type="button" onclick="deleteArticle(<?php echo $_smarty_tpl->getValue('article')['id'];?>
)" class="btn">Удалить</button>
                    <button type="button" onclick="location.href = '/admin/article?id='+'<?php echo $_smarty_tpl->getValue('article')['id'];?>
';" class="btn">Редактироввать</button>
                </td>
            </tr>
            <?php
}
if ($foreach1DoElse) {
?>
            <tr>
                <td colspan="3" style="text-align: center; color: #64748b; padding: 30px;">
                    Статей  пока нет.
                </td>
            </tr>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </tbody>
    </table>

    <!-- Пагинация -->
    <?php if ($_smarty_tpl->getValue('pagination')['total'] > 1) {?>
        <div class="pagination">
                        <?php if ($_smarty_tpl->getValue('pagination')['page'] > 1) {?>
                <a href="?page=<?php echo $_smarty_tpl->getValue('pagination')['page']-1;?>
">&laquo;</a>
            <?php } else { ?>
                <span class="disabled">&laquo;</span>
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
                    <span class="active"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</span>
                <?php } else { ?>
                    <a href="?page=<?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
"><?php echo ($_smarty_tpl->getValue('__smarty_section_p')['index'] ?? null);?>
</a>
                <?php }?>
            <?php
}
}
?>

                        <?php if ($_smarty_tpl->getValue('pagination')['page'] < $_smarty_tpl->getValue('pagination')['countPage']) {?>
                <a href="?page=<?php echo $_smarty_tpl->getValue('pagination')['page']+1;?>
">&raquo;</a>
            <?php } else { ?>
                <span class="disabled">&raquo;</span>
            <?php }?>
        </div>
    <?php }
}
}
/* {/block "content"} */
}
