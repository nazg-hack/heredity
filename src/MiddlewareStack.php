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

use Interop\Http\Server\MiddlewareInterface;

class MiddlewareStack {

  protected Resolvable $resolver;
  protected Vector<mixed> $queue = Vector {};
  protected int $position = 0;

  public function __construct(
    Traversable<mixed> $queue,
    ?Resolvable $resolver = null,
  ) {
    $this->queue = new Vector($queue);
    $this->resolver =
      (is_null($resolver)) ? new InstanceResolver() : $resolver;
  }

  public function isEmpty(): bool {
    return $this->queue->isEmpty();
  }

  public function shift(): MiddlewareInterface {
    $this->queue->reverse();
    $current = $this->queue->pop();
    return $this->resolver->resolve($current);
  }

  public function layer(): ImmVector<mixed> {
    return $this->queue->immutable();
  }

  public function cancel(int $index): Vector<mixed> {
    return $this->queue->removeKey($index);
  }
}
