<?hh

use PHPUnit\Framework\TestCase;
use Ytake\Heredity\Heredity;
use Ytake\Heredity\MiddlewareStack;
use Zend\Diactoros\ServerRequestFactory;

class HeredityTest extends TestCase {

  public function testFunctionalMiddlewareRunner(): void {
    $heredity = new Heredity(new MiddlewareStack([]));
    $response = $heredity->process(
      ServerRequestFactory::fromGlobals(),
      new SimpleRequestHandler(),
    );
    $content = $response->getBody()->getContents();
    $decode = json_decode($content);
    $this->assertCount(0, $decode);

    $heredity = new Heredity(
      new MiddlewareStack([MockMiddleware::class, MockMiddleware::class]),
    );
    $response = $heredity->process(
      ServerRequestFactory::fromGlobals(),
      new SimpleRequestHandler(),
    );
    $content = $response->getBody()->getContents();
    $decode = json_decode($content);
    $this->assertCount(2, $decode);
  }
}
