namespace NazgHeredityTest\Middleware;

use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};
use type Nazg\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use type HH\Lib\IO\CloseableWriteHandle;

final class MockMiddleware implements MiddlewareInterface {

  const string MOCK_HEADER = 'x-testing-call-count';

  public function process(
    CloseableWriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $request = $request->withAddedHeader(self::MOCK_HEADER, vec['1']);
    return $handler->handle($writeHandle, $request);
  }
}
