<?php
/* Smarty version 5.8.4, created on 2026-08-03 16:54:38
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/main.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a709d9e794580_78711479',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ed8b39d342fce62ea7e0797c006eb5dad1bbd4f2' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/main.tpl',
      1 => 1785661512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a709d9e794580_78711479 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_18823584386a709d9e78c018_71811343', "content");
?>


<?php $_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "content"} */
class Block_18823584386a709d9e78c018_71811343 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
?>

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
<?php
}
}
/* {/block "content"} */
}
