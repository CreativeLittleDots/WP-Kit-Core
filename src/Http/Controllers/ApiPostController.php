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
				
				foreach($model->getPublicMeta() as $meta_key) {
					
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

			if( ! empty( $this->http->get('tax_query') ) ) {
				
				$tax_queries = array_map(function($tax_query) {
					
					return ! is_array( $tax_query ) ? json_decode(stripslashes($tax_query), true) : $tax_query;
					
				}, $this->http->get('tax_query'));
				
				$tax_queries = array_filter($tax_queries, function($tax_query) {
					
					return ! empty( $tax_query['values'] ) || ! empty( $tax_query['values'] );
					
				});
				
				foreach($tax_queries as $tax_query) {
					
					$abbrev_tr = ! empty( $meta_query['taxonomy'] ) ? $meta_query['taxonomy'] : 'category';
					
					$query->join(
		    			'term_relationships as ' . $abbrev_tr, 
		    			$abbrev_tr . '.object_id', '=', 'posts.ID'
		    		);
		    		
		    		$abbrev_tt = $abbrev_tr . '_taxonomy';
		    		
		    		$query->join(
		    			'term_taxonomy as ' . $abbrev_tt, 
		    			$abbrev_tt . '.term_taxonomy_id', '=', $abbrev_tr . '.term_taxonomy_id'
		    		);
		    		
		    		$abbrev_t = $abbrev_tr . '_terms';
		    		
		    		$query->join(
		    			'terms as ' . $abbrev_t, 
		    			$abbrev_t . '.term_id', '=', $abbrev_tt . '.term_id'
		    		);
		    		
		    		$query->where($abbrev_tt . '.taxonomy', $abbrev_tr);
		    		
		    		if( ! empty( $tax_query['values'] ) ) {
		    		
			    		if( ! is_array( $tax_query['values'] ) ) {
				    	
			    			$query->whereIn( $abbrev_t . '.term_id', $tax_query['values'] );
			    			
			    		} else {
				    		
				    		$query->where( $abbrev_t . '.term_id', $tax_query['values'] );
				    		
			    		}
			    		
			    	} else {
				    	
				    	$query->where( $abbrev_t . '.term_id', $tax_query['value'] );
				    	
			    	}
					
				}
				
			}
		
			return $query;
			
		}
		
	}