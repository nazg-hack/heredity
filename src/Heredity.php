<?hh // strict

namespace Ytake\Heredity;

use Interop\Http\Server\RequestHandlerInterface;
use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Heredity implements MiddlewareInterface {

  public function __construct(protected MiddlewareStack $stack) {}

  public function process(
    ServerRequestInterface $request, 
    RequestHandlerInterface $handler) : ResponseInterface 
  {
    $dispatchHandler = new Dispatcher($this->stack, $handler);
    return $dispatchHandler->handle($request);
  }
}
