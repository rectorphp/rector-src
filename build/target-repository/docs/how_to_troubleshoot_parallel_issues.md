# How to Troubleshoot Parallel Issues

You may end up getting some parallels errors and ask why you can identify if there is not a parallel issue. They may be displayed as:

```
$ ./vendor/bin/rector process
...
[ERROR] Could not process "/app/vendor/rector/rector/vendor/symplify/easy-parallel/src/ValueObject/ParallelProcess.php" file, due to:  "Child process timed out after 120 seconds". On line: 103

[ERROR] Could not process some files, due to: "Reached system errors count limit of 50, exiting...".
```

Whan that happens, a first good approach is to disable parallel processing. Some of syntax and/or fatal errors can be hidden in the output of parallel failures. You can do so by:

```php
$rectorConfig->disableParallel();
```

After that, if rector processing works fine, that is an indication that you might need to adjust your parallel process to some balanced load, depending on the resources you have to process rector.


[parallel() function has some defaults](https://github.com/rectorphp/rector-src/blob/main/packages/Config/RectorConfig.php#L53)
```php
public function parallel(int $seconds = 120, int $maxNumberOfProcess = 16, int $jobSize = 20) : void
```

You might find useful to keep it aligned with what you have at your disposal:

- timeout `$seconds` can be increased if you find your codebase has a jobSize with classes that require more time to process and then you need more time to process
- `$maxNumberOfProcess` may be decreased to not overload your system, and keeping some process free and avoid side-impacts of busy systems not being able to process files properly. Example: if you have four processes, you can use `$maxNumberOfProcess = 2` so you keep 2 processes free and limit the system load.
- `$jobSize` might be decreased if your files has too many lines of code, what would cause more time needed to process, what also will load the processes, etc

Happy coding!
