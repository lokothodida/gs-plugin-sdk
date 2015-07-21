<?php

class SDK extends GSPlugin {
  // == PUBLIC INTERFACES ==
  // Initialization
  public static function init() {
    
  }
  
  // Sidebar
  public static function sideBar() {
    return array(
      array('label' => static::i18n('PLUGIN_TITLE')),
    );
  }

  // Hooks
  public static function hooks() {
    return array(
      
    );
  }
  
  // Administration Panel
  public static function adminPanel() {




    // Initialization
    try {
      GSPluginUtils::mkDir('dummy2');
      GSPluginUtils::mkFile('dummy2/test', array(
        'test' => 'example',
        'yes' => true,
        'nestedstuff' => array(
          'more' => 'hehe',
          'yes' => 'haha',
          '@attributes' => array('key' => 'value', 'another' => 'val'),
        ),
        'items' => array(
          'item' => array(
            //array('key' => 'value', 'another' => 'val'),
            'stuff',
            'here',
          ),
        ),
        'evenmorenestedstuff' => array(
          'hehe',
          'haha',
          //'@attributes' => array('key' => 'value', 'another' => 'val'),
        ),
        //'@attributes' => array('shouldnt work'),
      ));

      echo GSPluginUI::success('Success!');
    } catch (Exception $error) {
      echo GSPluginUI::error($error->getMessage());
    }

    // Headings
    echo GSPluginUI::title(static::i18n('PLUGIN_TITLE'), true);
    echo GSPluginUI::quickNav(array(
      array(
        'url' => static::adminUrl(),
        'label' => static::i18n('PLUGINS'),
      ),
      array(
        'url' => static::adminUrl('create'),
        'label' => static::i18n('CREATE'),
      ),
      array(
        'url' => static::adminUrl('api'),
        'label' => static::i18n('API'),
      ),
    ));

    // Pages
    static::adminRoute('', 'admin/index.php');
    static::adminRoute('api', 'admin/api.php');
    static::adminRoute('create', 'admin/create.php');
    static::adminRoute('edit', 'admin/edit.php');
  }

  // Front pages
  public static function index() {
    static::indexRoute('/sdk(\/)?\?p=(\d+)/', 'index/example2.php');
    static::indexRoute('sdk', 'index/example.php');
    //static::indexRoute('/sdk\//', 'index/example2.php');
    static::indexRouteRun();
  }
  
  // Create a Plugin from the SDK
  protected static function createPlugin(array $data) {
    // Validate
    if (empty($data['id'])) {
      throw new Exception(static::i18n('NO_EMPTY_FIELDS'));
    }

    $destination = GSPLUGINPATH . $data['id'];

    if (file_exists($destination) || file_exists($destination . '.php')) {
      throw new Exception(static::i18n('PLUGIN_NAME_TAKEN', $data['id']));
    }
    
    // Create the files
    $folder = GSPLUGINPATH . '/' . $data['id'];
    $file = $folder . '.php';
    $copyOfFile = GSPLUGINPATH . static::getId() . '.php';

    // Make the plugin directories
    $directories = array(
      $data['id'],
      $data['id'] . '/css',
      $data['id'] . '/img',
      $data['id'] . '/js',
      $data['id'] . '/lib',
      $data['id'] . '/lang',
      $data['id'] . '/admin',
      $data['id'] . '/front',
    );

    foreach ($directories as $directory) {
      $dir = GSPLUGINPATH . '/' . $directory;
      $mkdir = @mkdir($dir);
      if (!$mkdir) {
        throw new Exception(self::i18n('EXCEPTION_MK_DIR', $dir));
      }
    }

    
    // Copy the SDK files
    $source = GSPLUGINPATH . static::getId();
    $files = array(
      'sdk.php',
      'sdk/lib/gspluginutils.php',
      'sdk/lib/gspluginui.php',
      'sdk/lib/gsplugin.php',
      'sdk/register.php',
    );
    
    foreach ($files as $file) {
      $from = GSPLUGINPATH . '/' . $file;
      $to   = str_replace(static::getId(), $data['id'], $from);
      $copy = @copy($from, $to);
      if ($copy === false) {
        throw new Exception(self::i18n('EXCEPTION_COPY_FILE', $from));
      }
    }
    
    // Make a development file (so it can be edited in the SDK)
    file_put_contents($destination . '/dev.php', '');

    // Info file
    static::savePluginInfo($destination, $data);
    
    // Rename the dummy file
    $pluginContents = file_get_contents($source . '/lib/dummyplugin.php');
    $pluginContents = str_replace('class Dummy extends', 'class ' . $data['className'] . ' extends', $pluginContents);
    file_put_contents($destination . '/lib/plugin.php', $pluginContents);
    
    //throw new Exception();
  }

  // Save plugin info
  private static function savePluginInfo($destination, $data) {
    $infoContents = "<?php \n\$info = array(\n  'id' => \$id,\n";
    $info = array('author', 'version', 'website', 'defaultLang', 'tab');
    foreach ($info as $key) {
      $infoContents .= "  '" . $key . "' => '" . $data[$key] . "',\n";
    }
    $infoContents .= ');';
    $succ = @file_put_contents($destination . '/info.php', $infoContents);

    if (!$succ) {
      throw new Exception(static::i18n('EXCEPTION_COULDNT_SAVE_INFO'));
    }
  }
  
  // Update a plugin's info
  protected static function updatePluginInfo(array $data) {
    $destination = GSPLUGINPATH . '/' . $data['id'];
    
    // Check if a renaming is necessary
    if ($data['prevId'] != $data['id']) {
      $nameTaken = static::pluginExists($data['id']);
      if ($nameTaken) {
        throw new Exception(static::i18n('EXCEPTION_PLUGIN_NAME_TAKEN'));
      }

      // Rename files
      $source = GSPLUGINPATH . '/' . $data['prevId'];
      $files = array('', '.php');
      foreach ($files as $file) {      
        $rename = @rename($source . $file, $destination . $file);

        if (!$rename) {
          throw new Exception(static::i18n('EXCEPTION_RENAME_PLUGIN'));
        }  
      }
    }

    // Now we can save the info
    return static::savePluginInfo($destination, $data);
  }

  // Check that a plugin exists
  protected static function pluginExists($id) {
    $path = GSPLUGINPATH . '/' . $id;
    $folderExists = file_exists($path);
    $fileExists = file_exists($path . '.php');

    return ($folderExists && $fileExists);
  }
  
  // Get a plugin's classname
  protected static function getExternalPluginClassName($plugin) {
    $file = GSPLUGINPATH . '/' . $plugin . '/lib/plugin.php';
    $contents = @file_get_contents($file);
    
    if (!$contents) {
      throw new Exception(static::i18n('EXCEPTION_CLASS_NO_EXIST'));
    } else {
      preg_match("/class ([a-zA-Z0-9]*) extends GSPlugin/", $contents, $matches);
      if (isset($matches[1])) {
        return $matches[1];
      } else {
        throw new Exception('Class not found');
      }
    }
  }
  
  // Get the current class name (late static-binding workaround for PHP < 5.3)
  protected static function getClass() {
    return get_class();
  }
}
