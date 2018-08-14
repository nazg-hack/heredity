<?hh // strict

use type PHPUnit\Framework\TestCase;
use type Nazg\Heredity\InstanceResolver;
use type NazgHeredityTest\Middleware\MockMiddleware;

final class InstanceResolverTest extends TestCase {

  public function testShouldReturnMiddlewareInstance(): void {
    $resolver = new InstanceResolver();
    $class = $resolver->resolve(MockMiddleware::class);
    $this->assertInstanceOf(MockMiddleware::class, $class);

    $resolver = new InstanceResolver();
    $class = $resolver->resolve(MockMiddleware::class);
    $this->assertInstanceOf(MockMiddleware::class, $class);
  }
}
