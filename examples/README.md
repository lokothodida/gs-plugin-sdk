# Examples built with the SDK
This folder contains example plugins. Ideally, each plugin should:

* Work out of the box
* Illustrate how to implement common plugin design patterns and features using the SDK
* Have well-documented, explanatory source code

To install an example plugin, copy the folder and its corresponding PHP file
from this directory to your GetSimple `plugins` folder, and enable it from the
`Plugins` administration panel tab.

# Examples
### hello
A simple 'Hello World' plugin that outputs 'Hello World' into theme footers.

### settings
Custom (dummy) settings page.

#### Illustrates

* Building admin UI
* Creating/saving data

### pages
Custom page functionality designed to look like the built-in Pages functionality.

#### Illustrates

* Building admin UI
* Creating/saving data
* Displaying data on the front-end

### blog
Builds on **pages** to implement a simple blogging plugin.

#### Illustrates

* Building admin UI
* Creating/saving data
* Querying data
* Displaying data on the front-end

### items
Simple items manager that builds on **pages** and uses **I18N Search** for search indexing.

#### Illustrates

* Building admin UI
* Creating/saving data
* Querying data
* Inter-plugin dependency management
* Displaying data on the front-end
