use type Nazg\Heredity\{AsyncHeredity, AsyncMiddlewareStack};
use type Ytake\Hungrr\ServerRequestFactory;
use type NazgHeredityTest\Middleware\AsyncMockMiddleware;
use type Facebook\HackTest\HackTest;
use namespace HH\Lib\Experimental\IO;
use function Facebook\FBExpect\expect;

final class AsyncHeredityTest extends HackTest {

  public async function testFunctionalMiddlewareRunner(): Awaitable<void> {
    list($read, $write) = IO\pipe_nd();
    $heredity = new AsyncHeredity(
      new AsyncMiddlewareStack(vec[]),
      new AsyncSimpleRequestHandler()
    );
    $_ = await $heredity->handleAsync(
      $write,
      ServerRequestFactory::fromGlobals($read),
    );
    $content = await $read->readAsync();
    $decode = json_decode($content, true, 512, \JSON_FB_HACK_ARRAYS);
    expect($decode)->toBeSame(dict[]);
  }

  public async function testFunctionalMiddlewareStackRunner(): Awaitable<void> {
    $v = vec[AsyncMockMiddleware::class, AsyncMockMiddleware::class];
    list($read, $write) = IO\pipe_nd();
    $heredity = new AsyncHeredity(
      new AsyncMiddlewareStack($v),
      new AsyncSimpleRequestHandler()
    );
    $_ = $heredity->handleAsync(
      $write,
      ServerRequestFactory::fromGlobals(),
    );
    $content = await $read->readAsync();
    $decode = json_decode($content);
    expect(count($decode))->toBeSame(2);
  }
}
