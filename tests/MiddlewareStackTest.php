<?hh // strict

use PHPUnit\Framework\TestCase;
use Nazg\Heredity\MiddlewareStack;
use NazgHeredityTest\Middleware\MockMiddleware;
use NazgHeredityTest\Middleware\FakeMiddleware;

final class MiddlewareStackTest extends TestCase {
  /**
   * @expectedException \Nazg\Heredity\Exception\MiddlewareResolvingException
   */
  public function testShouldThrowMiddlewareResolvingException(): void {
    $stack = new MiddlewareStack(['aaa', 'bbb', 'ccc']);
    $this->assertInstanceOf(MiddlewareStack::class, $stack);
    $this->assertFalse($stack->isEmpty());
    $stack->shift();
  }

  public function testStackShift(): void {
    $stack = new MiddlewareStack(
      [MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class],
    );
    $this->assertInstanceOf(MiddlewareStack::class, $stack);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(MockMiddleware::class, $middleware);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(FakeMiddleware::class, $middleware);
    $this->assertFalse($stack->isEmpty());
    $middleware = $stack->shift();
    $this->assertInstanceOf(FakeMiddleware::class, $middleware);
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
