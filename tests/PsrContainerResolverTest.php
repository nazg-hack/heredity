<?hh // strict

use type PHPUnit\Framework\TestCase;
use type Nazg\Heredity\PsrContainerResolver;
use type Nazg\Heredity\MiddlewareStack;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type Ytake\HHContainer\FactoryContainer;

final class PsrContainerResolverTest extends TestCase {

  public function testShouldReturnMiddlewareInstance(): void {
    $container = new FactoryContainer();
    $container->set(
      MockMiddleware::class,
      $container ==> new MockMiddleware(),
    );
    $resolver = new PsrContainerResolver($container);
    $class = $resolver->resolve(MockMiddleware::class);
    $this->assertInstanceOf(MockMiddleware::class, $class);
  }

  public function testFunctionalMiddlewareStackWithContainer(): void {
    $container = new FactoryContainer();
    $container->set(
      MockMiddleware::class,
      $container ==> new MockMiddleware(),
    );
    $stack = new MiddlewareStack(
      [MockMiddleware::class],
      new PsrContainerResolver($container),
    );
    $this->assertInstanceOf(MiddlewareStack::class, $stack);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(MockMiddleware::class, $middleware);
  }
}
