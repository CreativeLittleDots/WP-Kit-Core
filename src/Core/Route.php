<?php 
	
	namespace WPKit\Core;

	use WPKit\Http\Response;
	use WPKit\Http\JsonResponse;
	use Exception;
	use Illuminate\Contracts\Support\Jsonable;
	use JsonSerializable;
	
	class Route {
	
	    /**
	     * @var \WPKit\Application
	     */
	    protected $app;
	
	    /**
	     * @var array
	     */
	    protected $parameters;
	
	    /**
	     * @var string
	     */
	    protected $uri;
	
	    /**
	     * @var string
	     */
	    protected $name;
	
	    /**
	     * @var string
	     */
	    protected $callback;
	
	    /**
	     * @param \WPKit\Application $app
	     * @param                                $data
	     * @param                                $parameters
	     */
	    public function __construct(Application $app, $data, $parameters = [])
	    {
	        $this->app = $app;
	        $this->parameters = $parameters;
	        $this->uri = $data['uri'];
	        $this->name = array_get($data, 'as', $this->uri);
	        $this->callback = $data['callback'];
	    }
	
	    /**
	     * Handles the route.
	     */
	    public function handle()
	    {
	        $response = $this->app->call(
	            $this->callback,
	            array_merge(['app' => $this->app], $this->parameters)
	        );
	
	        if ($response instanceof Response)
	        {
	            return $response;
	        }
	
	        if (is_null($response) || is_string($response))
	        {
	            return new Response($response);
	        }
	
	        if (is_array($response) || $response instanceof Jsonable || $response instanceof JsonSerializable)
	        {
	            return new JsonResponse($response);
	        }
	
	        throw new Exception('Unknown response type!');
	    }
	
	    /**
	     * Get a single parameter.
	     *
	     * @param       $name
	     * @param mixed $default
	     * @return mixed
	     */
	    public function parameter($name, $default = null)
	    {
	        if ( ! isset($this->parameters[$name]))
	        {
	            return $default;
	        }
	
	        return $this->parameters[$name];
	    }
	
	}