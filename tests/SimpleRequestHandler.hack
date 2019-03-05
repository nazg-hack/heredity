use type Nazg\Http\Server\RequestHandlerInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Ytake\Hungrr\Response;
use type Ytake\Hungrr\StatusCode;
use type NazgHeredityTest\Middleware\MockMiddleware;
use namespace HH\Lib\Experimental\IO;
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
    $handle->rawWriteBlocking(json_encode([]));
    return new Response($handle, StatusCode::OK);
  }
}
