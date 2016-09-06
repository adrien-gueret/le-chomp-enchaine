<item>
  <title><?= $view->article->prop('title'); ?></title>
  <link><?= $view->article->getUrl(); ?></link>
  <description><?= $view->article->prop('introduction'); ?></description>
  <?php if( $view->author !== null ): ?>
    <author><?= $view->author; ?></author>
  <?php endif ?>
  <enclosure url="https:<?= $view->article->getMainPictureURL(); ?>" length="<?= $view->article->getMainPictureSize(); ?>" type="<?= $view->article->getMainPictureMimeType(); ?>"></enclosure>
  <?php if( $view->category !== null ): ?>
    <category><?= $view->category; ?></category>
  <?php endif ?>
  <pubDate><?= date('r', strtotime($view->article->prop('date_publication'))); ?></pubDate>
  <guid isPermaLink="true"><?= $view->article->getUrl(); ?></guid>
</item>
