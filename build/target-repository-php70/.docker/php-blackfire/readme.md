## Docker image for Blackfire profiling

### Build

Builds image with `rector-blackfire` tag.

```
docker build . --tag rector-blackfire --file .docker/php-blackfire/Dockerfile
```

You can use `--build-arg PHP_VERSION=7.4` to build with specific PHP version. Supported versions are: 7.3, 7.4, 8.0


### Prepare

These variables must be set on host to pass them into container (obtain values at [blackfire.io](https://blackfire.io)):
```
export BLACKFIRE_CLIENT_ID=""
export BLACKFIRE_CLIENT_TOKEN=""
```


### Usage

Get into container:

```
docker run --entrypoint="" -it --rm -e BLACKFIRE_CLIENT_ID -e BLACKFIRE_CLIENT_TOKEN -v $(pwd):/rector rector-blackfire bash
```

Once in container, you can start profiling:
```
blackfire run php bin/rector <args..>
```
