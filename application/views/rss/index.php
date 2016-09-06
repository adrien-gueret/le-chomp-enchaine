<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
  <channel>
    <language>fr</language>
    <description><?= $view->page_description; ?></description>
    <link><?= $view->base_url; ?></link>
    <title><?= $view->page_title; ?></title>
    <image title="<?= $view->page_title; ?>" link="<?= $view->base_url; ?>" url="https:<?= Library_Assets::get('favicon.ico'); ?>" width="40" height="41"></image>
  </channel>
  <?= $view->tpl_items; ?>
</rss>
