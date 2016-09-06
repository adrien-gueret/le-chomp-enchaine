<?xml version="1.0" encoding="UTF-8" ?>
<rss xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
  <channel>
    <language>fr</language>
    <description><?= $view->page_description; ?></description>
    <link><?= $view->base_url; ?></link>
    <title><?= $view->page_title; ?></title>
    <image title="<?= $view->page_title; ?>" link="<?= $view->base_url; ?>" url="https:<?= Library_Assets::get('favicon.png'); ?>" width="40" height="41"></image>
    <?= $view->tpl_items; ?>
  </channel>
</rss>
