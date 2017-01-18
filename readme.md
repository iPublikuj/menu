# Menu

[![Build Status](https://img.shields.io/travis/iPublikuj/menu.svg?style=flat-square)](https://travis-ci.org/iPublikuj/menu)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/menu.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/menu/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/menu.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/menu/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/menu.svg?style=flat-square)](https://packagist.org/packages/ipub/menu)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/menu.svg?style=flat-square)](https://packagist.org/packages/ipub/menu)
[![License](https://img.shields.io/packagist/l/ipub/menu.svg?style=flat-square)](https://packagist.org/packages/ipub/menu)

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
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/menu](http://github.com/iPublikuj/menu).
