{* templates/admin/article_add.tpl *}
{extends file="Admin/layout/layout.tpl"}

{block name="title"}Добавить статью{/block}

{block name="scripts_top"}
    <script>
        class FileReaderEx extends FileReader{
            constructor() {
                super();
                this.file;
                this.index;
            }
        }
    </script>
   <script src="/js/DropZoneSimple.js"></script>
{/block}

{block name="content"}
    <h1>Создание новой статьи</h1>

    <form action="/admin/article" method="POST" enctype="multipart/form-data">
        <button type="submit" class="btn" style="float:right; background: #059669;">Опубликовать статью</button>
        <!-- Название статьи -->
        <div class="form-group">
            <label for="title">Название статьи *</label>
            <input type="text" id="name" name="name" value="{$article.name|default:''}" required placeholder="Введите заголовок статьи">
        </div>
        {if isset($article.id)}
            <input type="hidden" id="id" name="id" value="{$article.id|default:''}">
        {/if}
        <!-- Описание (анонс) -->
        <div class="form-group">
            <label for="description">Краткое описание (анонс)</label>
            <textarea id="description" name="description" rows="3" placeholder="Краткое содержание для списка статей...">{$article.description|default:''}</textarea>
        </div>

        <!-- Полный текст -->
        <div class="form-group">
            <label for="text">Полный текст статьи *</label>
            <textarea id="text" name="text" rows="8" required placeholder="Основной текст...">{$article.text|default:''}</textarea>
        </div>

        <!-- Дата публикации -->
        <div class="form-group">
            <label for="published_at">Дата публикации</label>
            <input type="date" id="published_at" name="published_at" value="{$article.tpublished_at|default:$smarty.now|date_format:'%Y-%m-%d'}">
        </div>

        <!-- Изображение -->
        <div style="display: flex;gap: 20px;justify-content: space-between">
        <div style="400px;height: 200px">
            <label for="image">Главное изображение</label>

            <div id='file' class='upload-block' dropzone>
                <div bodypreview class='upload-preview'>
                    <span class='upload-block-choose'>Выберите файл</span>
                    <div class='upload-preview-block' preview>
                       {if isset($article.image) }
                           <img show  src="/file/image?id={$article.image[0]['id']}">
                       {else}
                           <img show >
                       {/if}

                    </div>
            </div>
            <input filetarget type='file' class='file-input' name='file'/>
            </div>

        </div>

        <!-- Мультивыбор категорий (categories[]) -->
        <div  style="400px;height: 200px" >
            <label for="categories">Выберите категории (зажмите Ctrl / Cmd для выбора нескольких) *</label>
            <select   style="400px;height: 200px" id="category_id" multiple  name="category_id[]" multiple required>
                {foreach $category as $item}
                        {if !in_array($item.id ,$article.category) }
                            <option value="{$item.id}"  >{$item.name}</option>
                        {else}
                            <option value="{$item.id}"  selected >{$item.name}</option>
                       {/if}
               {/foreach}
            </select>
        </div>
        </div>

    </form>
{/block}
{block name="scripts_bottom"}
    <script>
        window.addEventListener('load', () => {
            let drop = new DropZoneSimple('#file')
            drop.init()
        });
    </script>

{/block}