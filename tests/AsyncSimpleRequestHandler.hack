use type Nazg\Http\Server\AsyncRequestHandlerInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Ytake\Hungrr\Response;
use type Ytake\Hungrr\StatusCode;
use type NazgHeredityTest\Middleware\AsyncMockMiddleware;
use namespace HH\Lib\Experimental\IO;
use function json_encode;

final class AsyncSimpleRequestHandler implements AsyncRequestHandlerInterface {

  public async function handleAsync(
    IO\WriteHandle $handle,
    ServerRequestInterface $request
  ): Awaitable<ResponseInterface> {
    $header = $request->getHeader(AsyncMockMiddleware::MOCK_HEADER);
    if (count($header)) {
      await $handle->writeAsync(json_encode($header));
      await $handle->closeAsync();
      return new Response($handle, StatusCode::OK);
    }
    await $handle->writeAsync(json_encode([]));
    await $handle->closeAsync();
    return new Response($handle, StatusCode::OK);
  }
}
