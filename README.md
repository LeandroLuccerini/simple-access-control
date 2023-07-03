# Simple Access Control

This project is about a simple system to check permissions about a textual label based action. 

The base concept is that you can define an [Action](./src/Domain/Action.php) that describes a possible process, then is possible to define a [Permission](./src/Domain/Permission.php) that allows or deny that action.

```php
<?php
// Defines the action with a label identifier
$productDetailViewAction = new Action('product.detail.view');
// Defines the permission that allow to view the product detail
$canViewProductDetailPermission = new Permission($productDetailViewAction, true);
```

### Strategies
Using the `PermissionCheckerStrategy` it's possible to check if a given `Action` is allowed or not against a [PermissionsCollection](./src/Domain/PermissionsCollection.php).
There are two types of strategies already defined:
- [Affirmative](./src/Domain/Checker/AffirmativePermissionCheckerStrategy.php): grants access as soon as there is one permission granting access;
- [Unanimous](./src/Domain/Checker/UnanimousPermissionCheckerStrategy.php): only grants access if there is no voter denying access.

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
As common use case there's the [UserAuthorizationService](./src/Application/UserAuthorizationService.php) that, given a [UserWithPermission](./src/Domain/User/UserWithPermissions.php) and an `Action`, has the capability to check if the user is allowed to perform the action.
`UserWithPermission` it's the helper interface that you must to implement to take advantage of the service.

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
$isAllowed = $service->canUserPerformAction($user, new Action('product.detail.edit')));
```
### Tests
To run all tests `composer test`

