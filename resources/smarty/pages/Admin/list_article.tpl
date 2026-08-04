{* templates/admin/category_add.tpl *}
{extends file="Admin/layout/layout.tpl"}

{block name="title"}Добавить категорию{/block}
{block name="scripts_top"}
{literal}
    <script>
        function deleteArticle(id){
            fetch('/admin/list/article', {method: 'DELETE',headers:{'Content-Type': 'application/json'}
                , body:JSON.stringify({id:id})});
            location.href=window.location.search;
        }
    </script>
{/literal}

{/block}

{block name="content"}
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
                        {foreach $category as $item}
                            <option value="{$item.id}">{$item.name}</option>
                        {/foreach}
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
        {foreach $article_list as $article}
            <tr>
                <td><strong>#{$article.id}</strong></td>
                <td>{$article.name}</td>
                <td>
                    {foreach $article.category as $item}
                        <span>{$item.name}</span> ,
                    {/foreach}
                </td>
                <td>
                    {$article.description}
                </td>
                <td>
                    <p>Создания:{$article.created_at}</p>
                    <p>Обновления{$article.updated_at}</p>
                </td>
                <td>
                    <button type="button" onclick="deleteArticle({$article.id})" class="btn">Удалить</button>
                    <button type="button" onclick="location.href = '/admin/article?id='+'{$article.id}';" class="btn">Редактироввать</button>
                </td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="3" style="text-align: center; color: #64748b; padding: 30px;">
                    Статей  пока нет.
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    <!-- Пагинация -->
    {if $pagination.total > 1}
        <div class="pagination">
            {* Кнопка Назад *}
            {if $pagination.page > 1}
                <a href="?page={$pagination.page - 1}">&laquo;</a>
            {else}
                <span class="disabled">&laquo;</span>
            {/if}

            {* Список страниц *}
            {section name=p start=1 loop=$pagination.countPage+1}
                {if $smarty.section.p.index == $pagination.page}
                    <span class="active">{$smarty.section.p.index}</span>
                {else}
                    <a href="?page={$smarty.section.p.index}">{$smarty.section.p.index}</a>
                {/if}
            {/section}

            {* Кнопка Вперед *}
            {if $pagination.page < $pagination.countPage}
                <a href="?page={$pagination.page+ 1}">&raquo;</a>
            {else}
                <span class="disabled">&raquo;</span>
            {/if}
        </div>
    {/if}
{/block}