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

use type HH\Lib\IO\CloseableWriteHandle;
use type Nazg\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
use type Facebook\Experimental\Http\Message\{
  ResponseInterface,
  ServerRequestInterface,
};

<<__ConsistentConstruct>>
class Heredity implements RequestHandlerInterface {

  public function __construct(
    protected MiddlewareStack $stack,
    protected ?RequestHandlerInterface $handler = null
  ) {
    $stack->reverse();
  }

  public function handle(
    CloseableWriteHandle $writeHandle,
    ServerRequestInterface $request
  ): ResponseInterface {
    if ($this->stack->isEmpty()) {
      if ($this->handler is RequestHandlerInterface) {
        return $this->handler->handle($writeHandle, $request);
      }
      throw new Exception\MiddlewareNotFoundException('Middleware Class Not Found.');
    }
    return $this->processor($writeHandle, $this->stack->shift(), $request);
  }

  protected function processor(
    CloseableWriteHandle $writeHandle,
    MiddlewareInterface $middleware,
    ServerRequestInterface $request
  )[defaults]: ResponseInterface {
    return $middleware->process($writeHandle, $request, $this);
  }
}
