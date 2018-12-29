<?hh // strict

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2018 Yuuki Takezawa
 *
 */
namespace Nazg\Heredity\Middleware;

use type HH\Lib\Experimental\IO\WriteHandle;
use type Facebook\Experimental\Http\Message\ResponseInterface;
use type Facebook\Experimental\Http\Message\ServerRequestInterface;
use type Nazg\Http\Server\MiddlewareInterface;

/**
 * An HTTP middleware component participates in processing an HTTP message,
 * either by acting on the request or the response. This interface defines the
 * methods required to use the middleware.
 */
interface AsyncMiddlewareInterface extends MiddlewareInterface {

  /**
   * Process an incoming server request and return a response, optionally delegating
   * response creation to a handler.
   *
   * @param WriteHandle $handler
   * @param ServerRequestInterface $request
   * @param RequestHandlerInterface $handler
   * @return Awaitable<ResponseInterface>
   */
  public function processAsync(
    WriteHandle $writeHandle,
    ServerRequestInterface $request,
    AsyncRequestHandlerInterface $handler
  ): Awaitable<ResponseInterface>;
}
