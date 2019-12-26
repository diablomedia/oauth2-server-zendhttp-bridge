<?php

namespace OAuth2Test\ZendHttpPhpEnvironmentBridge;

use Zend\Http\PhpEnvironment\Request as BaseRequest;
use OAuth2\ZendHttpPhpEnvironmentBridge\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var BaseRequest
     */
    protected $baseRequest;

    public function setup(): void
    {
        $this->baseRequest = BaseRequest::fromString("GET /index.php?test=true HTTP/1.1\r\n\r\nSome Content");
        $serverVars = $this->baseRequest->getServer();
        $serverVars['REQUEST_METHOD'] = 'GET';
    }

    public function testCreateFromRequestReturnsRequestObject(): void
    {
        $request = Request::createFromRequest($this->baseRequest);

        $this->assertInstanceOf('OAuth2\ZendHttpPhpEnvironmentBridge\Request', $request);
    }

    public function testRequestHasQueryMethod(): void
    {
        $request = Request::createFromRequest($this->baseRequest);

        $this->assertEquals('true', $request->query('test'));
    }

    public function testRequestHasServerMethod(): void
    {
        $request = Request::createFromRequest($this->baseRequest);

        $this->assertEquals('GET', $request->server('REQUEST_METHOD'));
    }

    public function testRequestHasRequestMethodAndReturnsPostVars(): void
    {
        $baseRequest = BaseRequest::fromString("POST /index.php HTTP/1.1\r\n\r\ntesting=true");
        $serverVars = $baseRequest->getServer();
        $serverVars['REQUEST_METHOD'] = 'POST';

        $postVars = $baseRequest->getPost();
        $postVars['testing'] = 'true';

        $request = Request::createFromRequest($baseRequest);

        $this->assertEquals('true', $request->request('testing'));
    }

    public function testRequestHasHeadersMethod(): void
    {
        $baseRequest = BaseRequest::fromString("GET /index.php HTTP/1.1\r\nAccept: */*\r\n\r\nSome Content");

        $request = Request::createFromRequest($baseRequest);

        $this->assertEquals('Accept: */*', $request->headers('Accept'));
    }
}
