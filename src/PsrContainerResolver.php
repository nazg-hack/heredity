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

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Server\MiddlewareInterface;
use Nazg\Heredity\Exception\MiddlewareResolvingException;

class PsrContainerResolver implements Resolvable {

  public function __construct(protected ContainerInterface $container) {}

  public function resolve(classname<MiddlewareInterface> $middleware): MiddlewareInterface {
    if ($this->container->has($middleware)) {
      $instance = $this->container->get($middleware);
      if ($instance instanceof MiddlewareInterface) {
        return $instance;
      }
    }

    throw new MiddlewareResolvingException(
      \sprintf('Identifier "%s" is not binding.', $middleware),
    );
  }
}
