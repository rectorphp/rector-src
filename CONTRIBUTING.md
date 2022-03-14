# How to Contribute

Contributions here are more than welcomed! You can contribute to [rector-src](https://github.com/rectorphp/rector-src) repository or one of [extension packages](https://github.com/rectorphp/).

## Preparing Local Environment

1. Fork the [rector/rector-src](https://github.com/rectorphp/rector-src) repository and clone it

```bash
git clone git@github.com:<your-name>/rector-src.git
cd rector-src
```

2. We use PHP 8.1 and composer

Verify your local environment and update dependencies:

```bash
composer check-platform-reqs
composer update
```

Then you can start working with the code :+1:

<br>

Do you want to **contribute a failing test**? [This tutorial will sow you how](https://github.com/rectorphp/rector/blob/main/docs/how_to_add_test_for_rector_rule.md)

### Docker runtime

*Note: using Docker for contributing is strongly discouraged, as it requires [extra knowledge of composer internals](https://github.com/composer/composer/issues/9368#issuecomment-718112361).*

Alternatively you can use Docker runtime. All you need to do is wrap every command with `docker-compose run php`, so commands will be executed inside Docker container.

For example, to download PHP dependencies:

```bash
docker-compose run php composer install
```

Now you can start using all scripts and work with the code.

#### Using XDebug in Docker runtime

XDebug is installed and enabled in Docker container, but by default it's in [`xdebug.mode=off`](https://xdebug.org/docs/all_settings#mode), adding 0 overhead. If you need to debug code, set `XDEBUG_MODE=debug` in `.env` file. You can tweak Docker config with these variables:

- `XDEBUG_IDEKEY`: this must be the same as in IDE's remote debug configuration
- `XDEBUG_CLIENT_HOST`: it's your host from container's perspective. By default, it's set to `host.docker.internal` which works for Docker Desktop on Mac. For Unix you can try with `172.16.0.1` or set `extra_hosts` in `docker-compose.override.yml` ([see here](https://github.com/docker/for-linux/issues/264#issuecomment-965465879)).

## Preparing Pull Request

There 3 steps will make your pull-request easy to merge:

- **1 feature per pull-request**
- **new features need tests**
- CI must pass... you can mimic it locally by running

    ```bash
    composer complete-check
    ```

- Do you need to fix coding standards?

    ```bash
    composer fix-cs
    ```

We would be happy to accept PRs that follow these guidelines.
