{extends file="Front/layout/layout.tpl"}
{block name="content"}
    <main class="container">
        <!-- Секция категории 1 -->
        <section class="category-block">
            <div class="category-header">
                <h2>ТЕХНОЛОГИИ</h2>
                <a href="/category/tech" class="btn-all">Все статьи &rarr;</a>
            </div>

            <div class="articles-grid">
                <!-- Пост 1 (Главная новость категории) -->
                <article class="card main-card">
                    <img src="tech1.jpg" alt="Превью">
                    <span class="badge">02 Авг 2026</span>
                    <h3><a href="/article/1">ИИ нового поколения изменил подход к разработке ПО</a></h3>
                    <p>Краткое описание свежей новости, привлечение внимания читателя...</p>
                </article>

                <!-- Пост 2 -->
                <article class="card">
                    <img src="tech2.jpg" alt="Превью">
                    <span class="badge">01 Авг 2026</span>
                    <h4><a href="/article/2">Презентация новых процессоров: ключевые анонсы</a></h4>
                </article>

                <!-- Пост 3 -->
                <article class="card">
                    <img src="tech3.jpg" alt="Превью">
                    <span class="badge">31 Июл 2026</span>
                    <h4><a href="/article/3">Будущее облачных вычислений в 2026 году</a></h4>
                </article>
            </div>
        </section>

        <hr class="section-divider">

        <!-- Секция категории 2 -->
        <section class="category-block">
            <div class="category-header">
                <h2>БИЗНЕС</h2>
                <a href="/category/business" class="btn-all">Все статьи &rarr;</a>
            </div>
            <div class="articles-grid">
                <!-- 3 последних поста аналогично -->
            </div>
        </section>
    </main>
{/block}

