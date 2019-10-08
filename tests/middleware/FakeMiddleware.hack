namespace NazgHeredityTest\Middleware;

use type HH\Lib\Experimental\IO\WriteHandle;
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};
use type Nazg\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

final class FakeMiddleware implements MiddlewareInterface {

  public function process(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    return $handler->handle($writeHandle, $request);
  }
}
