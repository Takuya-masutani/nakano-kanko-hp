<?php
define('SECRET_TOKEN', 'kanko_deploy_2026');
define('GITHUB_RAW', 'https://raw.githubusercontent.com/Takuya-masutani/nakano-kanko-hp/master/themes/themes/template');

if (($_GET['token'] ?? '') !== SECRET_TOKEN) {
    http_response_code(403);
    die('Unauthorized');
}

$files = [
    '404.php',
    'admin-style.css',
    'archive-column.php',
    'archive-eatery.php',
    'archive-event-info.php',
    'archive-facility.php',
    'archive-tourism.php',
    'archive.php',
    'attachment.php',
    'author.php',
    'category.php',
    'comments.php',
    'entry-content.php',
    'entry-footer.php',
    'entry-meta.php',
    'entry-summary.php',
    'entry.php',
    'event-calendar.php',
    'footer.php',
    'functions.php',
    'header.php',
    'index.php',
    'nav-below-single.php',
    'nav-below.php',
    'news-rss-noslide.php',
    'news-rss.php',
    'page-all-post.php',
    'page-citywalk.php',
    'page-cultures.php',
    'page-eatery.php',
    'page-event-info.php',
    'page-shopping.php',
    'page.php',
    'post-list-column.php',
    'post-list-eatery.php',
    'post-list-event-info.php',
    'post-list-facility.php',
    'post-list-tourism.php',
    'post-slide-event-info.php',
    'search.php',
    'sidebar.php',
    'single-column.php',
    'single-eatery.php',
    'single-event-info.php',
    'single-facility.php',
    'single-tourism.php',
    'single.php',
    'style.css',
    'tag.php',
    'tax-list-area-eatery.php',
    'tax-list-area-facility.php',
    'tax-list-area-tourism.php',
    'tax-list-area.php',
    'tax-list-cultures-category.php',
    'taxonomy-cultures-category.php',
    'js/nav.js',
];

foreach ($files as $file) {
    $content = file_get_contents(GITHUB_RAW . '/' . $file . '?nocache=' . time());
    if ($content === false) { echo "FAIL: $file\n"; continue; }
    $local = __DIR__ . '/' . $file;
    $dir = dirname($local);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents($local, $content) !== false ? print("OK: $file\n") : print("FAIL write: $file\n");
}
echo "完了";
