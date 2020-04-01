use type Nazg\Http\Server\RequestHandlerInterface;
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};
use type Ytake\Hungrr\{Response, StatusCode};
use type NazgHeredityTest\Middleware\MockMiddleware;
use namespace HH\Lib\IO;
use function json_encode;

final class SimpleRequestHandler implements RequestHandlerInterface {

  public function handle(
    IO\WriteHandle $handle,
    ServerRequestInterface $request
  ): ResponseInterface {
    $header = $request->getHeader(MockMiddleware::MOCK_HEADER);
    if (count($header)) {
      $handle->rawWriteBlocking(json_encode($header));
      return new Response($handle, StatusCode::OK);
    }
    $handle->rawWriteBlocking(json_encode(dict[]));
    return new Response($handle, StatusCode::OK);
  }
}
