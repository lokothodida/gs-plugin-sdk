<?php
  $left = array();
  $right = array();
  
  // Left section
  $left['id'] = GSPluginUI::input(array(
    'label' => static::i18n('ID'),
    'name' => 'id',
    'placeholder' => 'your_plugin',
  ));

  $left['className'] = GSPluginUI::input(array(
    'label' => static::i18n('CLASSNAME'),
    'name' => 'className',
    'placeholder' => 'yourPlugin',
  ));

  $left['author'] = GSPluginUI::input(array(
    'label' => static::i18n('AUTHOR'),
    'name' => 'author',
    'placeholder' => 'you',
  ));

  $left['version'] = GSPluginUI::input(array(
    'label' => static::i18n('VERSION'),
    'name' => 'version',
    'placeholder' => '0.1',
  ));

  // Right section
  $right['website'] = GSPluginUI::input(array(
    'label' => static::i18n('WEBSITE'),
    'name' => 'website',
    'placeholder' => 'http://yoursite.com/',
  ));

  $right['defaultLang'] = GSPluginUI::input(array(
    'label' => static::i18n('LANG'),
    'name' => 'defaultLang',
    'placeholder' => 'en_US',
  ));

  $right['tab'] = GSPluginUI::input(array(
    'label' => static::i18n('TAB'),
    'name' => 'tab',
    'placeholder' => 'pages',
  ));


  $sections   = GSPluginUI::bothSections($left, $right);
  $submitLine = GSPluginUI::submitLine(array(), null, array('url' => static::adminUrl()));
?>


<form action="<?php echo static::adminUrl(); ?>" method="post">
  <?php echo $sections; ?>
  <?php echo $submitLine; ?>
  <h4><?php echo static::i18n('HASHES'); ?></h4>
</form>

<script>

</script>