<?php
    
    namespace App\Models;
    
    use WPKit\Classes\Model;
    
    class Career extends Model {
        
        var $days_ago;
        var $end;
        
        public function populate( $post ) {
            
            $this->days_ago = round( ( date('U') - get_the_time('U', $this->id) ) / ( 60 * 60 * 24 ) );
            $this->end = round( ( date( 'U', strtotime( get_field('career_closing_date', $this->id) ) ) - get_the_time('U', $this->id) ) / ( 60 * 60 * 24 ) );
            
        }
        
        public function get_days_ago() {
            
        	if ( $this->days_ago == 0 ) {
            	
        		return 'Posted Today';
        		
        	} elseif ( $this->days_ago == 1 ) {
            	
        		return'Posted Yesterday';
        		
        	} else {
            	
        		return 'Posted ' . $this->days_ago . ' days ago';
        		
        	}
            
        }
        
        public function get_categories($fields = 'all') {
            
            return array_filter(wp_get_object_terms($this->id, 'career_category', ['fields' => $fields]), function($term) {
               
               return ( is_object( $term ) && $term->name != 'Featured' ) || ( is_string( $term ) && $term !== 'Featured' );
                
            });
            
        }
        
        public function get_colour() {
            
            $categories = $this->get_categories();
            
            return get_field( 'career_colour', reset( $categories ) );
            
        }
        
        public function get_ages() {
            
            $ages = [];
            
            if( get_field('career_promoted', $this->id) == true ) {
                
                $ages[] = 'Promoted';
                
            }
            
            if( $this->days_ago <= get_field('careers_startend_threshold', 'options') ) {
                
                $ages[] = 'New';
                
            }
            
            else if( $this->days_ago <= $this->end ) {
                
                $ages[] = 'Ending Soon';
                
            }
            
            return $ages;
            
        }
        
    }