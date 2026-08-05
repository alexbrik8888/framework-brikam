<?php
/* Smarty version 5.8.4, created on 2026-08-05 20:27:21
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Admin/article.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a73727962a159_09612888',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '057e640b8c76dbbccc59648b320dfd24a44f05ff' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Admin/article.tpl',
      1 => 1785945526,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a73727962a159_09612888 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_6987880366a7372795e88d5_42899481', "title");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_896930386a7372795f1290_69203182', "scripts_top");
?>


<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_11917878666a7372795f2405_11300485', "content");
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_21247071016a7372796295e6_49250325', "scripts_bottom");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Admin/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "title"} */
class Block_6987880366a7372795e88d5_42899481 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Admin';
?>
Добавить статью<?php
}
}
/* {/block "title"} */
/* {block "scripts_top"} */
class Block_896930386a7372795f1290_69203182 extends \Smarty\Runtime\Block
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
/* {/block "scripts_top"} */
/* {block "content"} */
class Block_11917878666a7372795f2405_11300485 extends \Smarty\Runtime\Block
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
        <?php if ((true && (true && null !== ($_smarty_tpl->getValue('article')['id'] ?? null)))) {?>
            <input type="hidden" id="id" name="id" value="<?php echo (($tmp = $_smarty_tpl->getValue('article')['id'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
">
        <?php }?>
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
                       <?php if ((true && (true && null !== ($_smarty_tpl->getValue('article')['image'] ?? null)))) {?>
                           <img show  src="/file/image?id=<?php echo $_smarty_tpl->getValue('article')['image'][0]['id'];?>
">
                       <?php } else { ?>
                           <img show >
                       <?php }?>

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
                        <?php if (!$_smarty_tpl->getSmarty()->getModifierCallback('in_array')($_smarty_tpl->getValue('item')['id'],$_smarty_tpl->getValue('article')['category'])) {?>
                            <option value="<?php echo $_smarty_tpl->getValue('item')['id'];?>
"  ><?php echo $_smarty_tpl->getValue('item')['name'];?>
</option>
                        <?php } else { ?>
                            <option value="<?php echo $_smarty_tpl->getValue('item')['id'];?>
"  selected ><?php echo $_smarty_tpl->getValue('item')['name'];?>
</option>
                       <?php }?>
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
class Block_21247071016a7372796295e6_49250325 extends \Smarty\Runtime\Block
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
