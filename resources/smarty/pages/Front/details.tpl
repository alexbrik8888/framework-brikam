{extends file="Front/layout/layout.tpl"}
{block name="content"}
<main class="container article-layout">
    <!-- Основной контент статьи -->
    <article class="full-article">
        <span class="cat-tag">ТЕХНОЛОГИИ</span>
        <h1 class="article-title">ИИ нового поколения изменил подход к разработке ПО</h1>

        <div class="article-meta">
            <span class="author">Автор: Иван Иванов</span>
            <span class="date">Опубликовано: 02 Августа 2026, 10:30</span>
            <span class="views">👁️ 1,240 просмотров</span>
        </div>

        <div class="main-image-wrapper">
            <img src="tech1-full.jpg" alt="Главная иллюстрация">
            <span class="image-caption">Фото: IT-конференция 2026</span>
        </div>

        <div class="article-body">
            <p class="lead">Краткая вводная мысль (выделенный лид-абзац, как принято в CNN)...</p>
            <p>Основной текст статьи, абзацы, цитаты и аналитика...</p>
            <blockquote>«Это крупнейший сдвиг в индустрии за последние 10 лет»</blockquote>
            <p>Продолжение текста новости...</p>
        </div>
    </article>

    <!-- Секция: Похожие статьи -->
    <section class="related-articles">
        <h3 class="related-title">ПОХОЖИЕ МАТЕРИАЛЫ</h3>
        <div class="related-grid">
            <article class="related-card">
                <img src="tech2.jpg" alt="Превью">
                <h4><a href="#">Презентация новых процессоров: ключевые анонсы</a></h4>
                <span class="date">01 Авг 2026</span>
            </article>

            <article class="related-card">
                <img src="tech3.jpg" alt="Превью">
                <h4><a href="#">Будущее облачных вычислений в 2026 году</a></h4>
                <span class="date">31 Июл 2026</span>
            </article>

            <article class="related-card">
                <img src="tech4.jpg" alt="Превью">
                <h4><a href="#">Как защитить данные в эпоху квантовых компьютеров</a></h4>
                <span class="date">29 Июл 2026</span>
            </article>
        </div>
    </section>
</main>
{/block}