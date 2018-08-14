<?hh // strict

use type PHPUnit\Framework\TestCase;
use type Nazg\Heredity\MiddlewareStack;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type NazgHeredityTest\Middleware\FakeMiddleware;

final class MiddlewareStackTest extends TestCase {

  public function testStackShift(): void {
    $stack = new MiddlewareStack(
      [MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class],
    );
    $this->assertInstanceOf(MiddlewareStack::class, $stack);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(FakeMiddleware::class, $middleware);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(FakeMiddleware::class, $middleware);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(MockMiddleware::class, $middleware);
    $this->assertTrue($stack->isEmpty());
  }

  public function testShouldReturnMiddlewareStackLayer(): void {
    $middlewares =
      [MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class];
    $stack = new MiddlewareStack($middlewares);
    $this->assertEquals($middlewares, $stack->layer()->toArray());
  }

  public function testShouldReturnSkipedMiddleware(): void {
    $middlewares =
      [MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class];
    $stack = new MiddlewareStack($middlewares);
    $stack->cancel(0);
    $this->assertNotEquals($middlewares, $stack->layer()->toArray());
  }
}
