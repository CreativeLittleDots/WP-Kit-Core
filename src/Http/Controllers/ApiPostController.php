<?php
	
	namespace WPKit\Http\Controllers;
	
	use Exception;
	
	class ApiPostController extends ApiController {
		
		/**
	     * Where query
	     *
	     * @return Model Query
	     */
		protected function whereQuery( $query, $model ) {
				
			if( ! empty( $this->http->get('s') ) ) {
				
				$query->where( 'post_title', 'like', '%' . $this->http->get('s') . '%' );
				
				foreach($model->getMetaKeys() as $meta_key) {
					
					$query->join(
		    			'postmeta as ' . $meta_key, 
		    			function($join) use($query, $model, $meta_key) {
					        $join->on($meta_key . '.post_id', '=', 'ID');
					        $join->where($meta_key . '.meta_key', '=', $meta_key);
					    }
		    		);
					
					$query->orWhere( $meta_key . '.meta_value', 'like', '%' . $this->http->get('s') . '%' );
					
				}
				
			}
			
			if( ! empty( $this->http->get('meta_query') ) ) {
			
				foreach($this->http->get('meta_query') as $meta_query) {
					
					if( empty( $this->http->get('s') ) ) {
					
						$query->join(
			    			'postmeta as ' . $meta_query['key'], 
			    			function($join) use($query, $model, $meta_query) {
						        $join->on($meta_query['key'] . '.post_id', '=', 'ID');
						        $join->where($meta_query['key'] . '.meta_key', '=', $meta_query['key']);
						    }
			    		);
			    		
			    	}
			    	
		    		$query->where(
		    			$meta_query['key'] . '.meta_value', 
		    			! empty( $meta_query['compare'] ) ? $meta_query['compare'] : '=', 
		    			$meta_query['value']
		    		);
					
				}
				
			}
		
			return $query;
			
		}
		
	}