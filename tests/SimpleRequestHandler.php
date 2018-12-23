<?hh // strict

use type Ytake\HackHttpServer\RequestHandlerInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Ytake\Hungrr\Response;
use type Ytake\Hungrr\StatusCode;
use type NazgHeredityTest\Middleware\MockMiddleware;
use namespace HH\Lib\Experimental\IO;
use function json_encode;

final class SimpleRequestHandler implements RequestHandlerInterface {
  
  public function __construct(
    private IO\WriteHandle $handle
  ) {}

  public function handle(ServerRequestInterface $request): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if (count($header)) {
      $this->handle->rawWriteBlocking(json_encode($header));
      return new Response($this->handle, StatusCode::OK);
    }
    $this->handle->rawWriteBlocking(json_encode([]));
    return new Response($this->handle, StatusCode::OK);
  }
}
