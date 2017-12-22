<?hh // strict

namespace Ytake\Heredity;

use Interop\Http\Server\RequestHandlerInterface;
use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Dispatcher implements RequestHandlerInterface {

  public function __construct(protected MiddlewareStack $stack, protected ?RequestHandlerInterface $handler = null) {}

  public function handle(ServerRequestInterface $request): ResponseInterface {
    if($this->stack->isEmpty()) {
      if ($this->handler) {
        return $this->handler->handle($request);
      }
    }
    $middleware = $this->stack->shift();
    $response = $middleware->process($request, $this);

    return $response;
  }
}
