<?php
	
	namespace WPKit\Http\Controllers\Api;
	
	use Exception;
	
	class PostController extends Controller {
		
		/**
	     * Save an post
	     *
	     * @param  int  $id
	     * @return Model
	     */
		protected function saveEntity( $id = null ) {
			
			$model = $this->getEntity( $id );
			
			$this->validateParams( $this->http->all(), $id ? false : true );
			
			$model->fill( $this->http->except( $model->getMagicMeta() ) )->save();
			
			foreach($this->http->all() as $key => $value) {
				
				if( ! in_array( $key, $model->getMagicMeta() ) ) {
					
					continue;
					
				}
				
				$model->updateMetaValue( $model->getMagicMetaKey( $key ), $value );
				
			}
			
			return $model;
			
		}
		
		/**
	     * Validate Params
	     *
	     * @param  array  $params
	     * @return array
	     */
		protected function validateParams( $params = array(), $creating = false ) {
			
			if( $creating ) {
				
				if( empty( $params['title'] ) ) {
				
					throw new Exception('Please provide a title');
					
				}
				
				$params['post_title'] = $params['title'];
				
			}
			
			return $params;
			
		}
		
		/**
	     * Where query
	     *
	     * @return Model Query
	     */
		protected function whereQuery( $query, $model ) {
			
			$query->select( 'posts.*' );
				
			if( ! empty( $this->http->get('s') ) ) {
				
				$query->where( 'post_title', 'like', '%' . $this->http->get('s') . '%' );
				
				foreach($model->getMagicMeta() as $meta_key => $key) {
					
					$query->join(
		    			'postmeta as ' . $key, 
		    			function($join) use($key, $meta_key) {
					        $join->on($key . '.post_id', '=', 'ID');
					        $join->where($key . '.meta_key', '=', $meta_key);
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
			
			if( $magic_meta = array_filter( $this->http->only( $model->getMagicMeta() ) ) ) {
				
				foreach($magic_meta as $key => $meta) {
					
					$meta = is_array($meta) ? $meta : [
						'compare' => '=',
						'value' => $meta
					];
					
					if( empty( $this->http->get('s') ) ) {
						
						$meta_key = $model->getMagicMetaKey( $key );
					
						$query->join(
			    			'postmeta as ' . $key, 
			    			function($join) use($key, $meta_key) {
						        $join->on($key . '.post_id', '=', 'ID');
						        $join->where($key . '.meta_key', '=', $meta_key);
						    }
			    		);
			    		
			    	}
			    	
		    		$query->where(
		    			$key . '.meta_value', 
		    			! empty( $meta['compare'] ) ? $meta['compare'] : '=', 
		    			$meta['value']
		    		);
					
				}
				
			}
		
			return $query;
			
		}
		
	}