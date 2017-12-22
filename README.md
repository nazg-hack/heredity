# heredity
Middleware Dispatcher For Hack

[![Build Status](https://travis-ci.org/ytake/heredity.svg?branch=master)](https://travis-ci.org/ytake/heredity)

## install

```bash
$ hhvm -c php7.ini $(which composer) require ytake/heredity
```

### example php7.ini

```
[hhvm]
hhvm.jit=false
hhvm.php7.all=1
hhvm.hack.lang.auto_typecheck=0
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
use Ytake\Heredity\Heredity;
use Ytake\Heredity\MiddlewareStack;
use Zend\Diactoros\ServerRequestFactory;

$heredity = new Heredity(
    new MiddlewareStack([
      SimpleMiddleware::class
    ])
  );
$response = $heredity->process(ServerRequestFactory::fromGlobals(), new SimpleRequestHandler());

```

### Use PSR-11 Containers

example. [ytake/hh-container](https://github.com/ytake/hh-container)

```hack
use Ytake\Heredity\Heredity;
use Ytake\Heredity\MiddlewareStack;
use Ytake\Heredity\PsrContainerResolver;
use Ytake\HHContainer\FactoryContainer;
use Zend\Diactoros\ServerRequestFactory;

$container = new FactoryContainer();
$container->set(SimpleMiddleware::class, $container ==> new SimpleMiddleware());

$heredity = new Heredity(
  new MiddlewareStack(
    [SimpleMiddleware::class],
    new PsrContainerResolver($container)
  );
);
$response = $heredity->process(ServerRequestFactory::fromGlobals(), new SimpleRequestHandler());

```
