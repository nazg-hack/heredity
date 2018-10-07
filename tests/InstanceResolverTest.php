<?hh // strict

use type Nazg\Heredity\InstanceResolver;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class InstanceResolverTest extends HackTest {

  public function testShouldReturnMiddlewareInstance(): void {
    $resolver = new InstanceResolver();
    $class = $resolver->resolve(MockMiddleware::class);
    expect($class)->toBeInstanceOf(MockMiddleware::class);

    $resolver = new InstanceResolver();
    $class = $resolver->resolve(MockMiddleware::class);
    expect($class)->toBeInstanceOf(MockMiddleware::class);
  }
}
