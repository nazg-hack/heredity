use type Nazg\Heredity\MiddlewareStack;
use type NazgHeredityTest\Middleware\{FakeMiddleware, MockMiddleware};
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class MiddlewareStackTest extends HackTest {

  public function testStackShift(): void {
    $stack = new MiddlewareStack(
      vec[MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class],
    );
    expect($stack)->toBeInstanceOf(MiddlewareStack::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(FakeMiddleware::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(FakeMiddleware::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(MockMiddleware::class);
    expect($stack->isEmpty())->toBeTrue();
  }

  public function testShouldReturnMiddlewareStackLayer(): void {
    $middlewares =
      vec[MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class];
    $stack = new MiddlewareStack($middlewares);
    expect($stack->layer())->toBeInstanceOf(ImmVector::class);
  }

  public function testShouldReturnSkipedMiddleware(): void {
    $middlewares = vec[
      MockMiddleware::class,
      FakeMiddleware::class,
      FakeMiddleware::class,
    ];
    $stack = new MiddlewareStack($middlewares);
    $stack->cancel(0);
    expect($stack->layer()->toVArray())->toNotBeSame($middlewares);
  }
}
