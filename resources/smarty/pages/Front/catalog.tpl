{extends file="Front/layout/layout.tpl"}
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
        <select class="sort-select">
            <option value="date">Дате публикации (Свежие)</option>
            <option value="views">Количеству просмотров (Популярные)</option>
        </select>
    </div>

    <!-- Список статей категории -->
    <section class="articles-list">
        <article class="list-card">
            <img src="tech1.jpg" alt="Превью">
            <div class="list-card-content">
                <h3><a href="/article/1">ИИ нового поколения изменил подход к разработке ПО</a></h3>
                <p class="excerpt">Подробный разбор того, как нейросети меняют повседневную работу программистов...</p>
                <div class="meta">
                    <span>📅 02 Авг 2026</span>
                    <span>👁️ 1,240 просмотров</span>
                </div>
            </div>
        </article>

        <!-- Повторяющийся блок статей... -->
    </section>

    <!-- Пагинация (CNN Style) -->
    {if $pagination.total > 1}
    <nav class="pagination">
        {if $pagination.page > 1}
            <a  href="?page={$pagination.page - 1}" class="page-link disabled">&laquo; Назад</a>
        {/if}
        {section name=p start=1 loop=$pagination.countPage+1}
            {if $smarty.section.p.index == $pagination.page}
                <a href="?page={$smarty.section.p.index}" class="page-link active">{$smarty.section.p.index}</a>
            {else}
                <a class="page-link" href="?page={$smarty.section.p.index}">{$smarty.section.p.index}</a>
            {/if}
        {/section}
        {if $pagination.page < $pagination.countPage}
            <a href="?page={$pagination.page+ 1}" class="page-link">Вперед &raquo;</a>
        {/if}
    </nav>
    {/if}
</main>
{/block}