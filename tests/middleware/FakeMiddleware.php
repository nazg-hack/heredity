<?hh // strict

namespace NazgHeredityTest\Middleware;

use type Psr\Http\Message\ResponseInterface;
use type Psr\Http\Message\ServerRequestInterface;
use type Psr\Http\Server\MiddlewareInterface;
use type Psr\Http\Server\RequestHandlerInterface;

final class FakeMiddleware implements MiddlewareInterface {

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    return $handler->handle($request);
  }
}
