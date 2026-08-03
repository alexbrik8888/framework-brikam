<header class="main-header">
    <div class="logo">PORTAL<span>NEWS</span></div>
    <nav class="nav-categories">
        {foreach $main_category as $item}
        <a href="/catalog?id={$item.id}">{$item.name}</a>
        {/foreach}
    </nav>
</header>