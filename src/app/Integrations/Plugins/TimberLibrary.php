<?php
	
	namespace WPKit\Integrations\Plugins;
	
	use WPKit\Integrations\Integration;
	use Timber;

	class TimberLibrary extends Integration {
		
		public function startIntegration( $settings = array() ) {
			
			if( ! class_exists('Timber') ) {
				
				return;
				
			}
	
			Timber::$locations = array( VIEWS_DIR, COMPONENTS_DIR );
			
			if( ! empty($settings['context']) && is_array($settings['context']) && count($settings['context']) > 0 ) {
				
				add_filter( 'timber_context', function ( $context ) use($settings) {
					
					$context = $context + $settings['context'];
					
					return $context;
					
				});
				
			}
			
			if( ! empty($settings['twigs']) && is_array($settings['twigs']) && count($settings['twigs']) > 0 ) {
			
				foreach($settings['twigs'] as $twig) {
					
					if( is_string($twig) && function_exists($twig) ) {
						
						add_action( 'get_twig', function ( $twiglet ) use ( $twig ) {
							
							$twiglet->addExtension( new Twig_Extension_StringLoader() );
							
							$twiglet->addFilter($twig, new Twig_SimpleFilter($twig, $twig));
							
							return $twiglet;
							
						} );
						
					}
					
				}	
				
			}
            
		}
		
	}
