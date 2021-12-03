# Magento 2 module to quickly access product, order or customer views

## Introduction
The Magento 2 backend can be sluggish.

Ever wanted to access a product or order via the backend? Going to the list view, wait for the list to load, enter filter, wait for the results, look for the item you want and click it.... it takes too long if you already know where you want to go.

This module tries to help out with that. It creates an extra menu item called "Quick View". Using this you can enter a product sku, product id, order increment id, order id, customer email or customer id, and you will view that item directly. No more filtering and waiting.

Extending the module to add custom fields to the menu has been made easy, see below. 

## Installation
Install package using composer
```sh
composer require fkruidhof/adminquickview
```
This will install fkruidhof/adminquickview

Enable module and run installers
```sh
php bin/magento module:enable Fkruidhof_AdminQuickView
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## Configuration
There are no configurations. When the module is installed it is automatically active. An extra "Quick View" menu item will be displayed in the admin menu.
The module does have an ACL resource, so please make sure to activate the "Admin Quick View actions" role resource for the roles of the users that wish to use the module.

## Extending the functionality
Extra fields can be easily added to the menu. Just make a new module with a model class that implements QuickViewInterface and add it to the QuickViewPool via adminhtml/di.xml.

adminhtml/di.xml example:
```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <type name="Fkruidhof\AdminQuickView\Model\QuickViewPool">
        <arguments>
            <argument name="quickViews" xsi:type="array">
                <item name="custom_quickview" xsi:type="object" sortOrder="10">Vendor\Module\Model\QuickView\Custom</item>
            </argument>
        </arguments>
    </type>
</config>

```

Keep in mind that the idea behind this module is to redirect to a url, nothing more, nothing less.

## Security considerations
The module uses it's own ACL resources, but more importantly: it just redirects the user to a url. No access controls are bypassed using this module.

## Compatibility
PHP >=7.4

Only tested on Magento 2.4.x with the default admin theme.
