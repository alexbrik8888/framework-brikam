{extends file="Front/layout/layout.tpl"}
{block name="scripts_top"}
{literal}
    <script>
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
    </script>
{/literal}
{/block}
{block name="content"}
<main class="container">
    <!-- Шапка категории -->
    <header class="category-page-header">
        <h1 class="cat-title">{$category.name}</h1>
        <p class="cat-description">{$category.description}</p>
    </header>

    <!-- Панель сортировки -->
    <div class="toolbar">
        <span class="sort-label">Сортировать по:</span>
        <select onchange="Sort(this)" name="sort" class="sort-select">
            {foreach [
                ['val' =>"date" ,'name' => "Дате публикации (Свежие)" ] ,
                ['val' =>"views" ,'name' => "Количеству просмотров (Популярные)" ]
            ] as $item}
                {if  $query_param['sort'] == $item.val}
                    <option value="{$item.val}" selected >{$item.name}</option>
                {else}
                        <option value="{$item.val}" >{$item.name}</option>
                {/if}
            {/foreach}
        </select>
    </div>
    <!-- Список статей категории -->
    <section class="articles-list">
        {foreach  $list_articl as $article }
        <article class="list-card">
            {if $article.file_id != NULL}
            <img src="file/image?id={$article.file_id}" alt="{$article.name}">
            {else}
                <img src="public/img/no-image-icon-23494.png" alt="{$article.name}">
            {/if}
            <div class="list-card-content">
                <h3><a href="/article?id={$article.id}">{$article.name}</a></h3>
                <p class="excerpt">{$article.description}</p>
                <div class="meta">
                    <span>📅 {$article.created_at}</span>
                    <span>👁️ {$article.view}  просмотров</span>
                </div>
            </div>
        </article>
        {/foreach}

        <!-- Повторяющийся блок статей... -->
    </section>

    <!-- Пагинация (CNN Style) -->
    {if $pagination.total > 1}

    <nav class="pagination">

        {if $pagination.page > 1}
            <a  href="/catalog?{$query_param|build_http_url:['page'=> $pagination.page-1]}" class="page-link">&laquo; Назад</a>
        {else}
            <a  href="#" class="page-link disabled">&laquo; Назад</a>
        {/if}
        {section name=p start=1 loop=$pagination.countPage+1}
            {if $smarty.section.p.index == $pagination.page}
                <a   href="/catalog?{$query_param|build_http_url:['page'=> $smarty.section.p.index]}" class="page-link active">{$smarty.section.p.index}</a>
            {else}
                <a href="/catalog?{$query_param|build_http_url:['page'=> $smarty.section.p.index]}" class="page-link"> {$smarty.section.p.index}</a>
            {/if}
        {/section}
        {if $pagination.page < $pagination.countPage}
            <a   href="/catalog?{$query_param|build_http_url:['page'=> $pagination.page+1]}"  class="page-link">Вперед &raquo;</a>
        {else}
            <a href="#" class="page-link disabled">Вперед &raquo;</a>
        {/if}
    </nav>
    {/if}
</main>
{/block}