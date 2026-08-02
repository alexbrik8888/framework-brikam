<?php
/* Smarty version 5.8.4, created on 2026-08-02 12:54:53
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/catalog.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a6f13ed466398_50140254',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5578a58a9c2e3924798bad0c3b547412c33d48bd' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/catalog.tpl',
      1 => 1785664408,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a6f13ed466398_50140254 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_7789483606a6f13ed45ea67_27857760', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "content"} */
class Block_7789483606a6f13ed45ea67_27857760 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
?>

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
<?php
}
}
/* {/block "content"} */
}
