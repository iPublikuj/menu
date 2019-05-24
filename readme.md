# Menu


**This package is abandoned and no longer maintained!**



Web page **menu** items manager for [Nette Framework](http://nette.org/)

## Introduction

This extensions add ability to manage menu groups and their items and display them in your application based on Nette framework

## Installation

The best way to install ipub/menu is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/menu
```

After that you have to register extension in config.neon.

```neon
extensions:
	menu: IPub\Menu\DI\MenuExtension
```

Package contains trait, which you will have to use in class, where you want to use mobile detector.

```php
<?php

class BasePresenter extends Nette\Application\UI\Presenter
{
    use IPub\Menu\TMenu;
    
    // Rest of code...
}
```

## Documentation

Learn how to use menu manager for handling menu items in [documentation](https://github.com/iPublikuj/menu/blob/master/docs/en/index.md).

***
Homepage [https://www.ipublikuj.eu](https://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/menu](http://github.com/iPublikuj/menu).
