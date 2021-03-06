<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests;

use Bluz\Http;
use Bluz\Request\AbstractRequest;
use Bluz\Response\AbstractResponse;

/**
 * Controller TestCase
 *
 * @package Application\Tests
 *
 * @author   Anton Shevchuk
 * @created  04.08.11 20:01
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Application entity
     *
     * @var \Application\Bootstrap
     */
    protected $app;

    /**
     * Setup TestCase
     */
    protected function setUp()
    {
        $this->app = BootstrapTest::getInstance();
        $this->app->init('testing');

        $this->reset();
    }

    /**
     * Reset layout and Request
     */
    private function reset()
    {
        $this->app->resetLayout();

        $this->app->getAuth()->clearIdentity();
        $this->app->setRequest(new Http\Request());
        $this->app->setResponse(new Http\Response());
    }

    /**
     * dispatch URI
     *
     * @param string $uri in format "module/controller"
     * @param array $params of request
     * @param string $method HTTP
     *
     * @return AbstractResponse
     */
    protected function dispatchUri($uri, array $params = null, $method = Http\Request::METHOD_GET)
    {
        $request = new Http\Request();

        $this->app->setRequest($request);
        $this->app->getRequest()->setOptions($this->app->getConfigData('request'));
        $this->app->getRequest()->setMethod($method);

        $uri = trim($uri, '/ ');
        list($module, $controller) = explode('/', $uri);

        // set default controller
        if (!$controller) {
            $controller = $request->getController();
        }

        $this->app->getRequest()
            ->setModule($module)
            ->setController($controller)
            ->setRequestUri($uri);

        if ($params) {
            $this->app->getRequest()->setParams($params);
        }
        return $this->app->process();
    }

    /**
     * dispatch Request
     *
     * @param AbstractRequest $request
     *
     * @return AbstractResponse
     */
    protected function dispatchRequest($request)
    {
        $this->app->setRequest($request);
        return $this->app->process();
    }
}
