use type Nazg\Heredity\{Heredity, MiddlewareStack};
use type Ytake\Hungrr\ServerRequestFactory;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type Facebook\HackTest\HackTest;
use namespace HH\Lib\IO;
use function Facebook\FBExpect\expect;

final class HeredityTest extends HackTest {

  public function testFunctionalMiddlewareRunner(): void {
    list($read, $write) = IO\pipe_nd();
    $heredity = new Heredity(
      new MiddlewareStack(vec[]),
      new SimpleRequestHandler()
    );
    $_ = $heredity->handle(
      $write,
      ServerRequestFactory::fromGlobals($read),
    );
    $content = $read->rawReadBlocking();
    $decode = json_decode($content, true, 512, \JSON_FB_HACK_ARRAYS);
    expect($decode)->toBeSame(dict[]);
  }

  public function testFunctionalMiddlewareStackRunner(): void {
    $v = vec[MockMiddleware::class, MockMiddleware::class];
    list($read, $write) = IO\pipe_nd();
    $heredity = new Heredity(
      new MiddlewareStack($v),
      new SimpleRequestHandler()
    );
    $_ = $heredity->handle(
      $write,
      ServerRequestFactory::fromGlobals(),
    );
    $decode = json_decode($read->rawReadBlocking());
    expect(count($decode))->toBeSame(2);
  }
}
