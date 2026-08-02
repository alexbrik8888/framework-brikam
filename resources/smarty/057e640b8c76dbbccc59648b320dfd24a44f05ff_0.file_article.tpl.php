<?php
/* Smarty version 5.8.4, created on 2026-08-02 20:28:36
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Admin/article.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a6f7e44789312_90522601',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '057e640b8c76dbbccc59648b320dfd24a44f05ff' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Admin/article.tpl',
      1 => 1785690866,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a6f7e44789312_90522601 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_7654581796a6f7e447485a8_88554516', "title");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_9456516446a6f7e447515e0_83340740', "script_top");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6843832906a6f7e44752f21_77947497', "content");
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_4663028346a6f7e44788644_06208315', "scripts_bottom");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Admin/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_7654581796a6f7e447485a8_88554516 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>
Добавить статью<?php
}
}
/* {/block "title"} */
/* {block "script_top"} */
class Block_9456516446a6f7e447515e0_83340740 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
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
    <?php echo '</script'; ?>
>
   <?php echo '<script'; ?>
 src="/js/DropZoneSimple.js"><?php echo '</script'; ?>
>
<?php
}
}
/* {/block "script_top"} */
/* {block "content"} */
class Block_6843832906a6f7e44752f21_77947497 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>

    <h1>Создание новой статьи</h1>

    <form action="/admin/article" method="POST" enctype="multipart/form-data">
        <button type="submit" class="btn" style="float:right; background: #059669;">Опубликовать статью</button>
        <!-- Название статьи -->
        <div class="form-group">
            <label for="title">Название статьи *</label>
            <input type="text" id="name" name="name" value="<?php echo (($tmp = $_smarty_tpl->getValue('article')['name'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
" required placeholder="Введите заголовок статьи">
        </div>

        <!-- Описание (анонс) -->
        <div class="form-group">
            <label for="description">Краткое описание (анонс)</label>
            <textarea id="description" name="description" rows="3" placeholder="Краткое содержание для списка статей..."><?php echo (($tmp = $_smarty_tpl->getValue('article')['description'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</textarea>
        </div>

        <!-- Полный текст -->
        <div class="form-group">
            <label for="text">Полный текст статьи *</label>
            <textarea id="text" name="text" rows="8" required placeholder="Основной текст..."><?php echo (($tmp = $_smarty_tpl->getValue('article')['text'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</textarea>
        </div>

        <!-- Дата публикации -->
        <div class="form-group">
            <label for="published_at">Дата публикации</label>
            <input type="date" id="published_at" name="published_at" value="<?php echo $_smarty_tpl->getSmarty()->getModifierCallback('date_format')((($tmp = $_smarty_tpl->getValue('article')['tpublished_at'] ?? null)===null||$tmp==='' ? time() ?? null : $tmp),'%Y-%m-%d');?>
">
        </div>

        <!-- Изображение -->
        <div style="display: flex;gap: 20px;justify-content: space-between">
        <div style="400px;height: 200px">
            <label for="image">Главное изображение</label>

            <div id='file' class='upload-block' dropzone>
                <div bodypreview class='upload-preview'>
                    <span class='upload-block-choose'>Выберите файл</span>
                    <div class='upload-preview-block' preview>
                        <img show src="">
                    </div>
            </div>
            <input filetarget type='file' class='file-input' name='file'/>
            </div>

        </div>

        <!-- Мультивыбор категорий (categories[]) -->
        <div  style="400px;height: 200px" >
            <label for="categories">Выберите категории (зажмите Ctrl / Cmd для выбора нескольких) *</label>
            <select   style="400px;height: 200px" id="category_id" multiple  name="category_id[]" multiple required>
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
        </div>

    </form>
<?php
}
}
/* {/block "content"} */
/* {block "scripts_bottom"} */
class Block_4663028346a6f7e44788644_06208315 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>

    <?php echo '<script'; ?>
>
        window.addEventListener('load', () => {
            let drop = new DropZoneSimple('#file')
            drop.init()
        });
    <?php echo '</script'; ?>
>

<?php
}
}
/* {/block "scripts_bottom"} */
}
