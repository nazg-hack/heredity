/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2017-2019 Yuuki Takezawa
 *
 */
namespace Nazg\Heredity;

use type HH\Lib\Experimental\IO\CloseableWriteHandle;
use type Nazg\Http\Server\{AsyncMiddlewareInterface, AsyncRequestHandlerInterface};
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};

<<__ConsistentConstruct>>
class AsyncHeredity implements AsyncRequestHandlerInterface {

  public function __construct(
    protected AsyncMiddlewareStack $stack,
    protected ?AsyncRequestHandlerInterface $handler = null
  ) {
    $stack->reverse();
  }

  public async function handleAsync(
    CloseableWriteHandle $writeHandle,
    ServerRequestInterface $request
  ): Awaitable<ResponseInterface> {
    if ($this->stack->isEmpty()) {
      if ($this->handler is AsyncRequestHandlerInterface) {
        return await $this->handler->handleAsync($writeHandle, $request);
      }
      throw new Exception\MiddlewareNotFoundException('Middleware Class Not Found.');
    }
    return await $this->processorAsync($writeHandle, $this->stack->shift(), $request);
  }

  protected async function processorAsync(
    CloseableWriteHandle $writeHandle,
    AsyncMiddlewareInterface $middleware,
    ServerRequestInterface $request
  ): Awaitable<ResponseInterface> {
    return await $middleware->processAsync($writeHandle, $request, $this);
  }
}
