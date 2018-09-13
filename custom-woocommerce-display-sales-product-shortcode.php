//register scripts
wp_register_script( 'slick-min-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.js', true );

// Add Shortcode
function custom_woocommerce_products_ticker_func( $atts ) {
        global $woocommerce_loop, $woocommerce;
        
	// Attributes
	extract( $atts = shortcode_atts(
		array(
			'title' => 'Sales',
			'posts_per_page' => '4',
			'orderby'       => 'title',
	                'order'         => 'asc'
		),
		$atts )
	);
	
	        // Get products on sale
		$product_ids_on_sale = woocommerce_get_product_ids_on_sale();
		$meta_query = array();
		$meta_query[] = $woocommerce->query->visibility_meta_query();
	        $meta_query[] = $woocommerce->query->stock_status_meta_query();
	
	
	$args = array(
	        'post_status' 	=> 'publish',
		'post_type' => 'product',
		'posts_per_page' => $posts_per_page,
		'order' => $order,
		'orderby' => $orderby,
		'no_found_rows' => 1,
		'meta_query' => $meta_query,
		'post__in' => $product_ids_on_sale
			
	);
	
	
	ob_start();
	        $products = new WP_Query( $args );
	        
		if ( $products->have_posts() ) : ?>
		             <style>
		             .slick-title{
		             background:#fa4444;
		             color:#fff;
		             text-transform:uppercase;
		             -webkit-clip-path: polygon(0px 22px,77px 22px,90px 0,0px 0);
                             clip-path: polygon(0px 22px,77px 22px,90px 0,0px 0);
   			     margin-top: -1px;}
                            
		             </style>
		   
		        <div id="slick-container" class="row" style="position:absolute;">
		             
		           <div class="col-md-2 slick-title" style="padding:2px 15px 2px 19px;">
                             <?php echo esc_attr($atts['title']);  ?>
                           </div>
                             
                           <div class="col-md-10" style="padding:0px 2px 0px 2px;">
                            <div class="custom-ticker"> 
                              <?php while ( $products->have_posts() ) : $products->the_post(); ?>
				    <p class="slick-slide"><a href="<?php echo get_permalink(); ?>" title="<?php echo the_title(); ?>"><?php 
				    $title_string = strlen(get_the_title());
				    echo substr(the_title('', '', FALSE), 0, 50); 
					if ( $title_string > 50 ) {
						echo '... ' ;
					} else { 
						echo ' ';
					} 
				    
				    ?></a></p> 
				<?php endwhile; // end of the loop. ?>
			    </div>
			   </div>
		       </div>
			     
                                <script>
				 jQuery(function ($) {
				 $('.custom-ticker').slick({
				  vertical: true,
				  autoplay: true,
				  autoplaySpeed: 3000,
				  speed: 300, 
				  arrows: false
				});
					
				
				
				});
				
				 </script>
			

		<?php endif;
		wp_reset_postdata();
		return ob_get_clean();

}
add_shortcode( 'custom_woocommerce_products_ticker', 'custom_woocommerce_products_ticker_func' );