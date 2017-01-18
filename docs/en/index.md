# Quickstart

Web page **menu** items manager for [Nette Framework](http://nette.org/)

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

## Usage

### Create menu & add items

```php
class BasePresenter extends Nette\Application\UI\Presenter
{
    use \IPub\Menu\TMenu;
    
    public function startup()
    {
        parent::startup();

        $this->menuManager->addItem(
            'userSettings',                                         // Menu name
            'user-profile: account settings',                       // Item ID
            'Account settings',                                     // Item name
            [
                'label'    => 'Account settings',                   // Item label, if not set item name will be used instead
                'target'   => ':UserProfile:Account:settings',      // Target link where this menu item should point
                'active'   => [
                    ':UserProfile:Account:settings',                // Rules to specify when ant isActive attribute should be added to item
                ],
                'access'   => [
                    'user@loggedIn'                                 // Access settings
                ],
                'priority' => 15,                                   // Item position in collection
            ],
            [
                'icon' => '/path/to/the/icon.png'                   // Add icon to the menu. It will be in data attribute of the menu item
            ]
        );
    }
}
```

#### Menu item **target**

This attribute accept fev variants. Target could be a route definition in full path *:Module:Presenter:action* or it could be an array if you need to pass some attributes to the route:

```php
[
    'target' => [
        ':Module:Presenter:action' => [
            'firstParam' => 'value',
            'secondParam' => 10,
            // etc.
        ]
    ],
]
```

or it could be a full url path to some page *https://www.ipublikuj.eu**

#### Menu item **active**

With this attribute you could specify for which route should be added attribute 'isActive' the menu item attributes. This attribute could be used during menu rendering.

This attribute accept array of routes. It also accept wildcard route definition: `:Module:Presenter:*` and for this case menu item will have `isActive` **TRUE** for all actions in presenter `Presenter`

#### Menu item **access**

With this attribute you could define access rights to the menu item. Acceptable values are this:

```php
[
    'access' => [
        'user@loggedIn',                                                        // or user@guest. This means that this item will be only for logged in or logget out users
        'resource@resourceName',                                                // It will check access to provided resource
        'privilege@privilegeName',                                              // It will check access to provided privilege for all resources
        'permission@resourceName:privilegeName, otherResource:otherPrivilege',  // This will check combination resource & privilege
        'role@roleName, anotherRole'                                            // And this will check if user is in provided role
    ],
]
```

### Obtaining menu items

When you want to render your menu items, just use one call on menu manager:

```php
$nodes = $this->menuManager->getTree('menu-name');
```

This call will return complete menu items tree created with nodes. In case you need some specific node in the tree, just pass its id:

```php
$nodes = $this->menuManager->getTree('menu-name', ['root' => 'wanted-item-id']);
```
