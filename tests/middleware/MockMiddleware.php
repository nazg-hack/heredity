<?hh // strict

use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\Server\RequestHandlerInterface;

class MockMiddleware implements MiddlewareInterface {

  const string MOCK_HEADER = 'x-testing-call-count';

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $request = $request->withAddedHeader(self::MOCK_HEADER, 1);
    return $handler->handle($request);
  }
}
