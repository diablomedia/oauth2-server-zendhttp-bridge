<?php

namespace OAuth2Test\ZendHttpPhpEnvironmentBridge;

use Zend\Http\PhpEnvironment\Request as BaseRequest;
use OAuth2\ZendHttpPhpEnvironmentBridge\Request;
use PHPUnit_Framework_TestCase;

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->baseRequest = BaseRequest::fromString("GET /index.php?test=true HTTP/1.1\r\n\r\nSome Content");
        $serverVars = $this->baseRequest->getServer();
        $serverVars['REQUEST_METHOD'] = 'GET';
    }

    public function testCreateFromRequestReturnsRequestObject()
    {
        $request = Request::createFromRequest($this->baseRequest);

        $this->assertInstanceOf('OAuth2\ZendHttpPhpEnvironmentBridge\Request', $request);
    }

    public function testRequestHasQueryMethod()
    {
        $request = Request::createFromRequest($this->baseRequest);

        $this->assertEquals('true', $request->query('test'));
    }

    public function testRequestHasServerMethod()
    {
        $request = Request::createFromRequest($this->baseRequest);

        $this->assertEquals('GET', $request->server('REQUEST_METHOD'));
    }

    public function testRequestHasRequestMethodAndReturnsPostVars()
    {
        $baseRequest = BaseRequest::fromString("POST /index.php HTTP/1.1\r\n\r\ntesting=true");
        $serverVars = $baseRequest->getServer();
        $serverVars['REQUEST_METHOD'] = 'POST';

        $postVars = $baseRequest->getPost();
        $postVars['testing'] = 'true';

        $request = Request::createFromRequest($baseRequest);

        $this->assertEquals('true', $request->request('testing'));
    }

    public function testRequestHasHeadersMethod()
    {
        $baseRequest = BaseRequest::fromString("GET /index.php HTTP/1.1\r\nAccept: */*\r\n\r\nSome Content");

        $request = Request::createFromRequest($baseRequest);

        $this->assertEquals('Accept: */*', $request->headers('Accept'));
    }
}
