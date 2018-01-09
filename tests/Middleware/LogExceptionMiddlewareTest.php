<?hh 

use Nazg\Log\LogServiceModule;
use Nazg\Middleware\LogExceptionMiddleware;
use PHPUnit\Framework\TestCase;
use Ytake\HHContainer\ServiceModule;
use Ytake\HHContainer\FactoryContainer;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\ServerRequestFactory;
use Ytake\Heredity\Heredity;
use Ytake\Heredity\MiddlewareStack;
use Ytake\Heredity\PsrContainerResolver;

class LogExceptionMiddlewareTest extends TEstCase {
  /**
   * @expectedException \Exception
   */
  public function testShouldThrowException(): void {
    $container = $this->getDependencyContainer();
    $heredity = new Heredity(
      new MiddlewareStack(
        [LogExceptionMiddleware::class, FakeThrowExceptionMiddleware::class],
        new PsrContainerResolver($container),
      ),
    );
    $response = $heredity->process(
      ServerRequestFactory::fromGlobals(),
      new StubRequestHandler(),
    );
  }
  
  /**
   * @depends testShouldThrowException
   */
  public function testShouldCreateLogFile(): void {
    $this->assertFileExists(OverrideLogServiceModule::LOG_FILE);
    unlink(OverrideLogServiceModule::LOG_FILE);
  }
  
  private function getDependencyContainer(): FactoryContainer {
    $container = new FactoryContainer();
    $container->register(OverrideLogServiceModule::class);
    $container->set(
      LogExceptionMiddleware::class,
      $container ==> new LogExceptionMiddleware($container->get(LoggerInterface::class)),
    );
    $container->set(
      FakeThrowExceptionMiddleware::class,
      $container ==> new FakeThrowExceptionMiddleware(),
    );
    $container->lockModule();
    return $container;
  }
}
