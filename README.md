# heredity
Middleware Dispatcher For Hack

[![Build Status](https://travis-ci.org/nazg-hack/heredity.svg?branch=master)](https://travis-ci.org/nazg-hack/heredity)

## install

```bash
$ hhvm $(which composer) require nazg/heredity
```

## Usage

### Basic
#### 1. Example Simple Request Handler

```hack
<?hh // strict

use type Ytake\HackHttpServer\RequestHandlerInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Ytake\Hungrr\Response;
use type Ytake\Hungrr\StatusCode;
use type NazgHeredityTest\Middleware\MockMiddleware;

use function json_encode;

final class SimpleRequestHandler implements RequestHandlerInterface {
  public function handle(ServerRequestInterface $request): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if (count($header)) {
      return new Response(StatusCode::OK, dict[], json_encode($header));
    }
    return new Response(StatusCode::OK, dict[], json_encode([]));
  }
}

```

#### 2. Creating Middleware

```hack
<?hh // strict

use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Ytake\HackHttpServer\MiddlewareInterface;
use type Ytake\HackHttpServer\RequestHandlerInterface;

class SimpleMiddleware implements MiddlewareInterface {

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    // ... do something and return response
    // or call request handler:
    // return $handler->handle($request);
  }
}

```

#### 3. Middleware

```hack
use Nazg\Heredity\Heredity;
use Nazg\Heredity\MiddlewareStack;
use Zend\Diactoros\ServerRequestFactory;

$heredity = new Heredity(
    new MiddlewareStack([
      SimpleMiddleware::class
    ]),
    new SimpleRequestHandler()
  );
$response = $heredity->process(ServerRequestFactory::fromGlobals());

```

### Use PSR-11 Containers

example. [ytake/hh-container](https://github.com/ytake/hh-container)

```hack
use Nazg\Heredity\Heredity;
use Nazg\Heredity\MiddlewareStack;
use Nazg\Heredity\PsrContainerResolver;
use Ytake\HHContainer\FactoryContainer;
use Zend\Diactoros\ServerRequestFactory;

$container = new FactoryContainer();
$container->set(SimpleMiddleware::class, $container ==> new SimpleMiddleware());

$heredity = new Heredity(
  new MiddlewareStack(
    [SimpleMiddleware::class],
    new PsrContainerResolver($container)
  ),
  new SimpleRequestHandler()
);
$response = $heredity->process(ServerRequestFactory::fromGlobals());

```
