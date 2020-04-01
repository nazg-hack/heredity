namespace NazgHeredityTest\Middleware;

use type HH\Lib\IO\CloseableWriteHandle;
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};
use type Nazg\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

final class FakeMiddleware implements MiddlewareInterface {

  public function process(
    CloseableWriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    return $handler->handle($writeHandle, $request);
  }
}
