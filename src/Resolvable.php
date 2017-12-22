<?hh // strict

namespace Ytake\Heredity;

use Interop\Http\Server\MiddlewareInterface;

interface Resolvable {

  public function resolve(mixed $middleware): MiddlewareInterface;
}
