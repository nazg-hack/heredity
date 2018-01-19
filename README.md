# heredity
Middleware Dispatcher For Hack

[![Build Status](https://travis-ci.org/nazg-hack/heredity.svg?branch=master)](https://travis-ci.org/nazg-hack/heredity)

## install

```bash
$ hhvm -d xdebug.enable=0 -d hhvm.jit=0 -d hhvm.php7.all=1 -d hhvm.hack.lang.auto_typecheck=0 \
 $(which composer) require nazg/heredity
```

## Usage

### Basic
#### 1. Example Simple Request Handler
```hack
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class SimpleRequestHandler implements RequestHandlerInterface {

  public function handle(ServerRequestInterface $request): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if(count($header)) {
      return new JsonResponse($header);
    }
    return new JsonResponse([]);
  }
}

```

#### 2. Creating Middleware

```hack

use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\Server\RequestHandlerInterface;

class SimpleMiddleware implements MiddlewareInterface {

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
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
