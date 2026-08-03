<?php
/* Smarty version 5.8.4, created on 2026-08-03 21:34:56
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Admin/category.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a70df50b6fb65_95872131',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0d6a0b9eb99612a7446f2ae9416f261bc79d2525' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Admin/category.tpl',
      1 => 1785679641,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a70df50b6fb65_95872131 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6612079606a70df50acf343_07088554', "title");
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_5139740546a70df50ad70a0_63204946', "script_top");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_20709779796a70df50ad8039_32876034', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Admin/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_6612079606a70df50acf343_07088554 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>
Добавить категорию<?php
}
}
/* {/block "title"} */
/* {block "script_top"} */
class Block_5139740546a70df50ad70a0_63204946 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>

    
        <?php echo '<script'; ?>
>
            function deleteCategory(id){
                fetch('/admin/category', {method: 'DELETE',headers:{'Content-Type': 'application/json'}
                    , body:JSON.stringify({id:id})});
                location.href=window.location.search;
            }
            function editCategory(data) {
               let inputHide =document.createElement('input')
                inputHide.type = 'hidden';
                inputHide.name = 'id';
                inputHide.id = 'id';
                let form = document.querySelector('#categoey')
               form.append(inputHide)
               let item = form.querySelectorAll('input,select,textarea')
                item.forEach((element, index) => {
                    let value = data[element.name];
                    switch (element.tagName){
                        case "INPUT":
                        case "TEXTAREA":
                            element.value = value;
                            break;
                        case "SELECT":
                            if(value == null){
                                element.querySelector('option[value="0"]').selected = true
                            }else {
                                element.querySelector('option[value="' + value + '"]').selected = true
                            }
                            break;
                    }
                });
            }
           function censel(){
               let form = document.querySelector('#categoey')
               form.querySelector("[name='id']").remove();
               form.reset()
           }
        <?php echo '</script'; ?>
>


<?php
}
}
/* {/block "script_top"} */
/* {block "content"} */
class Block_20709779796a70df50ad8039_32876034 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>

    <h1>Создание категории</h1>

    <form id="categoey" action="/admin/category" method="POST">

        <div class="form-group">
            <label for="name">Название категории *</label>
            <input type="text" id="name" name="name" required placeholder="Например: Новости PHP">
        </div>
        <div class="form-group">
            <label for="description">Краткое описание (анонс)</label>
            <textarea id="description" name="description" rows="3" placeholder="Краткое содержание для списка статей..."></textarea>
        </div>
        <div class="form-group">
            <label for="parent_id">Родительская категория</label>
            <select id="parent_id" name="parent_id">
                <option value="0">-- Без родителя (Верхний уровень) --</option>
                <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('parent'), 'category');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('category')->value) {
$foreach0DoElse = false;
?>
                    <option value="<?php echo $_smarty_tpl->getValue('category')['id'];?>
"><?php echo $_smarty_tpl->getValue('category')['name'];?>
</option>
                <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
            </select>
        </div>

        <button type="submit" class="btn">Сохранить категорию</button> <button type="button" onclick="censel()" class="btn">Отмена</button>
     </form>

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
            <label for="parent_id">Родительская категория</label>
                    <select style="width: 300px" id="parent_id" name="parent_id">
                    <option value="0">-- Без родителя (Верхний уровень) --</option>
                    <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('parent'), 'category');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('category')->value) {
$foreach1DoElse = false;
?>
                        <option value="<?php echo $_smarty_tpl->getValue('category')['id'];?>
"><?php echo $_smarty_tpl->getValue('category')['name'];?>
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
                <th>Родительская категория</th>
                <th>Описание</th>
                <th>Дата</th>
                <th>Действтя</th>
            </tr>
        </thead>
        <tbody>
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('category_list'), 'category');
$foreach2DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('category')->value) {
$foreach2DoElse = false;
?>
                <tr>
                    <td><strong>#<?php echo $_smarty_tpl->getValue('category')['id'];?>
</strong></td>
                    <td><?php echo $_smarty_tpl->getValue('category')['name'];?>
</td>
                    <td>
                        <?php if ($_smarty_tpl->getValue('category')['parent_id'] != null) {?>
                            <span class="badge-parent"><?php echo $_smarty_tpl->getValue('category')['parent_id'];?>
</span>
                        <?php } else { ?>
                            <span class="badge-root">Коренная категория</span>
                        <?php }?>
                    </td>
                    <td>
                        <?php echo $_smarty_tpl->getValue('category')['description'];?>

                    </td>
                    <td>
                        <p>Создания:<?php echo $_smarty_tpl->getValue('category')['created_at'];?>
</p>
                        <p>Обновления<?php echo $_smarty_tpl->getValue('category')['updated_at'];?>
</p>
                    </td>
                    <td>
                        <button type="button" onclick="deleteCategory(<?php echo $_smarty_tpl->getValue('category')['id'];?>
)" class="btn">Удалить</button>
                        <button type="button" onclick='editCategory(  <?php echo json_encode($_smarty_tpl->getValue('category'));?>
)' class="btn">Редактироввать</button>
                    </td>
                </tr>
            <?php
}
if ($foreach2DoElse) {
?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #64748b; padding: 30px;">
                        Категорий пока нет.
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
