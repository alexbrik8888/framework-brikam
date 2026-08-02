{* templates/admin/category_add.tpl *}
{extends file="Admin/layout/layout.tpl"}

{block name="title"}Добавить категорию{/block}
{block name="script_top"}
    {literal}
        <script>
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
        </script>
{/literal}

{/block}

{block name="content"}
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
                {foreach $parent as $category}
                    <option value="{$category.id}">{$category.name}</option>
                {/foreach}
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
                    {foreach $parent as $category}
                        <option value="{$category.id}">{$category.name}</option>
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
                <th>Родительская категория</th>
                <th>Описание</th>
                <th>Дата</th>
                <th>Действтя</th>
            </tr>
        </thead>
        <tbody>
            {foreach $category_list as $category}
                <tr>
                    <td><strong>#{$category.id}</strong></td>
                    <td>{$category.name}</td>
                    <td>
                        {if $category.parent_id != null}
                            <span class="badge-parent">{$category.parent_id}</span>
                        {else}
                            <span class="badge-root">Коренная категория</span>
                        {/if}
                    </td>
                    <td>
                        {$category.description}
                    </td>
                    <td>
                        <p>Создания:{$category.created_at}</p>
                        <p>Обновления{$category.updated_at}</p>
                    </td>
                    <td>
                        <button type="button" onclick="deleteCategory({$category.id})" class="btn">Удалить</button>
                        <button type="button" onclick='editCategory(  {$category|json_encode})' class="btn">Редактироввать</button>
                    </td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="3" style="text-align: center; color: #64748b; padding: 30px;">
                        Категорий пока нет.
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