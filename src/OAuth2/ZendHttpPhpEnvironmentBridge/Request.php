<?php

namespace OAuth2\ZendHttpPhpEnvironmentBridge;

use Zend\Http\PhpEnvironment\Request as BaseRequest;
use OAuth2\RequestInterface;

class Request extends BaseRequest implements RequestInterface
{
    public function query($name, $default = null)
    {
        return $this->getQuery($name, $default);
    }

    public function request($name, $default = null)
    {
        return $this->getPost($name, $default);
    }

    public function server($name, $default = null)
    {
        return $this->getServer($name, $default);
    }

    public function headers($name, $default = null)
    {
        $headers = $this->getHeaders($name, $default);
        if (method_exists($headers, 'toString')) {
            return $headers->toString();
        }

        return $headers;
    }

    public function getAllQueryParameters()
    {
        return $this->getQuery();
    }

    public static function createFromRequest(BaseRequest $request)
    {
        return new static($request->getQuery(), $request->getPost(), array(), $request->getCookie(), $request->getFiles(), $request->getServer(), $request->getContent());
    }
}
