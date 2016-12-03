<?php
    
    namespace App\Models;
    
    use WPKit\Models\Post;
    
    class Career extends Post {
	    
	    /**
	     * The post_type associated with the model.
	     *
	     * @var string
	     */
	    protected $post_type = 'career';
	    
	    /**
	     * The appends attributes that are mass assignable.
	     *
	     * @var array
	     */
		protected $appends = [
			'days_ago',
			'end'
		];
		
		/**
	     * Get Days Ago Attribute
	     *
	     * @var string
	     */
		public function getDaysAgoAttribute() {
			
			return round( ( date('U') - get_the_time('U', $this->ID) ) / ( 60 * 60 * 24 ) );
			
		}
		
		/**
	     * Get End Attribute
	     *
	     * @var string
	     */
		public function getEndAttribute() {
			
			return round( ( date( 'U', strtotime( get_field('career_closing_date', $this->ID) ) ) - date('U') ) / ( 60 * 60 * 24 ) );
			
		}
        
        public function getDaysAgo() {
            
        	if ( $this->days_ago == 0 ) {
            	
        		return 'Posted Today';
        		
        	} elseif ( $this->days_ago == 1 ) {
            	
        		return'Posted Yesterday';
        		
        	} else {
            	
        		return 'Posted ' . $this->days_ago . ' days ago';
        		
        	}
            
        }
        
        public function getCategories($fields = 'all') {
            
            return wp_get_object_terms($this->ID, 'career_category', ['fields' => $fields]);
            
        }
        
         public function getLocations($fields = 'all') {
            
            return wp_get_object_terms($this->ID, 'career_location', ['fields' => $fields]);
            
        }
        
        public function getColour() {
            
            $categories = $this->getCategories();
            
            return get_field( 'career_colour', reset( $categories ) );
            
        }
        
        public function getAges() {
                        
            $ages = [];
            
            if( get_field('career_promoted', $this->ID) == true ) {
                
                $ages[] = 'Promoted';
                
            }
            
            if( $this->days_ago <= get_field('careers_startend_threshold', 'options') ) {
                
                $ages[] = 'New';
                
            }
            
            else if( $this->end > 0 && ( ( $this->end - $this->days_ago ) < get_field('careers_startend_threshold', 'options') ) ) {
                
                $ages[] = 'Ending Soon';
                
            }
            
            else if( $this->end < 0 && $this->end > -10000 ) {

                $ages[] = 'Ended';
                
            }
            
            return $ages;
            
        }
        
        public function getDistance() {
            
            return property_exists($this->post, 'distance') ? $this->post->distance : false;
            
        }
        
        public function getClosingDate() {
	        
	        $date = get_field('career_closing_date', $this->ID);
	        
	        return $date ? date('j F Y', strtotime($date)) : '';
	        
        }
        
    }