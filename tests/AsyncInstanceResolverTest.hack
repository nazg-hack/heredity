use type Nazg\Heredity\AsyncInstanceResolver;
use type NazgHeredityTest\Middleware\AsyncMockMiddleware;
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class AsyncInstanceResolverTest extends HackTest {

  public function testShouldReturnMiddlewareInstance(): void {
    $resolver = new AsyncInstanceResolver();
    $class = $resolver->resolve(AsyncMockMiddleware::class);
    expect($class)->toBeInstanceOf(AsyncMockMiddleware::class);

    $resolver = new AsyncInstanceResolver();
    $class = $resolver->resolve(AsyncMockMiddleware::class);
    expect($class)->toBeInstanceOf(AsyncMockMiddleware::class);
  }
}
