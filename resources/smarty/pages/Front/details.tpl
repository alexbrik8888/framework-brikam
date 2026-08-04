{extends file="Front/layout/layout.tpl"}
{block name="content"}
<main class="container article-layout">
    <!-- Основной контент статьи -->
    <article class="full-article">
        {foreach $articl.category as $item}
            <span class="cat-tag"><a href="/catalog?id={$item.category_id}">{$item.name}</a></span>
        {/foreach}
        <h1 class="article-title">{$articl.name}</h1>

        <div class="article-meta">
            <span class="date">Опубликовано: {$articl.created_at}</span>
            <span class="views">👁️ {$articl.view}</span>
        </div>

        <div class="main-image-wrapper">
            <img src="/file/image?id={$articl.file_id}" alt="Главная иллюстрация">
        </div>

        <div class="article-body">
            <pre style="width: 100%;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            box-sizing: border-box; ">{$articl.text}</pre>
        </div>
    </article>
    <!-- Секция: Похожие статьи -->
    <section class="related-articles">
        <h3 class="related-title">ПОХОЖИЕ МАТЕРИАЛЫ</h3>
        <div class="related-grid">
            {foreach $recommendation as $item}
            <article class="related-card">
                <img src="/file/image?id={$item.file_id}" alt="{$item.name}">
                <h4><a href="/article?id={$item.id}">{$item.name}</a></h4>
                <span class="date">{$item.created_at}</span>
            </article>
            {/foreach}
        </div>
    </section>
</main>
{/block}