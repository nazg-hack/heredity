<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\Heredity\InstanceResolver;

class InstanceResolverTest extends TestCase {

  public function testShouldReturnMiddlewareInstance(): void {
    $resolver = new InstanceResolver();
    $class = $resolver->resolve(new MockMiddleware());
    $this->assertInstanceOf(MockMiddleware::class, $class);

    $resolver = new InstanceResolver();
    $class = $resolver->resolve(MockMiddleware::class);
    $this->assertInstanceOf(MockMiddleware::class, $class);
  }
}
