<?hh // strict

namespace NazgHeredityTest\Middleware;

use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Nazg\Http\Server\MiddlewareInterface;
use type Nazg\Http\Server\RequestHandlerInterface;
use type HH\Lib\Experimental\IO\WriteHandle;

final class MockMiddleware implements MiddlewareInterface {

  const string MOCK_HEADER = 'x-testing-call-count';

  public function process(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $request = $request->withAddedHeader(self::MOCK_HEADER, vec["1"]);
    return $handler->handle($writeHandle, $request);
  }
}
