use type Nazg\Heredity\AsyncMiddlewareStack;
use type NazgHeredityTest\Middleware\AsyncMockMiddleware;
use type NazgHeredityTest\Middleware\AsyncFakeMiddleware;
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class AsyncMiddlewareStackTest extends HackTest {

  public function testStackShift(): void {
    $stack = new AsyncMiddlewareStack(
      vec[AsyncMockMiddleware::class, AsyncFakeMiddleware::class, AsyncFakeMiddleware::class],
    );
    expect($stack)->toBeInstanceOf(AsyncMiddlewareStack::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(AsyncFakeMiddleware::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(AsyncFakeMiddleware::class);
    expect($stack->isEmpty())->toBeFalse();
    $middleware = $stack->shift();
    expect($middleware)->toBeInstanceOf(AsyncMockMiddleware::class);
    expect($stack->isEmpty())->toBeTrue();
  }

  public function testShouldReturnMiddlewareStackLayer(): void {
    $middlewares =
      vec[AsyncMockMiddleware::class, AsyncFakeMiddleware::class, AsyncFakeMiddleware::class];
    $stack = new AsyncMiddlewareStack($middlewares);
    expect($stack->layer())->toBeInstanceOf(ImmVector::class);
  }

  public function testShouldReturnSkipedMiddleware(): void {
    $middlewares = vec[
      AsyncMockMiddleware::class,
      AsyncFakeMiddleware::class,
      AsyncFakeMiddleware::class,
    ];
    $stack = new AsyncMiddlewareStack($middlewares);
    $stack->cancel(0);
    expect($stack->layer()->toArray())->toNotBeSame($middlewares);
  }
}
