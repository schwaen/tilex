<?php
namespace Tilex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    private $options = [];
    
    public function __construct(array $options = [])
    {
        $this->options = $this->normalizeOptions($options);
    }
    
    protected function normalizeOptions(array $options = [])
    {
        return array_merge(
            [
                'allowedOrigins' => '*',
                'allowedMethods' => '*',
                'allowedHeaders' => '*',
                'magAge' => 0,
                'allowCredentials' => false
            ], $options
        );
    }
    
    public function isCorsRequest(Request $request)
    {
        return $request->headers->has('Origin');
    }
    
    public function isPreflightRequest(Request $request)
    {
        return
            $this->isCorsRequest($request) &&
            $request->getMethod() === 'OPTIONS' &&
            $request->headers->has('Access-Control-Request-Method')
        ;
    }
    
    public function handlePreflightRequest(Request $request)
    {
        if ($this->isPreflightRequest($request)) {
            if (!$this->checkOrigin($request)) {
                return new Response('Origin not allowed', Response::HTTP_FORBIDDEN);
            }
            if (!$this->checkMethod($request->headers->get('Access-Control-Request-Method'))) {
                return new Response('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
            }
            $response = new Response('', Response::HTTP_NO_CONTENT);
            $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
            if ($this->options['magAge'] > 0) {
                $response->headers->set('Access-Control-Max-Age', $this->options['magAge']);
            }
            if ($this->options['allowCredentials'] === true) {
                $response->headers->set(' Access-Control-Allow-Credentials', 'true');
            }
            $response->headers->set('Access-Control-Allow-Methods', $this->options['allowedMethods'] === '*' ? $request->headers->get('Access-Control-Request-Method') : $this->options['allowedMethods']);
            $response->headers->set('Access-Control-Allow-Headers', $this->options['allowedHeaders'] === '*' ? $request->headers->get('Access-Control-Request-Headers') : $this->options['allowedHeaders']);

            return $response;
        }
    }
    
    public function handleRequest(Request $request, Response $response)
    {
        if ($this->isPreflightRequest($request)) {
            $response->setContent('');
        }
        if ($this->isCorsRequest($request)) {
            $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
            $response->headers->set('Vary', !$response->headers->has('Vary') ? 'Origin' : $response->headers->get('Vary').', Origin');
        }
        //@todo  Access-Control-Expose-Headers https://www.w3.org/TR/cors/#access-control-expose-headers-response-header
    }
    
    protected function checkOrigin(Request $request)
    {
        return
            $this->options['allowedOrigins'] === '*' ||
            in_array($request->headers->get('Origin'), explode(' ', $this->options['allowedOrigins']))
        ;
    }
    
    /**
     * @param Request|string $request_method
     * @return boolean
     */
    public function checkMethod($request_method)
    {
        $method = null;
        if (is_string($request_method)) {
            $method = strtoupper($request_method);
        } elseif ($request_method instanceof Request) {
            $method = $request_method->getMethod();
        }
        return
            $method !== null &&
            ($this->options['allowedMethods'] === '*' ||
            in_array($method, array_map('trim', explode(',', $this->options['allowedMethods']))))
        ;
    }
}
