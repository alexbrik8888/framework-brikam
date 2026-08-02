{extends file="Front/layout/layout.tpl"}
{block name="content"}
<main class="container">
    <!-- Шапка категории -->
    <header class="category-page-header">
        <h1 class="cat-title">ТЕХНОЛОГИИ</h1>
        <p class="cat-description">Актуальные новости из мира высоких технологий, стартапов и IT-индустрии.</p>
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
    <nav class="pagination">
        <a href="#" class="page-link disabled">&laquo; Назад</a>
        <a href="#" class="page-link active">1</a>
        <a href="#" class="page-link">2</a>
        <a href="#" class="page-link">3</a>
        <a href="#" class="page-link">Вперед &raquo;</a>
    </nav>
</main>
{/block}