<?php

// Display the form using the UI elements
// Title
$title = $ui->title($plugin->i18n('PLUGIN_TITLE'));

// Form
$form = $ui->form(array(
  'method' => 'post',
  'contents' => array(
    // Sections
    // Setting 1 will be on the left and Setting 2 on the right
    $ui->section(
      // Left
      $ui->input(array(
        'name' => 'setting1',
        'value' => $setting['setting1'],
        'label' => $plugin->i18n('SETTING_1'), 
      )),
      // Right
      $ui->input(array(
        'name' => 'setting2',
        'value' => $setting['setting2'],
        'label' => $plugin->i18n('SETTING_2'), 
      ))
    ),
    // Setting 3 will be in a WYSIWYG editor
    $ui->htmleditor(array(
      'name' => 'message',
      'value' => $settings['setting3'],
    )),
  )
));

// Print the form
echo $title;
echo $form;
