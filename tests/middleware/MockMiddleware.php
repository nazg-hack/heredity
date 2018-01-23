<?hh // strict

namespace NazgHeredityTest\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MockMiddleware implements MiddlewareInterface {

  const string MOCK_HEADER = 'x-testing-call-count';

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $request = $request->withAddedHeader(self::MOCK_HEADER, 1);
    return $handler->handle($request);
  }
}
