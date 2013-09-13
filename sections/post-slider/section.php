<?php
/*
	Section: Post Slider
	Author: Aleksander Hansson
	Author URI: http://ahansson.com
	Description: A nice way to feature your posts in a great looking slider.
	Class Name: PostSlider
	Cloning: true
	Filter: slider
*/

class PostSlider extends PageLinesSection {

	/**
	 * Load styles and scripts
	 */
	function section_styles(){

		wp_enqueue_script( 'jquery');

		wp_enqueue_script( 'post-slider-flexslider', $this->base_url.'/js/jquery.flexslider-min.js');
	}

	function section_head(){

		$clone_id = $this->get_the_id();

		$prefix = ($clone_id != '') ? 'clone'.$clone_id : '';

		$slideshow = ($this->opt('slideshow')) ? 'true' : 'false';

		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					var theSlider = jQuery('#post_slider_<?php echo $prefix; ?>');
					theSlider.flexslider({
						namespace: "post_slider_",
						animation: 'fade',
						slideshow: <?php echo $slideshow;?>,
						directionNav: true,
						controlNav: false,
						prevText: "<i class='icon-chevron-left'></i>",
    					nextText: "<i class='icon-chevron-right'></i>",
    					pauseOnHover: true,
    					useCSS: false,
					});
					jQuery(".post_slider_prev, .post_slider_next").click(function() {
					  	return false;
					});
				});

			</script>
		<?php
	}

	/**
	* Section template.
	*/
   function section_template() {

   	$clone_id = $this->get_the_id();

	$prefix = ($clone_id != '') ? 'clone'.$clone_id : '';

	$ignore_sticky = (!$sticky) ? '1': '0';

	$orderby = ($this->opt('orderby')) ? $this->opt('orderby') : "post_date";

	$order = ($this->opt('order')) ? $this->opt('order') : "ASC";

	$posts = ($this->opt('posts')) ? $this->opt('posts') : '4';

	$category = ($this->opt('taxonomy')) ? $this->opt('taxonomy') : "";

	$button = ($this->opt('button_type')) ? $this->opt('button_type') : "";

	?>

	<div id="post_slider_<?php echo $prefix; ?>" class="post_slider">
		  	<ul class="slides">
			  	<?php

					$args = array(
						'post_type' => 'post',
						'category_name' => $category,
						'posts_per_page' => $posts,
						'orderby' => $orderby,
						'order'=> $order,
					);

					$loop = new WP_Query( $args );

					$output = '';

					if ( $loop->have_posts() ) {

						while ( $loop->have_posts() ) : $loop->the_post();

							$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large');

							?>

								<li style="background-image:url('<?php echo $image[0]; ?>');">
									<div class="overlay">
										<div class="txt">
											<div class="post_slider_title-container">
												<h2 class="post_slider_title">
													<?php echo get_the_title(); ?>
												</h2>
											</div>
											<div class="btn-container">
												<a class="btn <?php echo $button; ?> btn-large" href="<?php echo the_permalink(); ?>"><?php echo __('Read more &rarr;','post-slider'); ?></a>
											</div>
										</div>
									</div>
								</li>

							<?php

						endwhile;

					}

					if($output == ''){
						$this->do_defaults();
					} else {
						echo $output;
					}

				?>
		  	</ul>
	</div>

		<?php
	}

	function do_defaults(){

		?>
			<p class="no-posts"><?php __('There is no Posts to show!', 'post-slider'); ?></p>
		<?php

	}

	function section_opts() {

		$options = array();

		$options[] = array(

			'title' => __( 'Slides options', 'post-slider' ),
			'type'	=> 'multi',
			'opts'	=> array(

				array(
					'key'			=> 'posts',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 20,
					'default'		=> '4',
					'label' 		=> __( 'Number of Posts to show', 'post-slider' ),
					'help' 			=> __( 'Enter the number of Post Slider Posts. <strong>Default is 4</strong>', 'post-slider' ),
				),

	    	    array(
	    	    	'key'			=> 'taxonomy',
					'type' 			=> 'select_taxonomy',
					'post_type'		=> 'post',
					'label' 		=> __( 'Category to show?', 'post-slider' ),
					'help' 			=> __( 'Select the category you want to show', 'post-slider' ),

				),

				 array(
					'key'			=> 'orderby',
					'label' 		=> 'Order According To (Default: Post Date)',
					'type'			=> 'select',
					'default'		=> 'post_date',
					'opts' 			=> array(
						'post_date' 	=> array( 'name' => "Using Post Date" ),
						'title'   		=> array( 'name' => "Using Post Title" ),
						'rand'   		=> array( 'name' => "Random Selection" ),
						'ID'   			=> array( 'name' => "Using Post ID" ),
					),
				),

				array(
					'key'			=> 'order',
					'label' 		=> 'Order Type (Default: ASC)',
					'type' 			=> 'select',
					'default'		=> 'ASC',
					'opts' 			=> array(
						'ASC'   		=> array( 'name' => "Ascending Order" ),
						'DESC'   		=> array( 'name' => "Descending Order" ),
					),
				),
			)
		);

		$options[] = array(
			'title'     			=>  __('Slider options', 'post-slider'),
			'type'     				=> 'multi',
			'opts'   				=> array(
				array(
					'key'			=> 'slideshow',
					'type' 			=> 'check',
					'label' 		=> __( 'Animate Slideshow Automatically?', 'post-slider' ),
					'help' 			=> __( 'Autoplay the slides, transitioning every 7 seconds.', 'post-slider' ),
				),
			)
		);

		$options[] = array(
	    	'key' 			=> 'button_type',
			'label' 		=> __('Button type', 'post-slider'),
			'type' 			=> 'select_button',
			'default'		=> 'btn-primary',
			'help'			=> __('Choose the type of button you want.', 'post-slider' )
		);

		return $options;
	}

}