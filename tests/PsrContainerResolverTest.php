<?hh // strict

use type Nazg\Heredity\PsrContainerResolver;
use type Nazg\Heredity\MiddlewareStack;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type Ytake\HHContainer\FactoryContainer;
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class PsrContainerResolverTest extends HackTest {

  public function testShouldReturnMiddlewareInstance(): void {
    $container = new FactoryContainer();
    $container->set(
      MockMiddleware::class,
      $container ==> new MockMiddleware(),
    );
    $resolver = new PsrContainerResolver($container);
    $class = $resolver->resolve(MockMiddleware::class);
    expect($class)->toBeInstanceOf(MockMiddleware::class);
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
    expect($stack)->toBeInstanceOf(MiddlewareStack::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(MockMiddleware::class);
  }
}
