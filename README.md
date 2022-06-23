![LightFramework](./docs/header.png)

# LightFramework

## Framework to Make Framework

LightFramework is a minimal library to make your own framework according to your needs. You can use any component, like Router, Cache etc and Register them. LightFramework also provided functionality for `deferred calling`. `Deferred` functions are called at end based on priority.

## About Author
[Nabeel Ali](https://iconiccodes.com) | [https://iconiccodes.com](https://iconiccodes.com) | [mail2nabeelali@gmail.com](mailto:mail2nabeelali@gmail.com)


## Motivation

A framework enables developer to write managable code and makes it easy for them to manage the code. But problem is that most frameworks are bloated. They have the features you don't require. That makes not suitable for small projects and causes fall in performance. 

For Example
*  You require only Router and Database for API. You can use LightFramework and install only Routing and Database plugins. No bloatware of email library, hashing libraries etc.

* You want a static website. You can use Router only.

* You want a website with dynamic content, You can use Router and Template Engine.

## Features
    * Easy
    * Very minimum code for initialization.
    * Deferred calls
    * Call deffered calls prematurely when required.
    * Add callables as plugin


## Installtion
Install via Composer

```
composer require nabeelalihashmi/LightFramework
```

Or Download the class and use own autoloading function.


## Usage

* Create new instance of LightFramework
It requires 4 arguments
  - `config`: Path of directory where configuration files are stored.
  - `autoload` : Path of directory where files for autoloading are stored.
  - `global_symbol` : The symbol from which instance of LightFramework will be asscessable globally. Default is `__`

Example:
```
$light = new LightFramework('config', 'autoload', '__');

```
* Plugin the modules. with `plugModule()` method. It requires 3 arguments
- `key`: The name of the plugin module which will accessed later using `$light->getModule($key)`
- `callable`: the callable plugin
- `callback`: the callback called by framework when object is loaded.

Example:
```
$demo = new Demo(10, 20);
$light->pluginModule('demo', $demo, function($demo) {
  $demo->showMessage();
});

```

* Initiate the Framework
```
$light->init();
```

## NOTE
When `init` is called, first autoload libs in autoload direcotry all loaded. After that plugin in autoload_module.config.php file are loaded.

-------------------------

## License

LightFramework is released under permissive licese with following conditions:

* It cannot be used to create adult apps.
* It cannot be used to create gambling apps.
* It cannot be used to create apps having hate speech.
* You must mention LightFramework in credits.

### MIT License

Copyright 2022 Nabeel Ali | IconicCodes.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

