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

    /**
     * @return Request
     */
    public static function createFromRequest(BaseRequest $request)
    {
        $new = static::fromString($request->toString());
        $new->setQuery($request->getQuery());
        $new->setPost($request->getPost());
        $new->setCookies($request->getCookie());
        $new->setFiles($request->getFiles());
        $new->setServer($request->getServer());
        $new->setContent($request->getContent());
        $new->setEnv($request->getEnv());

        $headers = $request->getHeaders();
        $new->setHeaders($headers);

        return $new;
    }
}
