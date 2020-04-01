namespace NazgHeredityTest\Middleware;

use type HH\Lib\IO\CloseableWriteHandle;
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};
use type Nazg\Http\Server\{AsyncMiddlewareInterface, AsyncRequestHandlerInterface};

final class AsyncFakeMiddleware implements AsyncMiddlewareInterface {

  public async function processAsync(
    CloseableWriteHandle $writeHandle,
    ServerRequestInterface $request,
    AsyncRequestHandlerInterface $handler,
  ): Awaitable<ResponseInterface> {
    return await $handler->handleAsync($writeHandle, $request);
  }
}
