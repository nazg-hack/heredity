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

use type Nazg\Http\Server\MiddlewareInterface;
use namespace HH\Lib\{C, Vec};

class MiddlewareStack {

  public function __construct(
    protected vec<classname<MiddlewareInterface>> $queue,
    protected Resolvable<MiddlewareInterface> $resolver = new InstanceResolver(),
  ) {}

  public function isEmpty()[rx]: bool {
    return C\is_empty($this->queue);
  }

  public function reverse()[rx]: void {
    $this->queue = Vec\reverse($this->queue);
  }

  public function shift(): MiddlewareInterface {
    $index = C\last_keyx($this->queue);
    $middleware = $this->queue[$index];
    $this->queue = Vec\take($this->queue, $index);
    return $this->resolver->resolve(
      $middleware
    );
  }

  public function layer()[rx]: ImmVector<classname<MiddlewareInterface>> {
    return new ImmVector($this->queue);
  }

  public function cancel(int $index)[rx]: vec<classname<MiddlewareInterface>> {
    $this->queue = Vec\drop($this->queue, $index);
    return $this->queue;
  }
}
