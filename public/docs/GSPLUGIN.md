# GSPlugin
This class is designed to ease plugin, hook, and filter registration.

### SDK_VERSION
Current SDK version. Use this to check if another version of the SDK has been
loaded.

```php
if (GSPlugin::SDK_VERSION <= 0.1) {
  // ...
}
```

### __construct
Instantiate the plugin wrapper with data about the plugin.

#### `__construct($params)`

```php
// == CONSTRUCTOR PARAMETERS ==
// $params['id']      (string)         plugin id (normally the plugin folder)
// $params['author']  (string)         plugin author
// $params['version'] (string|num)     plugin version
// $params['website'] (string)         author website
// $params['tab']     (string)         main plugin tab
// $params['lang']    (string)         default language
// $params['deps']    (array (string)) plugin ids of plugins that need to be
//                                     installed for this plugin to load
```

```php
$plugin = new GSPlugin(array(
  'id'       => 'your_plugin',
  'version'  => '1.0',
  'author'   => 'You',
  'website'  => 'http://yourwebsite.com',
  'tab'      => 'plugin_page_tab',
  'lang'     => 'default_language',
));
```


### init
Initializes plugin. Must be called at the end in order for all of your elements
to be registered.

```php
// Finally:
$plugin->init();
```

### id
Get the plugin id (same as the one given in the constructor)

```php
echo 'The plugin id is ' . $plugin->id();
```

### author
Get the plugin author (same as one given in the constructor)

```php
echo 'The plugin author is ' . $plugin->author();
```

### version
Get the plugin version (same as one given in the constructor)

```php
echo 'The plugin version is ' . $plugin->version();
```

### website
Get the plugin website (same as one given in the constructor)

```php
echo 'The plugin website is ' . $plugin->website();
```

### path
Get the canonical path to your plugin's folder

```php
include $plugin->path() . 'somefile.php';
```

### tab
#### `tab()`
Get the plugin tab (same as one given in the constructor)
```php
echo 'The plugin tab is ' . $plugin->tab();
```

#### `tab($id, $label[, $action = null])`
Register a new top level tab for the plugin in the admin panel
```php
// Create new tab 'My Tab'
$plugin->tab('mytab', 'My Tab');

// Create 'My Other Tab' with the landing url
// your_plugin&myaction
$plugin->tab('myothertab', 'My Other Tab', 'myaction');
```

### sidebar
#### `sidebar($label[, $action, $visibility = true, $tab])`
Register a sidebar for the plugin. Defaults to the tab that you initialized the plugin with.

```php
// Add a sidebar link
$plugin->sidebar('My Plugin');

// Add sidebar link to url 'your_plugin&view'
$plugin->sidebar('View Items', 'view');

// Add sidebar link to url 'your_plugin&create' that only appears when inside
// your plugin
$plugin->sidebar('Create Item', 'create', false);

// Add sidebar link that appears on the 'plugins' tab
$plugin->sidebar('About Your Plugin', 'about', true, 'plugins');
```

### hook
Register a plugin [hook](http://get-simple.info/wiki/plugins:hooks_filters).

#### `hook($name, $callback[, $callbackargs])`

```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_footer() {
  echo 'This will be output in the theme footer';
}

// ...
$plugin->hook('theme-footer', 'your_plugin_footer');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function footer() {
    echo 'This will be output in the theme footer';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->hook('theme-footer', array($yp, 'footer'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/footer.php
echo 'This will be output in the theme footer';

// Registration file:
$plugin->hook('theme-footer', 'frontend/footer.php');
```

### trigger
Trigger a plugin hook action. Use this to allow other plugin authors to run actions
when your plugin has done something.

```php
// After performing some action...
$plugin->trigger('your-hook-name');
```

### filter
Register a plugin [filter](http://get-simple.info/wiki/plugins:hooks_filters).

```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_content($content) {
  $string = 'This will be prepended to the page content';
  return $string . $content;
}

$plugin->filter('content', 'your_plugin_content');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function content($content) {
    $string = 'This will be prepended to the page content';
    return $string . $content;
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->filter('content', array($yp, 'content'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/content.php
$content = $args[0];
$string = 'This will be prepended to the page content';
return $string . $content;

// Registration file:
$plugin->filter('content', 'frontend/content.php');
```

### script
Register a Javascript file to be loaded

##### `script($params)`

```php
// == PARAMETERS ==
// $params['id']      unique id for the script
// $params['src']     script source
// $params['baseurl'] base url for script; defaults to your plugin folder
// $params['version'] script version
// $params['where']   where to load: GSFRONT, GSBACK or GSBOTH
// $params['footer']  true to load script in footer
```

```php
// Loads your_plugin/js/script.js on the front and back end
$plugin->script(array(
  'id'    => 'your_plugin_script',
  'src'   => 'js/script.js',
  'where' => GSBOTH,
));
```

### style
Register a CSS sheet to be loaded

##### `style($params)`

```php
// == PARAMETERS ==
// $params['id']      unique id for the style
// $params['href']    style source
// $params['baseurl'] base url for style; defaults to your plugin folder
// $params['version'] style version
// $params['where']   where to load: GSFRONT, GSBACK or GSBOTH
// $params['media']   media type for style
```

```php
// Loads your_plugin/css/style.css on the front end
$plugin->style(array(
  'id' => 'your_plugin_style',
  'src' => 'css/style.css,
  'where' => GSFRONT,
));
```

### admin
Load the administration panel for your plugin
#### `admin($callback)`
Execute a function when your plugin's admin page is accessed
```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_admin() {
  echo 'This is your admin panel';
}

$plugin->admin('your_plugin_admin');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function admin() {
    echo 'This is your admin panel';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->admin(array($yp, 'admin'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/backend/admin.php
echo 'This is your admin panel';

// In your registration file:
$plugin->admin('backend/admin.php');
```

#### `admin($url, $callback)`
Execute the `$callback` when the admin's url resembles the `$url`.

* The `$callback` takes an `(array) $exports` parameter, which has a collection of
variables that are imported into the scope of your function.
* `$url` can be a normal string or a Regular Expression. If it is a RegEx, the
`matches` will be be available as an array on `$exports`.

    ```php
    // == EXPORTS PARAMETERS ==
    // $exports['plugin']   (GSPlugin) your plugin instance
    // $exports['matches']  (array)    matches made on $url (if it was a RegEx)
    ```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function createPage($exports) {
    echo 'This is the create page';
  }

  public function editPage($exports) {
    $edit = $exports['matches'][0];
    echo 'This is the edit page for ' . $edit;
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->admin('create',    array($yp, 'createPage'));
$plugin->admin('/edit=.*/', array($yp, 'editPage'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/backend/create.php
echo 'This is the create page';

// your_plguin/backend/edit.php
$edit = $exports['matches'][0];
echo 'This is the edit page for ' . $edit;

// Registration file:
$plugin->admin('create',    'backend/create.php');
$plugin->admin('/edit=.*/', 'backend/edit.php');
```

### index
Runs code on the front-end of your site. This is done to implement "custom page"
functionality (e.g. creating a blog plugin).

#### `index($url, $callback)`

* The `$callback` takes an `(array) $exports` parameter, which has a collection of
variables that are imported into the scope of your function.
* `$url` can be a normal string or a Regular Expression. If it is a RegEx, the
`matches` will be be available as an array on `$exports`.

    ```php
    // == EXPORTS PARAMETERS ==
    // $exports['plugin']   (GSPlugin) your plugin instance
    // $exports['matches']  (array)    matches made on $url (if it was a RegEx)
    // $exports['page']     (object)   object with data about the current page
    ```

```php
// == GLOBAL FUNCTION EXAMPLE ==
function your_plugin_index_page($exports) {
  echo 'You are on the front page of your plugin!';
}

function your_plugin_foo_page($exports) {
  $bar = $exports['matches'][0];
  echo 'You are on the foo/' . $bar . ' page of your plugin!';
}

// ...
$plugin->index('your-plugin', 'your_plugin_index_page');
$plugin->index('your-plugin', 'your_plugin_foo_page');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function indexPage($exports) {
    // Page title
    $page = $exports['page'];
    $page->title = 'Your Plugin Main Page';

    // Page contents
    echo 'You are on the front page of your plugin!';
  }

  public function fooPage($exports) {
    // Page title
    $page = $exports['page'];
    $page->title = 'Your Plugin Foo Page';

    // Page contents
    $bar = $exports['matches'][0];
    echo 'You are on the foo/' . $bar . ' page of your plugin!';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->index('your-plugin',           array($yp, 'indexPage'));
$plugin->index('/your-plugin/foo/(.*)', array($yp, 'fooPage'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/frontend/index.php
// Page title
$page = $exports['page'];
$page->title = 'Your Plugin Main Page';

// Page contents
echo 'You are on the front page of your plugin!';

// your_plugin/frontend/foo.php
// Page title
$page = $exports['page'];
$page->title = 'Your Plugin Foo Page';

// Page contents
$bar = $exports['matches'][0];
echo 'You are on the foo/' . $bar . ' page of your plugin!';

// ...
// In your plugin registration file:
$plugin->index('your-plugin',           'frontend/index.php');
$plugin->index('/your-plugin/foo/(.*)', 'frontend/foo.php');
```

### i18n
Returns and internationalized hash according to the languages you've defined
for your plugin (and if a hash doesn't exist, it will search for a built in one

```php
echo $plugin->i18n('PLUGIN_TITLE');
```

### autoload
Adds an autoloader to be registered when the plugin is initialized. Allows you
to `include` classes on fly.
#### `autoload($callback)`

```php
// == GLOBAL FUNCTION EXAMPLE ==
// Includes classes in your_plugin/lib/*.php (in lower case)
function your_plugin_lib($class) {
  include GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php';
}

// ...

$plugin->autoload('your_plugin_lib');
```

```php
// == CLASS METHOD EXAMPLE ==
class YourPlugin {
  // ...

  public function loadLib($class) {
    include GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php';
  }

  // ...
}

// ...
$yp = new YourPlugin(/* params */);

// ...
$plugin->autoload(array($yp, 'loadLib'));
```

```php
// == SCRIPT EXAMPLE ==
// your_plugin/loadlib.php
include GSPLUGINPATH . 'your_plugin/lib/' . strtolower($class) . '.php';

// In your plugin registration file:
$plugin->autoload('loadlib.php'));
```
