<?hh // strict

namespace Ytake\Heredity;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ytake\Heredity\Exception\MiddlewareResolvingException;
use Interop\Http\Server\MiddlewareInterface;

class PsrContainerResolver implements Resolvable {
  
  public function __construct(protected ContainerInterface $container) {}

  public function resolve(mixed $middleware): MiddlewareInterface {
    if(is_string($middleware)) {
      if($this->container->has($middleware)) {
        try {
          $instance = $this->container->get($middleware);
          if($instance instanceof MiddlewareInterface) {
            return $instance;  
          }
        } catch(\Exception $e) {
          if($e instanceof NotFoundExceptionInterface) {
            throw new MiddlewareResolvingException(sprintf('Identifier "%s" is not binding.', get_class($middleware)));
          }
          throw $e;
        }
      }
    }

    throw new MiddlewareResolvingException(sprintf('Identifier "%s" is not binding.', get_class($middleware)));
  }
}
