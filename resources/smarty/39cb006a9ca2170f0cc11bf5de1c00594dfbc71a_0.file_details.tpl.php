<?php
/* Smarty version 5.8.4, created on 2026-08-04 19:57:34
  from 'file:C:\OpenServer\domains\testwork\resources\smarty\pages/Front/details.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.8.4',
  'unifunc' => 'content_6a7219fe1136a9_96306163',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '39cb006a9ca2170f0cc11bf5de1c00594dfbc71a' => 
    array (
      0 => 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages/Front/details.tpl',
      1 => 1785862651,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_6a7219fe1136a9_96306163 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_8385113976a7219fe0f0614_42511188', "content");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "Front/layout/layout.tpl", $_smarty_current_dir);
}
/* {block "content"} */
class Block_8385113976a7219fe0f0614_42511188 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = 'C:\\OpenServer\\domains\\testwork\\resources\\smarty\\pages\\Front';
?>

<main class="container article-layout">
    <!-- Основной контент статьи -->
    <article class="full-article">
        <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('articl')['category'], 'item');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach0DoElse = false;
?>
            <span class="cat-tag"><a href="/catalog?id=<?php echo $_smarty_tpl->getValue('item')['category_id'];?>
"><?php echo $_smarty_tpl->getValue('item')['name'];?>
</a></span>
        <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        <h1 class="article-title"><?php echo $_smarty_tpl->getValue('articl')['name'];?>
</h1>

        <div class="article-meta">
            <span class="date">Опубликовано: <?php echo $_smarty_tpl->getValue('articl')['created_at'];?>
</span>
            <span class="views">👁️ <?php echo $_smarty_tpl->getValue('articl')['view'];?>
</span>
        </div>

        <div class="main-image-wrapper">
            <img src="/file/image?id=<?php echo $_smarty_tpl->getValue('articl')['file_id'];?>
" alt="Главная иллюстрация">
        </div>

        <div class="article-body">
            <pre style="width: 100%;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            box-sizing: border-box; "><?php echo $_smarty_tpl->getValue('articl')['text'];?>
</pre>
        </div>
    </article>
    <!-- Секция: Похожие статьи -->
    <section class="related-articles">
        <h3 class="related-title">ПОХОЖИЕ МАТЕРИАЛЫ</h3>
        <div class="related-grid">
            <?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('recommendation'), 'item');
$foreach1DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('item')->value) {
$foreach1DoElse = false;
?>
            <article class="related-card">
                <img src="/file/image?id=<?php echo $_smarty_tpl->getValue('item')['file_id'];?>
" alt="<?php echo $_smarty_tpl->getValue('item')['name'];?>
">
                <h4><a href="/article?id=<?php echo $_smarty_tpl->getValue('item')['id'];?>
"><?php echo $_smarty_tpl->getValue('item')['name'];?>
</a></h4>
                <span class="date"><?php echo $_smarty_tpl->getValue('item')['created_at'];?>
</span>
            </article>
            <?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);?>
        </div>
    </section>
</main>
<?php
}
}
/* {/block "content"} */
}
