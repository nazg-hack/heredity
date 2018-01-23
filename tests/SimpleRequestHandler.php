<?hh

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use NazgHeredityTest\Middleware\MockMiddleware;

final class SimpleRequestHandler implements RequestHandlerInterface {

  public function handle(ServerRequestInterface $request): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if (count($header)) {
      return new JsonResponse($header);
    }
    return new JsonResponse([]);
  }
}
