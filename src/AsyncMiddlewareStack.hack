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

use type Nazg\Http\Server\AsyncMiddlewareInterface;
use namespace HH\Lib\{Vec, C};

class AsyncMiddlewareStack {

  public function __construct(
    protected vec<classname<AsyncMiddlewareInterface>> $queue,
    protected Resolvable<AsyncMiddlewareInterface> $resolver = new AsyncInstanceResolver(),
  ) {}

  <<__Rx>>
  public function isEmpty(): bool {
    return C\is_empty($this->queue);
  }

  public function reverse(): void {
    $this->queue = Vec\reverse($this->queue);
  }

  public function shift(): AsyncMiddlewareInterface {
    $index = C\last_keyx($this->queue);
    $middleware = $this->queue[$index];
    $this->queue = Vec\take($this->queue, $index);
    return $this->resolver->resolve(
      $middleware
    );
  }

  <<__Rx>>
  public function layer(): ImmVector<classname<AsyncMiddlewareInterface>> {
    return new ImmVector($this->queue);
  }

  public function cancel(int $index): vec<classname<AsyncMiddlewareInterface>> {
    $this->queue = Vec\drop($this->queue, $index);
    return $this->queue;
  }
}
