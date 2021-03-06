# PHP CS Fixer Fixers

## Remove Extra Spaces around Property and Constants Modifiers

- class: [`ArrayOpenerNewlineFixer`](../src/Fixer/ArrayNotation/ArrayOpenerNewlineFixer.php)

```diff
-$items = [$item,
+$items = [
+    $item,
     $item2
 ];
```

<br>

## Add Doctrine Annotations

- class: [`DoctrineAnnotationNewlineInNestedAnnotationFixer`](../src/Fixer/Annotation/DoctrineAnnotationNewlineInNestedAnnotationFixer.php)

```diff
 /**
- * @ORM\Table(name="table_name", indexes={@ORM\Index(name="...", columns={"..."}), @ORM\Index(name="...", columns={"..."})})
+ * @ORM\Table(name="table_name", indexes={
+ *     @ORM\Index(name="...", columns={"..."}),
+ *     @ORM\Index(name="...", columns={"..."})
+ * })
  */
class SomeEntity
{
}
```

The left side indent is handled by teaming up with `DoctrineAnnotationIndentationFixer`.


## Strict Types Declaration has to be Followed by Empty Line

- class: [`BlankLineAfterStrictTypesFixer`](../src/Fixer/Strict/BlankLineAfterStrictTypesFixer.php)

```diff
 <?php

 declare(strict_types=1);
+
 namespace SomeNamespace;
```

<br>

## Parameters, Arguments and Array items should be on the same/standalone line to fit Line Length

- class: [`LineLengthFixer`](../src/Fixer/LineLength/LineLengthFixer.php)

```php
<?php

// ecs.php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(LineLengthFixer::class)
        ->call('configure', [[
            LineLengthFixer::LINE_LENGTH => 120,
            LineLengthFixer::BREAK_LONG_LINES => true,
            LineLengthFixer::INLINE_SHORT_LINES => true,
        ]]);
};
````

```diff
 class SomeClass
 {
-    public function someMethod(SuperLongArguments $superLongArguments, AnotherLongArguments $anotherLongArguments, $oneMore)
+    public function someMethod(
+        SuperLongArguments $superLongArguments,
+        AnotherLongArguments $anotherLongArguments,
+        $oneMore
+    )
     {
     }

-    public function someOtherMethod(
-        ShortArgument $shortArgument,
-        $oneMore
-    ) {
+    public function someOtherMethod(ShortArgument $shortArgument, $oneMore) {
     }
 }
```

<br>

## Block comment should not have 2 empty lines in a row

- class: [`RemoveSuperfluousDocBlockWhitespaceFixer`](../src/Fixer/Commenting/RemoveSuperfluousDocBlockWhitespaceFixer.php)

```diff
 /**
  * @param int $value
  *
- *
  * @return array
  */
 public function setCount($value)
 {
 }
```

<br>

## Indexed PHP arrays should have 1 item per line

- class: [`StandaloneLineInMultilineArrayFixer`](../src/Fixer/ArrayNotation/StandaloneLineInMultilineArrayFixer.php)

```diff
-$friends = [1 => 'Peter', 2 => 'Paul'];
+$friends = [
+    1 => 'Peter',
+    2 => 'Paul'
+];
```

<br>

## Make `@param`, `@return` and `@var` Format United

- class: [`ParamReturnAndVarTagMalformsFixer`](../src/Fixer/Commenting/ParamReturnAndVarTagMalformsFixer.php)

```diff
 <?php

 /**
- * @param $name string
+ * @param string $name
  *
- * @return int $value
+ * @return int
  */
 function someFunction($name)
 {
 }
```

```diff
 <?php

 class SomeClass
 {
     /**
-     * @var int $property
+     * @var int
      */
     private $property;
 }
```

```diff
-/* @var int $value */
+/** @var int $value */
 $value = 5;

-/** @var $value int */
+/** @var int $value */
 $value = 5;
```

<br>

## Remove Extra Spaces around Property and Constants Modifiers

- class: [`RemoveSpacingAroundModifierAndConstFixer`](packages/coding-standard/src/Fixer/Spacing/RemoveSpacingAroundModifierAndConstFixer.php)

```diff
 class SomeClass
 {
-    protected     static     $value;
+    protected static $value;
}
```
