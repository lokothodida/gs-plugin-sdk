# Getsimple (GS) Utilities Polyfill
The purpose of this project is to provide a library that is:

* **portable** *(easy to add to add/remove from a plugin)*
* **unified** *(has a consistent, intuitive and stable interface)*
* **backward/forward compatible** *(works with old and new GetSimple versions)*
* **well documented**

in order to simplify the typical problems that arise during GetSimple plugin development. These problems include (but are not limited to):

* Building data structures for your plugin
* Sanitizing user inputs and avoiding XSS vulnerabilities
* Remaking/copying methods that exist in the core
* Accessing site data such as site/current user settings
* Building UI elements consistent with administration panel between versions

This project is a "polyfill" because in the ideal scenario, many of these functions will be integrated in the future into the GetSimple core in some manner. If/when they do, the methods in this repository will become wrappers for the native functions (thus fulfilling the backward/forward compatible point above).

# Getting Started
Head over to the [wiki](https://github.com/lokothodida/gs-utils-polyfill/wiki).
