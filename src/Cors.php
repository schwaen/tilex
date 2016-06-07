<?php
namespace Tilex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CORS
 */
class Cors
{
    private $options = [];
    
    public function __construct(array $options = [])
    {
        $this->options = $this->normalizeOptions($options);
    }
    
    /**
     * Normalitze and return the $options-Array with default-Values if not set
     * @param array $options
     * @return multitype:Array
     */
    protected function normalizeOptions(array $options = [])
    {
        $options = array_merge(
            [
                'allowedOrigins' => '*',
                'allowedMethods' => '*',
                'allowedHeaders' => '*',
                'exposedHeaders' => '',
                'magAge' => 0,
                'allowCredentials' => false
            ], $options
        );
        foreach (['allowedMethods', 'allowedHeaders', 'exposedHeaders'] as $key) {
            if (is_array($options[$key])) {
                $options[$key] = implode(', ', $options[$key]);
            }
        }
        return $options;
    }
    
    /**
     * Checks if $request is a CORS request
     * @param Request $request
     * @return boolean
     */
    public function isCorsRequest(Request $request)
    {
        return $request->headers->has('Origin');
    }
    
    /**
     * Checks if $request is a CORS preflight request
     * @param Request $request
     * @return boolean
     */
    public function isPreflightRequest(Request $request)
    {
        return
            $this->isCorsRequest($request) &&
            $request->getMethod() === 'OPTIONS' &&
            $request->headers->has('Access-Control-Request-Method')
        ;
    }
    
    /**
     * Handles the preflight request
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
    
    /**
     * Handles the CORS request
     * @param Request $request
     * @param Response $response
     */
    public function handleRequest(Request $request, Response $response)
    {
        if ($this->isPreflightRequest($request)) {
            $response->setContent('');
        } elseif ($this->isCorsRequest($request) && $this->checkOrigin($request)) {
            $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
            $response->headers->set('Vary', !$response->headers->has('Vary') ? 'Origin' : $response->headers->get('Vary').', Origin');
            if (!empty($this->options['exposedHeaders'])) {
              $response->headers->set('Access-Control-Expose-Headers', $this->options['exposedHeaders']);
            }
        }
    }
    
    /**
     * Check the origin of $request
     * @param Request $request
     * @return boolean
     */
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
