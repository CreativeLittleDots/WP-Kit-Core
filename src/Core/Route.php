<?php
    
    namespace WPKit\Core;
    
    use Illuminate\Routing\Route as BaseRoute;
    use Illuminate\Support\Str;
    use UnexpectedValueException;
    
    class Route extends BaseRoute {
	    
	    /**
	     * Parse the route action into a standard array.
	     *
	     * @param  callable|array  $action
	     * @return array
	     *
	     * @throws \UnexpectedValueException
	     */
	    public function reparseAction() {
		    
		    $this->action = $this->parseAction( $this->action );
		    
		    return $this;
		    
	    }
	    
	    /**
	     * Parse the route action into a standard array.
	     *
	     * @param  callable|array  $action
	     * @return array
	     *
	     * @throws \UnexpectedValueException
	     */
	    protected function parseAction($action)
	    {
		    // If the action is a string nest inside for route methods
		    if(is_string($action)) {
		        $action = ['uses' => str_replace( '::', '@', $action)];
	        }
	        
	        // If the action is already a Closure instance, we will just set that instance
	        // as the "uses" property, because there is nothing else we need to do when
	        // it is available. Otherwise we will need to find it in the action list.
	        if (is_callable($action)) {
	            return ['uses' => $action];
	        }
	
	        // If no "uses" property has been set, we will dig through the array to find a
	        // Closure instance within this list. We will set the first Closure we come
	        // across into the "uses" property that will get fired off by this route.
	        elseif (! isset($action['uses'])) {
	            $action['uses'] = $this->findCallable($action);
	        }
	
	        if (is_string($action['uses'])) {
		        
		        if(! Str::contains($action['uses'], '@') ) {
	            	$action['uses'] .= '@dispatch';
	            }
	            
	            if( $this->container ) {
	            
		            $action['uses'] = Str::parseCallback( $action['uses'], 'dispatch' );
		            $action['uses'][0] = stripos( $action['uses'][0], '\\' ) === 0 ? $action['uses'][0] : $this->container->getControllerName( $action['uses'][0] );
		            $action['uses'] = implode( '@', $action['uses'] );
		            
				}
	            
	        }
	
	        return $action;
	    }
	    
    }