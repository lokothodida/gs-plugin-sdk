<?php

// Prevent this class from being included if another plugin
// has already loaded it
if (class_exists('GSUI')) return;

class GSUI {
  // == PUBLIC METHODS ==
  // Header (title + quicknav)
  public function header($title, $quicknav) {
    return $this->title($title, true) . "\n" . $this->quicknav($quicknav);
  }

  // Title
  public function title($content, $floated = false) {
    return $this->element('h3', array(
      'class' => $floated ? 'floated' : null,
    ), $content);
  }

  // Quick navigation
  public function quicknav($nav) {
    $links = array();
    $count = count($nav);
    $default = array(
      'href' => '',
      'class' => '',
    );

    // Loop through $nav in reverse order
    for ($i = $count-1; $i >= 0; $i--) {
      $link = array_merge($default, $nav[$i]);
      $label = $link['label'];
      unset($link['label']);
      $links[] = $this->element('a', $link, $label);
    }
    
    return $this->element('div', array(
      'class' => 'edit-nav clearfix',
    ), $links);
  }

  // HTML element
  public function element($tag, $attrs = array(), $content = null) {
    $element = '<' . $tag . ' ';

    foreach ($attrs as $attr => $val) {
      $element .= $attr . '="' . $val . '"';
    }

    if (!$content) {
      $element .= '/>';
    } else {
      if (is_array($content)) {
        $content = implode("\n", $content);
      } else {
        $content = (string) $content;
      }
      $element .= '>' . $content . '</' . $tag . '>';
    }

    return $element;
  }
}
