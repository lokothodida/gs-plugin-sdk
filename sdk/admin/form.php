<?php
  $left = array();
  $right = array();
  
  // Left section
  $left['prevId'] = GSPluginUI::input(array(
    'name' => 'prevId',
    'value' => $info['id'],
    'type' => 'hidden',
  ));

  $left['id'] = GSPluginUI::input(array(
    'label' => static::i18n('ID'),
    'name' => 'id',
    'value' => $info['id'],
    'placeholder' => 'your_plugin',
  ));

  $left['className'] = GSPluginUI::input(array(
    'label' => static::i18n('CLASSNAME'),
    'name' => 'className',
    'value' => $info['className'],
    'placeholder' => 'yourPlugin',
  ));

  $left['author'] = GSPluginUI::input(array(
    'label' => static::i18n('AUTHOR'),
    'name' => 'author',
    'value' => $info['author'],
    'placeholder' => 'you',
  ));

  $left['version'] = GSPluginUI::input(array(
    'label' => static::i18n('VERSION'),
    'name' => 'version',
    'value' => $info['version'],
    'placeholder' => '0.1',
  ));

  // Right section
  $right['website'] = GSPluginUI::input(array(
    'label' => static::i18n('WEBSITE'),
    'name' => 'website',
    'value' => $info['website'],
    'placeholder' => 'http://yoursite.com/',
  ));

  $right['defaultLang'] = GSPluginUI::input(array(
    'label' => static::i18n('LANG'),
    'name' => 'defaultLang',
    'value' => $info['defaultLang'],
    'placeholder' => 'en_US',
  ));

  $right['tab'] = GSPluginUI::input(array(
    'label' => static::i18n('TAB'),
    'name' => 'tab',
    'value' => $info['tab'],
    'placeholder' => 'pages',
  ));

  $sections   = GSPluginUI::bothSections($left, $right);
  $submitLine = GSPluginUI::submitLine(array(), null, array('url' => static::adminUrl()));
?>


<form action="<?php echo $url; ?>" method="post">
  <?php echo $sections; ?>
  <?php echo $submitLine; ?>
</form>