<?php

namespace OAuth2\ZendHttpPhpEnvironmentBridge;

use Zend\Http\PhpEnvironment\Response as BaseResponse;
use OAuth2\ResponseInterface;

class Response extends BaseResponse implements ResponseInterface
{
    public function addParameters(array $parameters)
    {
        if ($this->content) {
            $parameters = array_merge($this->content, $parameters);
        }
        $this->setContent($parameters);
    }

    public function addHttpHeaders(array $httpHeaders)
    {
        $this->getHeaders()->addHeaders($httpHeaders);
    }

    public function getParameter($name)
    {
        return isset($this->content[$name]) ? $this->content[$name] : null;
    }

    public function setError($statusCode, $name, $description = null, $uri = null)
    {
        $this->setStatusCode($statusCode);
        $this->addParameters(
            array_filter(
                array(
                    'error'             => $name,
                    'error_description' => $description,
                    'error_uri'         => $uri,
                )
            )
        );
    }

    public function setRedirect($statusCode = 302, $url, $state = null, $error = null, $errorDescription = null, $errorUri = null)
    {
        $this->setStatusCode($statusCode);
        $this->addParameters(
            array_filter(
                array(
                    'error'             => $error,
                    'error_description' => $errorDescription,
                    'error_uri'         => $errorUri,
                )
            )
        );
        $this->addHttpHeaders(array('Location' => $url));
    }
}
