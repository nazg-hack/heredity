<?hh

use type Ytake\HackHttpServer\RequestHandlerInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Zend\Diactoros\Response\JsonResponse;
use type NazgHeredityTest\Middleware\MockMiddleware;

final class SimpleRequestHandler implements RequestHandlerInterface {

  public function handle(ServerRequestInterface $request): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if (count($header)) {
      return new JsonResponse($header);
    }
    return new JsonResponse([]);
  }
}
