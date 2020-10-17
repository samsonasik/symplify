# 40+ PHPStan Rules

## No "ValueObject" suffix in Value Object class names

- class: [`NoSuffixValueObjectClassRule`](../src/Rules/NoSuffixValueObjectClassRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoSuffixValueObjectClassRule
        tags: [phpstan.rules.rule]
```

```php
namespace App\ValueObject;

final class PersonValueObject
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
```

:x:

<br>

## No Container used in `__construct`

- class: [`NoContainerInjectionInConstructorRule`](../src/Rules/NoContainerInjectionInConstructorRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoContainerInjectionInConstructorRule
        tags: [phpstan.rules.rule]
```

```php
class SomeClass
{
    public function __construct(ContainerInterface $container)
    {
        $container->get('...');
    }
}
```

:x:

<br>

## Forbid Nodes of your Choice

- class: [`NoParticularNodeRule`](../src/Rules/NoParticularNodeRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoParticularNodeRule
        tags: [phpstan.rules.rule]
        arguments:
            forbiddenNodes:
                - PhpParser\Node\Expr\Empty_
                - PhpParser\Node\Stmt\Switch_
```

```php
class SomeClass
{
    public function run()
    {
        return empty('...');
    }
}
```

:x:

<br>

## Interface must be suffixed with "Interface"

- class: [`SuffixInterfaceRule`](../src/Rules/SuffixInterfaceRule.php)

```php
interface SomeClass
{
}
```

:x:

```php
interface SomeInterface
{
}
```

:+1:

## Trait must be suffixed with "Trait"

- class: [`SuffixTraitRule`](../src/Rules/SuffixTraitRule.php)

```php
trait SomeClass
{
}
```

:x:

```php
trait SomeTrait
{
}
```

:+1:

<br>

## `abstract` class name must be prefixed with "Abstract"

- class: [`PrefixAbstractClassRule`](../src/Rules/PrefixAbstractClassRule.php)

```php
abstract class SomeClass
{
}
```

:x:

```php
abstract class AbstractSomeClass
{
}
```

:+1:

<br>

## Regex Constants Must end With "_REGEX"

- class: [`RegexSuffixInRegexConstantRule`](../src/Rules/RegexSuffixInRegexConstantRule.php)

```php
use Nette\Utils\Strings;

class SomePath
{
    public const SOME_NAME = '#some\s+name#';

    public function run($value)
    {
        $somePath = Strings::match($value, self::SOME_NAME);
    }
}
```

:x:

```php
use Nette\Utils\Strings;

class SomePath
{
    public const SOME_NAME_REGEX = '#some\s+name#';

    public function run($value)
    {
        $somePath = Strings::match($value, self::SOME_NAME_REGEX);
    }
}
```

:+1:

<br>

## __DIR__ . '/*' paths must Exist

- class: [`NoMissingDirPathRule`](../src/Rules/NoMissingDirPathRule.php)

```php
class SomePath
{
    public function run()
    {
        $somePath = __DIR__ . '/missing_file.php';
    }
}
```

:x:

<br>

## Require Cnstant Used in Specific Method Call Position

- class: [`RequireConstantInMethodCallPositionRule`](../src/Rules/RequireConstantInMethodCallPositionRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\RequireConstantInMethodCallPositionRule
        tags: [phpstan.rules.rule]
        arguments:
            requiredLocalConstantInMethodCall:
                AlwaysCallMeWithConstantLocal:
                    some_type: [0] # positions
            requiredExternalConstantInMethodCall:
                AlwaysCallMeWithConstantExternal:
                    some_type: [0] # positions
```

```php
<?php


declare(strict_types=1);

class SomeClass
{
    private const SOME = 'SOME';
    public function run(): void
    {
        $alwaysCallMeWithConstantLocal = new AlwaysCallMeWithConstantLocal();
        $alwaysCallMeWithConstantLocal->call('someValue');
        // should be: $alwaysCallMeWithConstant->call(self::SOME);

        $alwaysCallMeWithConstantExternal = new AlwaysCallMeWithConstantExternal();
        $alwaysCallMeWithConstantExternal->call(self::SOME);
        // should be: $alwaysCallMeWithConstantExternal->call(SomeClass::SOME);
    }
}
```

:x:

<br>

## Test methods by Type Must Use Data Provider

- class: [`RequireDataProviderTestMethodRule`](../src/Rules/RequireDataProviderTestMethodRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\RequireDataProviderTestMethodRule
        arguments:
            classesRequiringDataProvider:
                - '*RectorTestCase'
```


```php
class SomeRectorTestCase extends RectorTestCase
{
    public function test()
    {
    }
}
```

:x:

```php
class SomeRectorTestCase extends RectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test($value)
    {
    }

    public function provideData()
    {
        // ...
    }
}
```

:+1:

<br>

## Create Objects only inside Factory

- class: [`NoNewOutsideFactoryRule`](../src/Rules/NoNewOutsideFactoryRule.php)

```php
class SomeClass
{
    public function run()
    {
        $someObject = new SomeObject();
        // ...
        return $someObject;
    }
}
```

:x:

```php
class SomeObjectFactory
{
    public function create()
    {
        $someObject = new SomeObject();
        // ...
        return $someObject;
    }
}
```

:+1:

<br>

## Add regex.com link to Pattern Constants

- class: [`AnnotateRegexClassConstWithRegexLinkRule`](../src/Rules/AnnotateRegexClassConstWithRegexLinkRule.php)

```php
class SomeClass
{
    private const COMPLICATED_REGEX = '#some_complicated_stu|ff#';
}
```

:x:

```php
class SomeClass
{
    /**
     * @see https://regex101.com/r/SZr0X5/12
     */
    private const COMPLICATED_REGEX = '#some_complicated_stu|ff#';
}
```

:+1:

<br>

## Use Constant Regex Patterns over Inlined Strings

- class: [`NoInlineStringRegexRule`](../src/Rules/NoInlineStringRegexRule.php)

```php
class SomeClass
{
    public function run($value)
    {
        return preg_match('#some_stu|ff#', $value);
    }
}
```

:x:

```php
class SomeClass
{
    /**
     * @var string
     */
    private const NAMED_REGEX = '#some_stu|ff#';

    public function run($value)
    {
        return preg_match(self::NAMED_REGEX, $value);
    }
}
```

:+1:

<br>

## Use +-pre instead of post+- to Prevent 2 Values on 1 line

- class: [`NoPostIncPostDecRule`](../src/Rules/NoPostIncPostDecRule.php)

```php
class SomeClass
{
    public function run($value = 1)
    {
        if ($value--) {
            // 1 or 0?
        }
    }
}
```

:x:

<br>

## No protected element in final class

- class: [`NoProtectedElementInFinalClassRule`](../src/Rules/NoProtectedElementInFinalClassRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoProtectedElementInFinalClassRule
        tags: [phpstan.rules.rule]
```

```php
final class SomeFinalClassWithProtectedPropertyAndProtectedMethod
{
    protected $x = [];

    protected function run()
    {
    }
}
```

:x:

<br>

## Use Contract or Service over Abstract Method

- class: [`NoAbstractMethodRule`](../src/Rules/NoAbstractMethodRule.php)

```php
abstract class SomeClass
{
    abstract public function run();
}
```

:x:

<br>

## Require UpperCase Class Constants

- class: [`UppercaseConstantRule`](../src/Rules/UppercaseConstantRule.php)

```php
final class SomeClass
{
    public const some = 'value';
}
```

:x:

<br>

## Keep Variable Not Too Long

- class: [`TooLongVariableRule`](../src/Rules/TooLongVariableRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\TooLongVariableRule
        tags: [phpstan.rules.rule]
        arguments:
            maxVariableLength: 40
```

```php
final class SomeClass
{
    public function run($superLongVariableThatGoesBeyongReadingFewWords)
    {
        return $superLongVariableThatGoesBeyongReadingFewWords;
    }
}
```

:x:

<br>

## Keep Low Public Elements in a Class

- class: [`ExcessivePublicCountRule`](../src/Rules/ExcessivePublicCountRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ExcessivePublicCountRule
        tags: [phpstan.rules.rule]
        arguments:
            maxPublicClassElementCount: 10
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public const NAME = 'value';

    public $value;

    public function run(): void
    {
    }

    // ...
}
```

:x:

<br>

## Prefer Static Call over specific Function

- class: [`PrefferedStaticCallOverFuncCallRule`](../src/Rules/PrefferedStaticCallOverFuncCallRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\PrefferedStaticCallOverFuncCallRule
        tags: [phpstan.rules.rule]
        arguments:
            funcCallToPrefferedStaticCalls:
                'preg_match': ['Nette\Utils\Strings', 'match']
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run()
    {
        return preg_match('#\d+#', 'content 1');
    }
}
```

:x:

<br>

## Prefer Method Call over specific Function

- class: [`PrefferedMethodCallOverFuncCallRule`](../src/Rules/PrefferedMethodCallOverFuncCallRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\PrefferedMethodCallOverFuncCallRule
        tags: [phpstan.rules.rule]
        arguments:
            funcCallToPrefferedMethodCalls:
                'file_get_contents': ['Symplify\SmartFileSystem\SmartFileSystem', 'readFile']
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run()
    {
        return file_get_contents('foo.txt');
    }
}
```

:x:

<br>

## Keep Low Parameter Count in Methods and Functions

- class: [`ExcessiveParameterListRule`](../src/Rules/ExcessiveParameterListRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ExcessiveParameterListRule
        tags: [phpstan.rules.rule]
        arguments:
            maxParameterCount: 8
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run($one, $two, $three, $four, $five, $six): void
    {
    }
}
```

:x:

<br>

## No Static Calls

- class: [`NoStaticCallRule`](../src/Rules/NoStaticCallRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoStaticCallRule
        tags: [phpstan.rules.rule]
        arguments:
            allowedStaticCallClasses:
                - 'Symplify\PackageBuilder\Console\Command\CommandNaming'
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run()
    {
        return StaticClass::staticMethod();
    }
}
```

:x:

<br>

## No Static Property

- class: [`NoStaticPropertyRule`](../src/Rules/NoStaticPropertyRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoStaticPropertyRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    protected static $customFileNames = [];
}
```

:x:

<br>

## No Trait Except Its methods public and Required via @required Docblock

- class: [`NoTraitExceptItsMethodsPublicAndRequiredRule`](../src/Rules/NoTraitExceptItsMethodsPublicAndRequiredRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoTraitExceptItsMethodsPublicAndRequiredRule
```

```php
<?php


declare(strict_types=1);

trait SomeTrait
{
    /**
     * @required
     */
    public function run()
    {

    }
}
```

:x:

<br>

## Use Explicit String over ::class Reference on Specific Method Call Position

Useful for PHAR prefixing with [php-scoper](https://github.com/humbug/php-scoper) and [box](https://github.com/humbug/box). This allows you to keep configurable string-classes unprefixed. If `::class` is used, they would be prefixed with `Prefix30281...`, so the original class would never be found.

- class: [`RequireStringArgumentInMethodCallRule`](../src/Rules/RequireStringArgumentInMethodCallRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\RequireStringArgumentInMethodCallRule
        tags: [phpstan.rules.rule]
        arguments:
            stringArgByMethodByType:
                SomeObject:
                    someMethod: [1]
```

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function run(SomeObject $someObject): void
    {
        $this->someObject->someMethod('yes', Another::class);
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function run(SomeObject $someObject): void
    {
        $this->someObject->someMethod('yes', 'Another');
    }
}
```

:+1:

<br>

## Require $this on Parent Method Call

- class: [`RequireThisOnParentMethodCallRule`](../src/Rules/RequireThisOnParentMethodCallRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\RequireThisOnParentMethodCallRule
        tags: [phpstan.rules.rule]
```

```php
class ParentClass
{
    protected function foo()
    {
        echo 'foo';
    }

    protected function bar()
    {
        echo 'bar';
    }
}

class CallParentMethodStatically extends ParentClass
{
    public function run()
    {
        parent::foo();
        parent::bar();
    }
}
```

:x:

<br>

## Use Value Objects over Array in Complex PHP Configs

- class: [`ForbiddenComplexArrayConfigInSetRule`](../src/Rules/ForbiddenComplexArrayConfigInSetRule.php)

```php
<?php


declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(NormalToFluentRector::class)
        ->call('configure', [[
            'options' => ['Cake\Network\Response', ['withLocation', 'withHeader']],
        ]]);
};
```

:x:

```php
<?php


declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(NormalToFluentRector::class)
        ->call('configure', [[
            'options' => inline_value_objects([
                new SomeValueObject('Cake\Network\Response', ['withLocation', 'withHeader']),
            ]),
        ]]);
};
```

:+1:

<br>

## Use specific Repository over EntityManager in Controller

- class: [`NoEntityManagerInControllerRule`](../src/Rules/NoEntityManagerInControllerRule.php)

```php
<?php


declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;

final class SomeController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        // ...
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

final class SomeController
{
    public function __construct(SomeEntityRepository $someEntityRepository)
    {
        // ...
    }
}
```

:+1:

<br>

## No factory method call in constructor

- class: [`NoFactoryInConstructorRule`](../src/Rules/NoFactoryInConstructorRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoFactoryInConstructorRule
        tags: [phpstan.rules.rule]
```

```php
final class WithConstructorWithFactory
{
    public function __construct(Factory $factory)
    {
        $factory->build();
    }
}
```

:x:

<br>

## `getRepository()` is allowed only in Repository constructor

- class: [`NoGetRepositoryOutsideConstructorRule`](../src/Rules/NoGetRepositoryOutsideConstructorRule.php)

```php
<?php


declare(strict_types=1);

use Doctrine\ORM\EntityManager;

final class SomeController
{
    public function someAction(EntityManager $entityManager): void
    {
        $someEntityRepository = $entityManager->getRepository(SomeEntity::class);
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class SomeRepository
{
    /**
     * @var EntityRepository<SomeEntity>
     */
    public $someEntityRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->someEntityRepository = $entityManager->getRepository(SomeEntity::class);
    }
}
```

:+1:

<br>

## No Parameter can be Nullable

Inspired by [Null Hell](https://afilina.com/null-hell) by @afilina

- class: [`NoNullableParameterRule`](../src/Rules/NoNullableParameterRule.php)

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function run(?string $vaulue = true): void
    {
    }
}
```

:x:

<br>

## No Parent Method Call on Empty Statement in Parent Method

- class: [`NoParentMethodCallOnEmptyStatementInParentMethodRule`](../src/Rules/NoParentMethodCallOnEmptyStatementInParentMethodRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoParentMethodCallOnEmptyStatementInParentMethodRule
        tags: [phpstan.rules.rule]
```

```php
abstract class ParentClass
{
    protected function setUp()
    {
        // empty statement
    }
}

final class SomeClass extends ParentClass
{
    protected function setUp()
    {
        parent::setUp();

        echo 'some code';
    }
}
```

:x:

<br>

## No parent method call on No override process

- class: [`NoParentMethodCallOnNoOverrideProcessRule`](../src/Rules/NoParentMethodCallOnNoOverrideProcessRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoParentMethodCallOnNoOverrideProcessRule
        tags: [phpstan.rules.rule]
```

```php
final class SomeTest exteds TestCase
{
     protected function setUp()
     {
          parent::setUp();
     }
}
```

:x:

<br>

## No Parameter can Have Default Value

- class: [`NoDefaultParameterValueRule`](../src/Rules/NoDefaultParameterValueRule.php)

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function run($vaulue = true): void
    {
    }
}
```

:x:

<br>

## Class should have suffix by parent class/interface

Covers `Interface` suffix as well, e.g `EventSubscriber` checks for `EventSubscriberInterface` as well.

- class: [`ClassNameRespectsParentSuffixRule`](../src/Rules/ClassNameRespectsParentSuffixRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ClassNameRespectsParentSuffixRule
        tags: [phpstan.rules.rule]
        arguments:
            parentClasses:
                - Rector
                - Rule
```

:x:

```php
<?php


declare(strict_types=1);

// should be "SomeCommand"
class Some extends Command
{
}
```

<br>

## Forbid unwanted Functions

- class: [`ForbiddenFuncCallRule`](../src/Rules/ForbiddenFuncCallRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenFuncCallRule
        tags: [phpstan.rules.rule]
        arguments:
            # default are: ['d', 'dd', 'dump', 'var_dump', 'extract']
            forbiddenFunctions: ['dump', 'echo', 'print', 'exec']
```

```php
<?php


declare(strict_types=1);

dump($value);
```

:x:

<br>

## Forbid Multiple Class Like (Interface, Class, Trait) in One File

- class: [`ForbiddenMultipleClassLikeInOneFileRule`](../src/Rules/ForbiddenMultipleClassLikeInOneFileRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenMultipleClassLikeInOneFileRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

interface Foo
{
}

class Bar implements Foo
{
}
```

:x:

<br>

## Forbid Nested Foreach with Empty Statement

- class: [`ForbiddenNestedForeachWithEmptyStatementRule`](../src/Rules/ForbiddenNestedForeachWithEmptyStatementRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenNestedForeachWithEmptyStatementRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

foreach ($errors as $fileErrors) {
    // empty
    foreach ($fileErrors as $fileError) {

    }
}
```

:x:

```php
<?php


declare(strict_types=1);

foreach ($errors as $fileErrors) {
    $errorCount = count($fileErrors);
    foreach ($fileErrors as $fileError) {

    }
}
```

:+1:

<br>

## Forbid New in Method Rule

- class: [`ForbiddenNewInMethodRule`](../src/Rules/ForbiddenNewInMethodRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenNewInMethodRule
        tags: [phpstan.rules.rule]
        arguments:
            forbiddenClassMethods:
                PHPStan\Rules\Rule: ['getRule']
```

```php
<?php


declare(strict_types=1);

namespace Some\Namespace;

use PHPStan\Rules\Rule;

class SomeClassRule implements Rule
{
    // ...

    protected function getRule(): Rule
    {
        return new SomeRule();
    }
}
```

:x:

<br>

## Forbid New Outside Factory Service

- class: [`ForbidNewOutsideFactoryServiceRule`](../src/Rules/ForbidNewOutsideFactoryServiceRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbidNewOutsideFactoryServiceRule
        tags: [phpstan.rules.rule]
        arguments:
            types:
                - *Search
                - Foo
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function create()
	{
		return new CarSearch();
	}
}
```

:x:

```php
<?php


declare(strict_types=1);

final class SearchFactory
{
    public function create()
	{
		return new CarSearch();
	}
}
```

:+1:

<br>

## Use explicit comparison over `empty()`

- class: [`NoEmptyRule`](../src/Rules/NoEmptyRule.php)

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run($value)
    {
        return empty($value);
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run(array $value)
    {
        return $value === [];
    }
}
```

:+1:

<br>

## Prevent Override of Parent Method Visbility

- class: [`PreventParentMethodVisibilityOverrideRule`](../src/Rules/PreventParentMethodVisibilityOverrideRule.php)

```php
<?php


declare(strict_types=1);

class ProtectedVisibility
{
    protected function run(): void
    {
    }
}

final class PublicOverride extends ProtectedVisibility
{
    public function run(): void
    {
    }
}
```

:x:

<br>

## No Function Call in Method Call

- class: [`NoFunctionCallInMethodCallRule`](../src/Rules/NoFunctionCallInMethodCallRule.php)

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run($value): void
    {
        $this->someMethod(strlen('fooo'));
    }

    private function someMethod($value)
    {
        return $value;
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run($value): void
    {
        $fooSize = strlen('fooo');
        $this->someMethod($fooSize);
    }

    private function someMethod($value)
    {
        return $value;
    }
}
```

:+1:

<br>

## No Array Access on Object

- class: [`NoArrayAccessOnObjectRule`](../src/Rules/NoArrayAccessOnObjectRule.php)

```php
<?php


declare(strict_types=1);

final class MagicArrayObject implements ArrayAccess
{
    public function offsetExists($offset): void
    {
        // ...
    }

    public function offsetGet($offset): void
    {
        // ...
    }

    public function offsetSet($offset, $value): void
    {
        // ...
    }

    public function offsetUnset($offset): void
    {
        // ...
    }
}
```

```php
<?php


declare(strict_types=1);

final class SomeClass
{
    public function run(MagicArrayObject $magicArrayObject)
    {
        return $magicArrayObject['more_magic'];
    }
}
```

:x:

<br>

## No `isset()` on objects

- class: [`NoIssetOnObjectRule`](../src/Rules/NoIssetOnObjectRule.php)

```php
<?php


declare(strict_types=1);

final class IssetOnObject
{
    public function run()
    {
        if (random_int(0, 1)) {
            $object = new SomeClass();
        }

        if (isset($object)) {
            return $object;
        }
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

final class IssetOnObject
{
    public function run()
    {
        $object = null;
        if (random_int(0, 1)) {
            $object = new SomeClass();
        }

        if ($object !== null) {
            return $object;
        }
    }
}
```

:+1:

<br>

## No `@method` Tag in Class Docblock

- class: [`NoMethodTagInClassDocblockRule`](../src/Rules/NoMethodTagInClassDocblockRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoMethodTagInClassDocblockRule
        tags: [phpstan.rules.rule]
```

```php
<?php


/**
 * @method getMagic() string
 */
class ClassWithMethodTag
{
}
```

:x:

<br>

## Use explicit Property Fetch Names over Dynamic

- class: [`NoDynamicPropertyFetchNameRule`](../src/Rules/NoDynamicPropertyFetchNameRule.php)

```php
<?php


declare(strict_types=1);

final class DynamicPropertyFetchName
{
    public function run($value): void
    {
        $this->{$value};
    }
}
```

:x:

<br>

## Use explicit Method Names over Dynamic

- class: [`NoDynamicMethodNameRule`](../src/Rules/NoDynamicMethodNameRule.php)

```php
<?php


declare(strict_types=1);

final class DynamicMethodCallName
{
    public function run($value): void
    {
        $this->{$value}();
    }
}
```

:x:

<br>

## Use explicit return values over magic "&$variable" reference

- class: [`NoReferenceRule`](../src/Rules/NoReferenceRule.php)

```php
<?php


declare(strict_types=1);

function someFunction(&$var): void
{
    $var + 1;
}
```

:x:

```php
<?php


declare(strict_types=1);

function someFunction($var)
{
    return $var + 1;
}
```

:+1:

<br>

## No scalar and array in constructor parameter

- class: [`NoScalarAndArrayConstructorParameterRule`](../src/Rules/NoScalarAndArrayConstructorParameterRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoScalarAndArrayConstructorParameterRule
        tags: [phpstan.rules.rule]
```

```php
final class SomeConstruct
{
    protected function __construct(string $string)
    {
    }
}
```

:x:

<br>

## No setter on a service

- class: [`NoSetterOnServiceRule`](../src/Rules/NoSetterOnServiceRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoSetterOnServiceRule
        tags: [phpstan.rules.rule]
```

```php
final class SomeServiceWithSetter
{
    private $x;

    public function setX(stdClass $x)
    {
        $this->x = $x;
    }
}
```

:x:

<br>

## Class "%s" inherits from forbidden parent class "%s". Use Composition over Inheritance instead

- class: [`ForbiddenParentClassRule`](../src/Rules/ForbiddenParentClassRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenParentClassRule
        tags: [phpstan.rules.rule]
        arguments:
            forbiddenParentClasses:
                - 'Doctrine\ORM\EntityRepository'
                # you can use fnmatch() pattern
                - '*\AbstractController'
```

```php
<?php


declare(strict_types=1);

use Doctrine\ORM\EntityRepository;

final class ProductRepository extends EntityRepository
{
}
```

:x:

```php
<?php


declare(strict_types=1);

use Doctrine\ORM\EntityRepository;

final class ProductRepository
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }
}
```

:+1:

<br>

## Forbid Protected Property Usage. Use Interface Instead.

- class: [`ForbiddenProtectedPropertyRule`](../src/Rules/ForbiddenProtectedPropertyRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenProtectedPropertyRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

abstract class SomeClass
{
    protected $config = [];
}
```

:x:

```php
<?php


declare(strict_types=1);

interface ConfigInterface
{
    public function getConfig():
}

abstract class SomeClass implements ConfigInterface
{
    public function getConfig()
    {
        // ...
    }

    public function run()
    {
        $this->getConfig();
    }
}
```

:+1:

<br>

## Forbid Method or Function Call Inside Foreach's Expression. Use Variable instead.

- class: [`ForbidMethodOrFuncCallInForeachRule`](../src/Rules/ForbidMethodOrFuncCallInForeachRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbidMethodOrFuncCallInForeachRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

function getData()
{
    return [];
}

foreach (getData() as $key => $item) {

}
```

:x:

```php
<?php


declare(strict_types=1);

function getData()
{
    return [];
}

$data = getData();
foreach ($data as $key => $item) {

}
```

:+1:

<br>

## Assignment inside if statement is forbidden. Use before if instead

- class: [`ForbiddenAssignInifRule`](../src/Rules/ForbiddenAssignInifRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\ForbiddenAssignInifRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

function data()
{
    return rand(1, 2);
}

if (($a = data()) === 1) {

}
```

:x:

```php
<?php


declare(strict_types=1);

function data()
{
    return rand(1, 2);
}

$a = data();
if ($a === 1) {

}
```

:+1:

<br>

## Use custom exceptions instead of Native Ones

- class: [`NoDefaultExceptionRule`](../src/Rules/NoDefaultExceptionRule.php)

```php
<?php


declare(strict_types=1);

throw new RuntimeException('...');
```

:x:

```php
<?php


declare(strict_types=1);

use App\Exception\FileNotFoundExceptoin;

throw new FileNotFoundExceptoin('...');
```

:+1:

<br>

## Forbidden return of `require_once()`/`incude_once()`

- class: [`ForbidReturnValueOfIncludeOnceRule`](../src/Rules/ForbidReturnValueOfIncludeOnceRule.php)

```php
<?php

// ecs.php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Rules\ForbidReturnValueOfIncludeOnceRule;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ForbidReturnValueOfIncludeOnceRule::class);
};
```

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function run()
    {
        return require_once 'Test.php';
    }
}
```

:x:

<br>

## Boolish Methods has to have is/has/was Name

- class: [`BoolishClassMethodPrefixRule`](../src/Rules/BoolishClassMethodPrefixRule.php)

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function old(): bool
    {
        return $this->age > 100;
    }
}
```

:x:

<br>

## Check Not Tests Namespace Outside Tests Directory

- class: [`CheckNotTestsNamespaceOutsideTestsDirectoryRule`](../src/Rules/CheckNotTestsNamespaceOutsideTestsDirectoryRule.php)

```php
<?php


declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Rules\CheckNotTestsNamespaceOutsideTestsDirectoryRule\Fixture\Tests;

class TestsNamespaceInsideTestsDirectoryClass
{

}
```

:x:

<br>

## Check Required `abstract` Keyword for Class Name Start with Abstract

- class: [`CheckRequiredAbstractKeywordForClassNameStartWithAbstractRule`](../src/Rules/CheckRequiredAbstractKeywordForClassNameStartWithAbstractRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\CheckRequiredAbstractKeywordForClassNameStartWithAbstractRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Rules\CheckRequiredAbstractKeywordForClassNameStartWithAbstract\Fixture\Tests;

class AbstractFoo
{

}
```

:x:

<br>

## Check Method with @required need to be named autowire+class name

- class: [`CheckRequiredMethodTobeAutowireWithClassNameRule`](../src/Rules/CheckRequiredMethodTobeAutowireWithClassNameRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\CheckRequiredMethodTobeAutowireWithClassNameRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Rules\CheckRequiredMethodTobeAutowireWithClassName\Fixture\Tests;

final class WithRequiredAutowire
{
    /**
     * @required
     */
    public function autowireWithRequiredAutowire()
    {
    }
}
```

:x:

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function isOld(): bool
    {
        return $this->age > 100;
    }
}
```

:+1:

<br>

## Check Unneeded SymfonyStyle usage for only newline, write, and/or writeln

- class: [`CheckUnneededSymfonyStyleUsageRule`](../src/Rules/CheckUnneededSymfonyStyleUsageRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\CheckUnneededSymfonyStyleUsageRule
        tags: [phpstan.rules.rule]
```

```php
<?php


declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Rules\CheckRequireMethodTobeAutowireWithClassName\Fixture;

use Symfony\Component\Console\Style\SymfonyStyle;

class UseMethodCallFromSymfonyStyle
{
    private $symfonyStyle;

    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    public function run()
    {
        $this->symfonyStyle->newline();
    }
}
```

:x:

<br>

## Check Used Namespaced Name on Class_ node

- class: [`CheckUsedNamespacedNameOnClassNodeRule`](../src/Rules/CheckUsedNamespacedNameOnClassNodeRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\CheckUsedNamespacedNameOnClassNodeRule
        tags: [phpstan.rules.rule]
        arguments:
            excludedClasses:
                - 'Symplify\CodingStandard\Tests\Rules\CheckUsedNamespacedNameOnClassNodeRule\Fixture\SkippedClass'
```

```php
<?php

declare(strict_types=1);

use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;

final class UsedNameOfClass
{
    public function process(Class_ $class, Scope $scope): array
    {
        $class->name;
        return [];
    }
}
```

:x:

<br>

## Constant type Must Match its Value

- class: [`MatchingTypeConstantRule`](../src/Rules/MatchingTypeConstantRule.php)

```php
<?php


declare(strict_types=1);

class SomeClass
{
    /**
     * @var int
     */
    private const LIMIT = 'max';
}
```

:x:


```php
<?php


declare(strict_types=1);

class SomeClass
{
    /**
     * @var string
     */
    private const LIMIT = 'max';
}
```

:+1:

<br>

## Defined Method Argument should be Always Constant Value

- class: [`RequireMethodCallArgumentConstantRule`](../src/Rules/RequireMethodCallArgumentConstantRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\RequireMethodCallArgumentConstantRule
        tags: [phpstan.rules.rule]
        arguments:
            constantArgByMethodByType:
                AlwaysCallMeWithConstant:
                    some_type: [0] # positions
```

```php
<?php


declare(strict_types=1);

class SomeClass
{
    public function run(): void
    {
        $alwaysCallMeWithConstant = new AlwaysCallMeWithConstant();
        $alwaysCallMeWithConstant->call('someValue');
        // should be: $alwaysCallMeWithConstant->call(TypeList::SOME);
    }
}
```

:x:

<br>

## Require @see annotation to class Test case by Type

- class: [`SeeAnnotationToTestRule`](../src/Rules/SeeAnnotationToTestRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\SeeAnnotationToTestRule
        tags: [phpstan.rules.rule]
        arguments:
            requiredSeeTypes:
                - PHPStan\Rules\Rule
                - PHP_CodeSniffer\Sniffs\Sniff
                - PHP_CodeSniffer\Fixer
```

```php
<?php


declare(strict_types=1);

use PHPStan\Rules\Rule;

class SomeRule implements Rule
{
    // ...
}
```

:x:

```php
<?php


declare(strict_types=1);

use PHPStan\Rules\Rule;

/**
 * @see SomeRuleTest
 */
class SomeRule implements Rule
{
    // ...
}
```

:+1:

<br>

## Prefer Another Class

- class: [`PreferredClassRule`](../src/Rules/PreferredClassRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\PreferredClassRule
        tags: [phpstan.rules.rule]
        arguments:
            oldToPrefferedClasses:
                SplFileInfo: 'Symplify\SmartFileSystem\SmartFileInfo'
                DateTime: 'Nette\Utils\DateTime'
```

```php
<?php

declare(strict_types=1);

$dateTime = new DateTime('now');
```

:x:

```php
<?php

declare(strict_types=1);

$dateTime = new Nette\Utils\DateTime('now');
```

:+1:

<br>

## Prefer raw data in test\'s dataProvider method instead from setUp()

- class: [`PreferredRawDataInTestDataProviderRule`](../src/Rules/PreferredRawDataInTestDataProviderRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\PreferredRawDataInTestDataProviderRule
        tags: [phpstan.rules.rule]
```

```php
<?php

final class UseDataFromSetupInTestDataProviderTest extends TestCase
{
    private $data;

    protected function setUp()
    {
        $this->data = true;
    }

    public function provideFoo()
    {
        yield [$this->data];
    }

    /**
     * @dataProvider provideFoo
     */
    public function testFoo($value)
    {
        $this->assertTrue($value);
    }
}
```

:x:

```php
<?php

use stdClass;

final class UseRawDataForTestDataProviderTest
{
    private $obj;

    protected function setUp()
    {
        $this->obj = new stdClass;
    }

    public function provideFoo()
    {
        yield [true];
    }

    /**
     * @dataProvider provideFoo
     */
    public function testFoo($value)
    {
        $this->obj->x = $value;
        $this->assertTrue($this->obj->x);
    }
}
```

:+1:

<br>

## Prefer Class constant over Variable constant

- class: [`PreferredClassConstantOverVariableConstantRule`](../src/Rules/PreferredClassConstantOverVariableConstantRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\PreferredClassConstantOverVariableConstantRule
        tags: [phpstan.rules.rule]
```

```php
<?php

$album::PER_PAGE;
```

:x:

```php
<?php

Album::PER_PAGE;
```

:+1:

<br>

## Classes with Static Methods must have "Static" in the Name

- class: [`NoClassWithStaticMethodWithoutStaticNameRule`](../src/Rules/NoClassWithStaticMethodWithoutStaticNameRule.php)

Be honest about static. [Why is static bad?](https://tomasvotruba.com/blog/2019/04/01/removing-static-there-and-back-again/)

Value object static constructors, EventSubscriber and Command classe are excluded.

```php
<?php

declare(strict_types=1);

class FormatConverter
{
    public static function yamlToJson(array $yaml): array
    {
        // ...
    }
}
```

:x:

```php
<?php

declare(strict_types=1);

class StaticFormatConverter
{
    public static function yamlToJson(array $yaml): array
    {
        // ...
    }
}
```

:+1:

<br>

## No constructor in Test

- class: [`NoConstructorInTestRule`](../src/Rules/NoConstructorInTestRule.php)

```yaml
# phpstan.neon
services:
    -
        class: Symplify\CodingStandard\Rules\NoConstructorInTestRule
        tags: [phpstan.rules.rule]
```

```php
final class SomeTest
{
    public function __construct()
    {
    }
}
```

:x:

<br>

## Use Unique Class Short Names

- class: [`NoDuplicatedShortClassNameRule`](../src/Rules/NoDuplicatedShortClassNameRule.php)

```php
<?php


declare(strict_types=1);

namespace App;

// Same as "Nette\Utils\Finder" or "Symfony\Component\Finder\Finder"
class Finder
{
}
```

:x:

```php
<?php


declare(strict_types=1);

namespace App\Entity;

class EntityFinder
{
}
```

:+1:
