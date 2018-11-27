<?hh // strict

use type Psr\Container\ContainerInterface;
use type Ytake\HackHttpServer\MiddlewareInterface;
use type Nazg\Heredity\Exception\MiddlewareResolvingException;
use type Nazg\Heredity\Resolvable;

use function sprintf;

class PsrContainerResolver implements Resolvable {

  public function __construct(
    protected ContainerInterface $container
  ) {}

  public function resolve(
    classname<MiddlewareInterface> $middleware
  ): MiddlewareInterface {
    if ($this->container->has($middleware)) {
      $instance = $this->container->get($middleware);
      if ($instance is MiddlewareInterface) {
        return $instance;
      }
    }

    throw new MiddlewareResolvingException(
      sprintf('Identifier "%s" is not binding.', $middleware),
    );
  }
}
