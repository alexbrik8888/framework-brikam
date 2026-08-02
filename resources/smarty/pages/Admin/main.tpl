{* templates/admin/index.tpl *}
{extends file="Admin/layout/layout.tpl"}

{block name="title"}Главная панель{/block}

{block name="content"}
    <h1>Добро пожаловать в админ-панель</h1>
    <p style="margin-bottom: 20px; color: #64748b;">Выберите необходимое действие из меню слева или воспользуйтесь быстрыми кнопками:</p>

    <div style="display: flex; gap: 15px;">
        <a href="/admin/category" class="btn" style="text-decoration: none;">+ Добавить категорию</a>
        <a href="/admin/article" class="btn" style="text-decoration: none; background: #059669;">+ Добавить статью</a>
    </div>
{/block}