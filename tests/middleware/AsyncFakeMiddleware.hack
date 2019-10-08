namespace NazgHeredityTest\Middleware;

use type HH\Lib\Experimental\IO\WriteHandle;
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};
use type Nazg\Http\Server\{AsyncMiddlewareInterface, AsyncRequestHandlerInterface};

final class AsyncFakeMiddleware implements AsyncMiddlewareInterface {

  public async function processAsync(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    AsyncRequestHandlerInterface $handler,
  ): Awaitable<ResponseInterface> {
    return await $handler->handleAsync($writeHandle, $request);
  }
}
