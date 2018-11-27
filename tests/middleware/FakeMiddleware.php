<?hh // strict

namespace NazgHeredityTest\Middleware;

use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Ytake\HackHttpServer\MiddlewareInterface;
use type Ytake\HackHttpServer\RequestHandlerInterface;

final class FakeMiddleware implements MiddlewareInterface {

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    return $handler->handle($request);
  }
}
