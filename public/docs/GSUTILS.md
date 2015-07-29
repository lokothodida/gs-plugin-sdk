# GSUtils
This library is meant to handle general utility-based problems in plugin development:

* Directory management
* File creation/manipulation
* String manipulation

### SDK_VERSION
### __construct

```php
// == CONSTRUCTOR PARAMETERS ==
// $params['basepath']  base path for operations; default is GSDATAOTHERPATH
```

```php
$utils = new GSUtils(array(
  // TODO
));
```

### mkdir
Makes a directory and initializes it with an htaccess file. Throws an exception
if the directory could not be made.

#### `mkdir($path[, $mode = 0755, $recursive = true, $htaccess = true])`

```php
try {
  $utils->mkdir('yourdir');
} catch (Exception $error) {
  echo 'Could not create directory';
}
```

### rmdir
Removes a directory. If `$force` is set to true, all
files and directories inside will be removed. Throws an exception if there was
an error deleting the directory.

#### `rmdir($path[, $force = false])`

```php
try {
  $utils->rmdir('yourdir', true);
} catch (Exception $error) {
  echo 'Error removing directory';
}
```

### mvdir
Moves a directory

```php
try {
  $utils->mvdir('yourdir', 'anotherdir');
} catch (Exception $error) {
  echo 'Could not move directory';
}
```

### copy
Copy a directory. Throws exception if there is an error copying the directory.

```php
try {
  $utils->copy('yourdir', 'anotherdir');
} catch (Exception $error) {
  echo 'Could not copy directory';
}
```

### mkfile
Makes a file.

#### `mkfile($jsonfile, $data)`
```php
try {
  $utils->mkfile('yourdir/data.json', array(
    'key1' => 'value1',
    'key2' => 'value2',
  ));
} catch (Exception $error) {
  echo 'Error creating file';
}
```

#### `mkfile($otherfile, $data)`

### putfile

Writes contents to the file (same signature as mkfile). Throws exception if there
was an error writing to the file.

```php
try {
  $utils->putfile('yourdir/data.json', array(
    'key1' => 'value1',
    'key2' => 'value2',
  ));
} catch (Exception $error) {
  echo 'Error putting file contents';
}
```

### rmfile
Deletes a file.

```php
try {
  $utils->rmfile('yourdir/data.json');
} catch (Exception $error) {
  echo 'Error deleting file';
}
```

### mvfile
Deletes a file.

```php
try {
  $utils->rmfile('yourdir/data.json', 'somedir/data.json');
} catch (Exception $error) {
  echo 'Error moving file';
}
```

### getfile
Gets file contents. Parses the contents if the file is a JSON.

```php
try {
  $file = $utils->getfile('yourdir/data.json');
  echo $file['key1'];
} catch (Exception $error) {
  echo 'Error getting file';
}
```

### getfiles
Gets array of file contents. Parses the contents if the file is a JSON.

```php
try {
  $files = $utils->getfiles('yourdir/*.json');
  foreach ($files as $filename => $data) {
    // ...
  }
} catch (Exception $error) {
  echo 'Error getting files';
}
```

### fileexists
Returns `true` iff the file exists.

```php
if ($utils->fileexists('yourdir/data.json')) {
  // ...
}
```

### iswriteable
Returns true iff the file is writeable

```php
if ($utils->iswriteable('yourdir/data.json')) {
  // ...
}
```

### print
Pretty-prints variables.

```php
echo $utils->print($var1, $var2, $var3);
```

### slug
Slugify a string.

```php
$slug = $utils->slug('SOMEthing HErE'); // something-here
```

### translit
Transliterate a string.

```php
```

### clean
Sanitize a string.

```php
```
