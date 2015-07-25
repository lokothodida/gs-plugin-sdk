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

  // Quick tabs
  public function quicktab($attrs, $nav, $contents) {
    if (!is_array($attrs)) {
      $attrs = array('id' => $attrs);
    }

    $quicknav   = $this->quicknav($nav);
    $containers = array();

    foreach ($contents as $content) {
      $containers[] = $this->element('div', array('class' => 'page'), $content);
    }

    $containers = implode("\n", $containers);
    $script = '
      <script>
        $(function() {
          var $tabcontainer = $(' . json_encode('#' . $attrs['id']) . ');
          var $tabs = $tabcontainer.find(".edit-nav a");
          var $pages = $tabcontainer.find(".page");
          var totalPages = $pages.length;
          
          // Hide all pages (except the first)
          $pages.slice(1).hide();

          // Bind click event to quicknav
          $tabs.each(function(i, tab) {
            var $tab = $(tab);
            $tab.click(function() {
              $tabs.removeClass("current");
              $tab.addClass("current");
              $pages.hide();
              var idx = (totalPages - 1) - i;
              $($pages[idx]).show();
              return false;
            });
          });
        });
      </script>
    ';

    $wrapper = $this->element('div', $attrs, array($quicknav, $containers, $script));
    return $wrapper;
  }

  // Section
  public function section($left, $right) {
    return implode("\n", array(
      $this->leftsec($left),
      $this->rightsec($right),
      $this->element('div', array('class' => 'clear')),
    ));
  }

  // Left section
  public function leftsec($content) {
    return $this->element('div', array('class' => 'leftsec'), $content);
  }

  // Right section
  public function rightsec($content) {
    return $this->element('div', array('class' => 'rightsec'), $content);
  }

  // Metawindow
  public function metawindow($left, $right) {
    return $this->element('div', array('id' => 'metadata_window'), array(
      $this->element('div', array('class' => 'leftopt'),  $left),
      $this->element('div', array('class' => 'rightopt'), $right),
      $this->element('div', array('class' => 'clear')),
    ));
  }

  // Table
  public function table($attrs, $header, $rows) {
    $heads = $this->tr('th', array(), $header);
    $thead = $this->element('thead', array(), $heads);

    // Format rows
    $body = array();
    foreach ($rows as $row) {
      if (is_array($row)) {
        $body[] = $this->tr('td', array(), $row);
      } else {
        $body[] = (string) $row;
      }
    }

    $tbody = $this->element('tbody', array(), $body);

    return $this->element('table', $attrs, array($thead, $tbody));
  }

  // Table row
  public function tr($type = 'td', $attrs, $cols) {
    $cells = array();

    foreach ($cols as $col) {
      $cells[] = $this->element($type, array(), $col);
    }

    return $this->element('tr', $attrs, $cells);
  }

  // HTML element
  public function element($tag, $attrs = array(), $content = ' ') {
    $element = '<' . $tag . ' ';

    foreach ($attrs as $attr => $val) {
      $element .= $attr . '="' . $val . '" ';
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
