# Injector IoC

An easy to configure and flexible PSR-11 compliant IoC container for PHP.

## Basic Usage

The most simple use case is as below.

```php
$map = new \Injector\Binding\BindingMap;
$container = \Injector\ContainerFactory::makeContainer($map);

// Any type hinted constructor parameters will be auto-wired.
$class = $container->get(MyClass::class);
```

## Auto-Wiring

By default the container will automatically attempt to resolve any dependencies it can without them needing to be bound.

```php
class UserRepository
{
    public function __construct(EntityManager $user)
    ...
}
```

If the `EntityManager` class is instantiable (not an interface or abstract, and has no dependencies that can't be resolved) then the container will resolve it using the code below.

```php
$userRepository = $container->get(UserRepository::class);
```

However if the dependency is an interface or abstract it will need binding. There are different ways to do this.

### Auto-Wired Binding

The most common form of binding is by binding to a concrete so they can be auto wired.

```php
$map->add(
    MyInterface::class,
    new Injector\Dependency\AutoWireDependency(MyConcrete::class)
);
```

### Closure Binding

```php
$map->add(
    MyInterface::class,
    new Injector\Dependency\ClosureDependency(function($container){
        return new MyConcrete(
            $container->get(ChildInterface::class),
            'something-that-cannot-be-resolved'
        );
    })
);
```

In this example the closure will be run every time this dependency is requested. This means that if two classes require it within the same process they will both get new instances.

### Singleton Binding

```php
$childDependency = new Injector\Dependency\ClosureDependency(function($container){
    return new MyConcrete(
        $container->get(ChildInterface::class),
        'something-that-cannot-be-resolved'
    );
});

$map->add(
    MyInterface::class,
    new Injector\Dependency\SingletonBinding($childDependency)
);
```

In this case the closure within `$childDependency` will be run the first time it is requested and then cached. Subsequent calls will result in the same instance being returned.

Child dependencies for singletons can also be auto wire bindings. This is useful when you always want the same instance and isn't limited to interface binding.

```php
$childDependency = new Injector\Dependency\AutoWireDependency(MyClass::class);

$map->add(
    new Injector\Dependency\SingletonDependency(MyClass::class, $childDependency)
);
```

By binding a class to itself as a singleton it ensures the same instance will always be returned.

## Injecting the Container

The following reserved bindings can be used to access the container as a dependency.

`Injector\Container` and `Psr\Container\ContainerInterface` will always resolve to the instance of the container.

`Injector\Binding\BindingMapInterface` will always resolve to the binding map in use by the container.

This is useful when you might want programmatic binding through providers.

```php
class UserServiceProvider
{
    private $bindingMap;

    public function __construct(\Injector\Binding\BindingMapInterface $bindingMap)
    {
        $this->bindingMap = $bindingMap;
    }

    public function bindDependencies()
    {
        $this->bindingMap->add(
            UserRepositoryInterface::class,
            new \Injector\Binding\AutoWireBinding(UserRepository::class)
        );
    }
}
```
