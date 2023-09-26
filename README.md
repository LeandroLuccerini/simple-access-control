# Simple Access Control

This project is about a simple system to check permissions about a textual labeled action. 

The base concept is that you can define an [Action](./src/Domain/Action.php) that describes a possible process, then is possible to define a [Permission](./src/Domain/Permission.php) that allows or deny that action.

```php
<?php
// Defines the action with a label identifier
$productDetailViewAction = new Action('product.detail.view');
// Defines the permission that allow to view the product detail
$canViewProductDetailPermission = new Permission($productDetailViewAction, true);
```

### Hierarchical permissions
You can define action labels in such a way that they behave like a hierarchical structure. The basic idea is to use a sequence of nouns, separated by an arbitrary string, in order to create a parent-child relationship. This way, it will be possible to define a permission(#permission-checker-strategies) for an entire family of actions.

#### Action name parser strategies
What has been mentioned in the previous section is achieved using the `ActionNameParserStrategy`, which defines how to parse the `Action` label to split it into an array of individual elements whose sequence defines the path of the parent-child relationship. 
There are two already defined strategies:
- [NullActionNameParserStrategy](./src/Domain/Parser/NullActionNameParserStrategy.php): performs no action and treats the labels as a single string without breaking them down;
- [DotSeparatedActionNameParserStrategy](./src/Domain/Parser/DotSeparatedActionNameParserStrategy.php): parses the action label using the period `.` character as a separator; 

```php
// Defines the parser strategy
$dotSeparatedParser = new DotSeparatedActionNameParserStrategy(); 
// Defines two action that are related 
$productAction = new Action('product', $dotSeparatedParser);
// `product` is the root
// `detail` is the child of product
// `view` child of detail and nephew of product
$productDetailViewAction = new Action('product.detail.view', $dotSeparatedParser);
```
This way, it will be possible to define, for example, a permission that grants privileges for the root `product`, so that those who possess this permission will also be granted any permissions for "child" actions of `product` (e.g., `product.detail.view`), based on the [decision-making strategy used](#permission-checker-strategies).


### Permission checker strategies
Using the `PermissionCheckerStrategy` it's possible to check if a given `Action` is allowed or not against a [PermissionsCollection](./src/Domain/PermissionsCollection.php).
There are two types of strategies already defined:
- [Affirmative](./src/Domain/Checker/AffirmativePermissionCheckerStrategy.php): grants access as soon as there is one permission granting access;
- [Unanimous](./src/Domain/Checker/UnanimousPermissionCheckerStrategy.php): only grants access if there is no permission denying access.

```php
<?php

$permissions = [
    new Permission(new Action('product.detail.view'), true),
    new Permission(new Action('product.detail.edit'), false),
];

$strategy = new AffirmativePermissionCheckerStrategy();
// This returns `true`
$isAllowed = $strategy->canPerformAction(
    new Action('product.detail.view'),
    new PermissionsCollection($permissions)
);
```
Using the hierarchical feature, if a permission of a parent action is granted, then the child action is granted using the `AffirmativePermissionCheckerStrategy`
```php
<?php
// Defines the parser strategy
$dotSeparatedParser = new DotSeparatedActionNameParserStrategy(); 

$permissions = [
    new Permission(new Action('product', $dotSeparatedParser), true),
    new Permission(new Action('product.detail.edit', $dotSeparatedParser), false),
];

$strategy = new AffirmativePermissionCheckerStrategy();
// This returns `true`
$isAllowed = $strategy->canPerformAction(
    new Action('product.detail.edit', $dotSeparatedParser),
    new PermissionsCollection($permissions)
);
```

If a permission against an action is not defined the strategy **will always deny the action**.

```php
<?php

$permissions = [
    new Permission(new Action('product.detail.view'), true),
    new Permission(new Action('product.detail.edit'), false),
];

$strategy = new AffirmativePermissionCheckerStrategy();
// This returns `false`
$isAllowed = $strategy->canPerformAction(
    // This action is not associated to any permission in the collection
    new Action('product.add'),
    new PermissionsCollection($permissions)
);
```

### Services
As common use case the package offers the [UserAuthorizationService](./src/Application/UserAuthorizationService.php) that, given a [UserWithPermission](./src/Domain/User/UserWithPermissions.php) and an `Action`, has the capability to check if the user is allowed to perform the action.
`UserWithPermission` is the helper interface that you must implement to take advantage of the service.

```php
<?php

final class MyDomainUser implements UserWithPermission
{
    // some code...
    
    public function getPermissions(): PermissionsCollection
    {
        return new PermissionsCollection([
            new Permission(new Action('product.detail.view'), true),
            new Permission(new Action('product.detail.edit'), false),
        ]);
    }
}

```
Then the service.
```php
<?php

$user = new MyDomainUser();

$service = new UserAuthorizationService(
    new AffirmativePermissionCheckerStrategy()
);
// This returns `false`
$isAllowed = $service->canUserPerformAction($user, new Action('product.detail.edit'));
```
### Tests
To run all tests `composer test`

