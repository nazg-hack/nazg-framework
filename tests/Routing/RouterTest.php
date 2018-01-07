<?hh 

use PHPUnit\Framework\TestCase;
use Ytake\Adr\Routing\Router;
use Ytake\Adr\Foundation\Service;
use Ytake\Adr\Routing\HttpMethod;
use Zend\Diactoros\ServerRequestFactory;

class RouterTest extends TestCase {
  
  public function testShouldBeMatchRoute(): void {
    $router = new Router(ImmMap{
      HttpMethod::GET => ImmMap {
        '/' => IndexAction::class,
      },
    });
    $match = $router->routePsr7Request(
      ServerRequestFactory::fromGlobals([
        'REQUEST_URI' => '/',
        'REQUEST_METHOD' => 'GET'
      ])
    );
    $this->assertInternalType('array', $match);
    $this->assertSame(\IndexAction::class, $match[0]);
  }

  /**
   * @expectedException \Facebook\HackRouter\NotFoundException
   */
  public function testShouldNotBeMatchRoute(): void {
    $router = new Router(ImmMap{
      HttpMethod::GET => ImmMap {
        '/' => IndexAction::class,
      },
    });
    $match = $router->routePsr7Request(
      ServerRequestFactory::fromGlobals([
        'REQUEST_URI' => '/testing',
        'REQUEST_METHOD' => 'GET'
      ])
    );
  }
}
