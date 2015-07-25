<?php

// Prevent this class from being included if another plugin
// has already loaded it
if (class_exists('GSUI')) return;

class GSUI {
  // == PUBLIC METHODS ==
  // Title
  public function title($content, $floated = false) {
    return $this->element('h3', array(
      'class' => $floated ? 'floated' : null,
    ), $content);
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
