use type Nazg\Heredity\{AsyncHeredity, AsyncMiddlewareStack};
use type Ytake\Hungrr\ServerRequestFactory;
use type NazgHeredityTest\Middleware\AsyncMockMiddleware;
use type Facebook\HackTest\HackTest;
use namespace HH\Lib\Experimental\IO;
use function Facebook\FBExpect\expect;

final class AsyncHeredityTest extends HackTest {

  public async function testFunctionalMiddlewareRunner(): Awaitable<void> {
    list($read, $write) = IO\pipe_non_disposable();
    $heredity = new AsyncHeredity(
      new AsyncMiddlewareStack(vec[]),
      new AsyncSimpleRequestHandler()
    );
    $response = await $heredity->handleAsync(
      $write,
      ServerRequestFactory::fromGlobals($read),
    );
    $content = await $read->readAsync();
    $decode = json_decode($content);
    expect($decode)->toBeSame([]);
  }

  public async function testFunctionalMiddlewareStackRunner(): Awaitable<void> {
    $v = vec[AsyncMockMiddleware::class, AsyncMockMiddleware::class];
    list($read, $write) = IO\pipe_non_disposable();
    $heredity = new AsyncHeredity(
      new AsyncMiddlewareStack($v),
      new AsyncSimpleRequestHandler()
    );
    $response = $heredity->handleAsync(
      $write,
      ServerRequestFactory::fromGlobals(),
    );
    $content = await $read->readAsync();
    $decode = json_decode($content);
    expect(count($decode))->toBeSame(2);
  }
}
