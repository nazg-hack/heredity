<?hh // strict

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
 * Copyright (c) 2017 Yuuki Takezawa
 *
 */
namespace Ytake\Heredity;

use Interop\Http\Server\RequestHandlerInterface;
use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Dispatcher implements RequestHandlerInterface {

  public function __construct(
    protected MiddlewareStack $stack,
    protected ?RequestHandlerInterface $handler = null,
  ) {}

  public function handle(ServerRequestInterface $request): ResponseInterface {
    if ($this->stack->isEmpty()) {
      if ($this->handler) {
        return $this->handler->handle($request);
      }
    }
    $middleware = $this->stack->shift();
    $response = $middleware->process($request, $this);

    return $response;
  }
}
