<?hh // strict

namespace NazgHeredityTest\Middleware;

use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Nazg\Http\Server\MiddlewareInterface;
use type Nazg\Http\Server\RequestHandlerInterface;
use type HH\Lib\Experimental\IO\WriteHandle;
use namespace Nazg\Heredity\Middleware;

final class AsyncStubMiddleware implements Middleware\AsyncMiddlewareInterface {

  const string MOCK_HEADER = 'x-testing-call-count';

  public function process(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $request = $request->withAddedHeader(self::MOCK_HEADER, vec["1"]);
    return $handler->handle($writeHandle, $request);
  }

  public async function processAsync(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): Awaitable<ResponseInterface> {
    $request = $request->withAddedHeader(self::MOCK_HEADER, vec["1"]);
    return $handler->handle($writeHandle, $request);
  }
}
