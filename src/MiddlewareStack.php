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
 * Copyright (c) 2017-2018 Yuuki Takezawa
 *
 */
namespace Nazg\Heredity;

use type Psr\Http\Server\MiddlewareInterface;
use function is_null;

class MiddlewareStack {

  protected Resolvable $resolver;
  protected Vector<classname<MiddlewareInterface>> $queue = Vector {};

  public function __construct(
    Traversable<classname<MiddlewareInterface>> $queue,
    ?Resolvable $resolver = null,
  ) {
    $this->queue = new Vector($queue);
    $this->resolver = (is_null($resolver)) ? new InstanceResolver() : $resolver;
  }

  <<__Rx>>
  public function isEmpty(): bool {
    return $this->queue->isEmpty();
  }

  public function reverse(): void {
    $this->queue->reverse();
  }

  public function shift(): MiddlewareInterface {
    return $this->resolver->resolve(
      $this->queue->pop()
    );
  }

  <<__Rx>>
  public function layer(): ImmVector<classname<MiddlewareInterface>> {
    return $this->queue->immutable();
  }

  public function cancel(int $index): Vector<classname<MiddlewareInterface>> {
    return $this->queue->removeKey($index);
  }
}
