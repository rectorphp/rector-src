# How to Apply Major Change with BC-Break Allowed

Rector always try to make change to your code with preserve BC break as possible, for example, with `TypedPropertyRector`, you can always only change:

1. private property
2. protected property on final class without extends

For example:

```diff
class SomeClass
{
    /** @var bool */
    public $a;

    /** @var bool */
    protected $b;

-    /** @var bool */
-    private $c;
+    private bool $c;
```

What if you want to change all of them for `Major` change use case, eg, upgrade from version 1 to version 2 of your application? There is `Option::ALLOW_BC_BREAK` option for that, it can be run via `--allow-bc-break` in command:

```bash
vendor/bin/rector --allow-bc-break
```

so the change will be:

```diff
class SomeClass
{
-    /** @var bool */
-    private $a;
+    private bool $a;

-    /** @var bool */
-    private $b;
+    private bool $b;

-    /** @var bool */
-    private $c;
+    private bool $c;
```
