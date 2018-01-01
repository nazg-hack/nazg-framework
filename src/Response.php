<?hh

namespace Ytake\Adr;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\EmitterInterface;

class Response {
  
  public function __construct(protected ResponseInterface $response) {}

  public function send(): void {
    $this->emitter()->emit($this->response);
  }

  protected function emitter(): EmitterInterface {
    return new SapiEmitter();
  }
}