<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{block name="title"}Панель управления{/block}</title>
    <link rel="stylesheet"   href="/css/admin.css">
    {block name="styles"}{/block}
    {block name="script_top"}{/block}
</head>
<body>

<aside>
    <h2>Админ-панель</h2>
    <nav>
        <a href="/admin/" class="{if $active_page == "/admin"}active{/if}">Главная</a>
        <a href="/admin/category" class="{if $active_page =="/admin/category"}active{/if}">Добавить категорию</a>
        <a href="/admin/article" class="{if $active_page =="/admin/article"}active{/if}">Добавить статью</a>
        <a href="/admin/list/article" class="{if $active_page == "/admin/list/article"}active{/if}">Список статей</a>
        <a href="/admin/logout" class="{if $active_page == "/admin/logout"}active{/if}">Выйти</a>
    </nav>
</aside>

<main>
    {if isset($success_message)}
        <div class="alert-success">{$success_message}</div>
    {/if}
    <div class="card">
        {block name="content"}{/block}
    </div>
</main>

</body>
{block name="scripts_bottom"}{/block}
</html>