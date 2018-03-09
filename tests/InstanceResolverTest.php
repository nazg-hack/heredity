<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\Heredity\InstanceResolver;
use NazgHeredityTest\Middleware\MockMiddleware;

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
