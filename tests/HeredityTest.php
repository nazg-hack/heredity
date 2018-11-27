<?hh // strict

use type Nazg\Heredity\Heredity;
use type Nazg\Heredity\MiddlewareStack;
use type Ytake\Hungrr\ServerRequestFactory;
use type Ytake\Hungrr\Response;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type Facebook\HackTest\HackTest;
use function Facebook\FBExpect\expect;

final class HeredityTest extends HackTest {

  public function testFunctionalMiddlewareRunner(): void {
    $heredity = new Heredity(
      new MiddlewareStack([]),
      new SimpleRequestHandler()
    );
    $response = $heredity->handle(
      ServerRequestFactory::fromGlobals(),
    );
    invariant($response is Response, "e");
    $content = $response->readBody()->rawReadBlocking();
    $decode = json_decode($content);
    expect($decode)->toBeSame([]);

    $heredity = new Heredity(
      new MiddlewareStack([MockMiddleware::class, MockMiddleware::class]),
      new SimpleRequestHandler()
    );
    $response = $heredity->handle(
      ServerRequestFactory::fromGlobals(),
    );
    invariant($response is Response, "e");
    $content = $response->readBody()->rawReadBlocking();
    $decode = json_decode($content);
    expect(count($decode))->toBeSame(2);
  }
}
