{extends file="Front/layout/layout.tpl"}
{block name="content"}
    <main class="container">
        {foreach  $list_category['group_name'] as $id =>$groupName }
        <section class="category-block">
            <div class="category-header">
                <h2>{$groupName}</h2>
                <a href="/catalog?id={$id}" class="btn-all">Все статьи &rarr;</a>
            </div>
            <div class="articles-grid">
            {foreach  $list_category['group'][$id] as $id =>$groupInfo }
                    <!-- Пост 1 (Главная новость категории) -->
                    <article class="card main-card">
                        <img src="file/image?id={$groupInfo.file_id}" alt="Превью">
                        <span class="badge">{$groupInfo.created_at}</span>
                        <h3><a href="/article/1">{$groupInfo.article_title}</a></h3>
                        <p>{$groupInfo.article_description}</p>
                    </article>
            {/foreach}
            </div>
        </section>
            <hr class="section-divider">
        {/foreach}
        <!-- Секция категории 1 -->








{/block}

