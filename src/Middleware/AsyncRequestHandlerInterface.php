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
use type Nazg\Http\Server\RequestHandlerInterface;

/**
 * An HTTP request handler process a HTTP request and produces an HTTP response.
 * This interface defines the methods require to use the request handler.
 */
interface AsyncRequestHandlerInterface extends RequestHandlerInterface {

  /**
   * Handle the request and return a response.
   * @param WriteHandle $writeHandle
   * @param ServerRequestInterface $request
   *
   * @return Awaitable<ResponseInterface>
   */
  public function handleAsync(
    WriteHandle $writeHandle,
    ServerRequestInterface $request
  ): Awaitable<ResponseInterface>;
}
