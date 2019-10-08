# heredity

Middleware Dispatcher For Hack.  

[![Build Status](https://travis-ci.org/nazg-hack/heredity.svg?branch=master)](https://travis-ci.org/nazg-hack/heredity)

PSR-7 HTTP message library Not Supported.  
Supported Only Hack library.  
*Required HHVM >= 4.20.0*

- [ytake/hungrr](https://github.com/ytake/hungrr)
- [usox/hackttp](https://github.com/usox/hackttp)

## install

```bash
$ composer require nazg/heredity
```

## Usage

### Basic

#### 1. Example Simple Request Handler

```hack
use type Nazg\Http\Server\RequestHandlerInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Ytake\Hungrr\Response;
use type Ytake\Hungrr\StatusCode;
use type NazgHeredityTest\Middleware\MockMiddleware;
use namespace HH\Lib\Experimental\IO;
use function json_encode;

final class SimpleRequestHandler implements RequestHandlerInterface {

  public function handle(
    IO\WriteHandle $handle,
    ServerRequestInterface $request
  ): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if (count($header)) {
      $handle->rawWriteBlocking(json_encode($header));
      return new Response($handle, StatusCode::OK);
    }
    $handle->rawWriteBlocking(json_encode([]));
    return new Response($handle, StatusCode::OK);
  }
}
```

#### 2. Creating Middleware

```hack
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Nazg\Http\Server\MiddlewareInterface;
use type Nazg\Http\Server\RequestHandlerInterface;
use type HH\Lib\Experimental\IO\WriteHandle;

class SimpleMiddleware implements MiddlewareInterface {

  public function process(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    // ... do something and return response
    return $handler->handle($writeHandle, $request);
  }
}

```

#### 3. Middleware

```hack
use type Nazg\Heredity\Heredity;
use type Nazg\Heredity\MiddlewareStack;
use type Ytake\Hungrr\ServerRequestFactory;
use namespace HH\Lib\Experimental\IO;

list($read, $write) = IO\pipe_non_disposable();
$heredity = new Heredity(
    new MiddlewareStack([
      SimpleMiddleware::class
    ]),
    new SimpleRequestHandler()
  );
$response = $heredity->handle($write, ServerRequestFactory::fromGlobals());

```

### With Dependency Injection Container

example. [nazg-hack/glue](https://github.com/nazg-hack/glue)

```hack

use namespace HH\Lib\Str;
use type Nazg\Http\Server\AsyncMiddlewareInterface;
use type Nazg\Heredity\Exception\MiddlewareResolvingException;
use type Nazg\Heredity\Resolvable;
use type Nazg\Glue\Container;

class GlueResolver implements Resolvable<AsyncMiddlewareInterface> {

  public function __construct(
    protected Container $container
  ) {}

  public function resolve(
    classname<AsyncMiddlewareInterface> $middleware
  ): AsyncMiddlewareInterface {
    if ($this->container->has($middleware)) {
      return $this->container->get($middleware);
    }
    throw new MiddlewareResolvingException(
      Str\format('Identifier "%s" is not binding.', $middleware),
    );
  }
}
```
