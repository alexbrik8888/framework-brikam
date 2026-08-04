{extends file="Front/layout/layout.tpl"}
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
<main class="container">
    <!-- Шапка категории -->
    <header class="category-page-header">
        <h1 class="cat-title">{$category.name}</h1>
        <p class="cat-description">{$category.description}</p>
    </header>

    <!-- Панель сортировки -->
    <div class="toolbar">
        <span class="sort-label">Сортировать по:</span>
        <select onchange="" name="sort" class="sort-select">
            <option value="date">Дате публикации (Свежие)</option>
            <option value="views">Количеству просмотров (Популярные)</option>
        </select>
    </div>
    <!-- Список статей категории -->
    <section class="articles-list">
        {foreach  $list_articl as $article }
        <article class="list-card">
            <img src="file/image?id={$article.file_id}" alt="Превью">
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
            <a  href="/catalog?{$smarty.server.QUERY_STRING}&page={$pagination.page - 1}" class="page-link">&laquo; Назад</a>
        {else}
            <a  href="/catalog?{$smarty.server.QUERY_STRING}&page={$pagination.page - 1}" class="page-link disabled">&laquo; Назад</a>
        {/if}
        {section name=p start=1 loop=$pagination.countPage+1}
            {if $smarty.section.p.index == $pagination.page}
                <a href="/catalog?{$smarty.server.QUERY_STRING}&page={$smarty.section.p.index}" class="page-link active">{$smarty.section.p.index}</a>
            {else}
                <a class="page-link" href="/catalog?{$smarty.server.QUERY_STRING}&page={$smarty.section.p.index}">{$smarty.section.p.index}</a>
            {/if}
        {/section}
        {if $pagination.page < $pagination.countPage}
            <a href="/catalog?{$smarty.server.QUERY_STRING}&page={$pagination.page+ 1}" class="page-link">Вперед &raquo;</a>
        {else}
            <a href="/catalog?{$smarty.server.QUERY_STRING}&page={$pagination.page+ 1}" class="page-link disabled">Вперед &raquo;</a>
        {/if}
    </nav>
    {/if}
</main>
{/block}