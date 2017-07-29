<?php

	namespace WPKit\Exceptions;
	
	use Exception;
    use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
    use WPKit\Http\Response;
	
	class Handler implements ExceptionHandlerContract {
        
        /**
	     * A list of the exception types that should not be reported.
	     *
	     * @var array
	     */
	    protected $dontReport = [
		    \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException::class,
		    \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
		    \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class
	    ];



        /**
         * Report or log an exception.
         *
         * @param  \Exception $e
         *
         * @return void
         */
        public function report( Exception $e ) {
	        
	        if ( $this->shouldntReport($e) ) {
		        
	            return;
	            
	        }
	        
	        error_log( $e->getMessage() );
		
        }
	
	    /**
	     * Determine if the exception is in the "do not report" list.
	     *
	     * @param  \Exception  $e
	     * @return bool
	     */
	    protected function shouldntReport(Exception $e)
	    {
			foreach($this->dontReport as $exception) {
				if($e instanceof $exception) {
					return true;
				}
			}
	    }

        /**
         * Render an exception into an HTTP response.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \Exception               $e
         *
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function render( $request, Exception $e ) {
	        
	        if ( $this->shouldntReport($e) ) {
		        
	            return;
	            
	        }
	        
	        if ( defined('WP_DEBUG_DISPLAY') && true === WP_DEBUG_DISPLAY ) {

				wp_die( $e->getMessage() );
				
			}

        }


        /**
         * Render an exception to the console.
         *
         * @param  \Symfony\Component\Console\Output\OutputInterface $output
         * @param  \Exception                                        $e
         *
         * @return void
         */
        public function renderForConsole( $output, Exception $e ) {
	        
            echo $e->getMessage();
            
        }

    }