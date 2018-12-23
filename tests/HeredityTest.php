<?hh // strict

use type Nazg\Heredity\Heredity;
use type Nazg\Heredity\MiddlewareStack;
use type Ytake\Hungrr\ServerRequestFactory;
use type Ytake\Hungrr\Response;
use type NazgHeredityTest\Middleware\MockMiddleware;
use type Facebook\HackTest\HackTest;
use namespace HH\Lib\Experimental\IO;
use function Facebook\FBExpect\expect;

final class HeredityTest extends HackTest {

  public function testFunctionalMiddlewareRunner(): void {
    list($read, $write) = IO\pipe_non_disposable();
    $heredity = new Heredity(
      new MiddlewareStack([]),
      new SimpleRequestHandler($write)
    );
    $response = $heredity->handle(
      ServerRequestFactory::fromGlobals($read),
    );
    $content = $read->rawReadBlocking();
    $decode = json_decode($content);
    expect($decode)->toBeSame([]);
  }

  public function testFunctionalMiddlewareStackRunner(): void {
    list($read, $write) = IO\pipe_non_disposable();
    $heredity = new Heredity(
      new MiddlewareStack([MockMiddleware::class, MockMiddleware::class]),
      new SimpleRequestHandler($write)
    );
    $response = $heredity->handle(
      ServerRequestFactory::fromGlobals(),
    );
    $decode = json_decode($read->rawReadBlocking());
    expect(count($decode))->toBeSame(2);
  }
}
