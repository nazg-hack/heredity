<?hh

use PHPUnit\Framework\TestCase;
use Nazg\Heredity\Heredity;
use Nazg\Heredity\MiddlewareStack;
use Zend\Diactoros\ServerRequestFactory;

class HeredityTest extends TestCase {

  public function testFunctionalMiddlewareRunner(): void {
    $heredity = new Heredity(
      new MiddlewareStack([]), 
      new SimpleRequestHandler()
    );
    $response = $heredity->handle(
      ServerRequestFactory::fromGlobals(),
    );
    $content = $response->getBody()->getContents();
    $decode = json_decode($content);
    $this->assertCount(0, $decode);

    $heredity = new Heredity(
      new MiddlewareStack([MockMiddleware::class, MockMiddleware::class]),
      new SimpleRequestHandler()
    );
    $response = $heredity->handle(
      ServerRequestFactory::fromGlobals(),
    );
    $content = $response->getBody()->getContents();
    $decode = json_decode($content);
    $this->assertCount(2, $decode);
  }
}
