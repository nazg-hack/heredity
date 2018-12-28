<?hh // strict

use type Nazg\Heredity\MiddlewareStack;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type NazgHeredityTest\Middleware\FakeMiddleware;
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class MiddlewareStackTest extends HackTest {

  public function testStackShift(): void {
    $stack = new MiddlewareStack(
      Vector{MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class},
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
      Vector{MockMiddleware::class, FakeMiddleware::class, FakeMiddleware::class};
    $stack = new MiddlewareStack($middlewares);
    expect($stack->layer()->toVArray())->toBeSame($middlewares->toVArray());
  }

  public function testShouldReturnSkipedMiddleware(): void {
    $middlewares = Vector{
      MockMiddleware::class,
      FakeMiddleware::class,
      FakeMiddleware::class,
    };
    $stack = new MiddlewareStack($middlewares);
    $stack->cancel(0);
    expect($stack->layer()->toArray())->toNotBeSame($middlewares);
  }
}
