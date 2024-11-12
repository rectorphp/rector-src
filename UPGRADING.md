# Upgrading from Rector 1.x to 2.0

## Upgrade for custom Rules writers

### 1. `AbstractScopeAwareRector` is removed, use `AbstractRector` instead

The `Rector\Rector\AbstractScopeAwareRector` was too granular to fetch single helper object. It made creating new custom rules ambiguous, one layer more complex and confusing. This class has been removed in favor of standard `AbstractRector`. The `Scope` object can be fetched via `ScopeFetcher`.

**Before**

```php
use Rector\Rector\AbstractScopeAwareRector;

final class SimpleRector extends AbstractScopeAwareRector
{
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        // ...
    }
}
```

**After**

```php
use Rector\Rector\AbstractRector;
use Rector\PHPStan\ScopeFetcher;

final class SimpleRector extends AbstractRector
{
    public function refactor(Node $node): ?Node
    {
        if (...) {
            // this allow to fetch scope only when needed
            $scope = ScopeFetcher::fetch($node);
        }

        // ...
    }
}
```
