<?hh // strict

namespace Ytake\Heredity;

use ReflectionException;
use ReflectionClass;
use Ytake\Heredity\Exception\MiddlewareResolvingException;
use Interop\Http\Server\MiddlewareInterface;

class InstanceResolver implements Resolvable {

  public function resolve(mixed $middleware): MiddlewareInterface {
    try{ 
      $ref = new ReflectionClass($middleware);
      return $ref->newInstance();
    }catch(ReflectionException $e){
      throw new MiddlewareResolvingException($e->getMessage(), $e->getCode(), $e);
    }
  }
}
