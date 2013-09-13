<?php
/*
Template Name: Cloud
*/
?>
<?php
/**
* Cloud template
*
* For cloud template data..
*
* @package ObserWeb
* @subpackage Cloud
* @since Cloud 0.1
*/


function get_curr_tags_array(){
	if (get_query_var('tags') == '')
		return array();
  
	return explode(",", get_query_var('tags'));
}

function get_curr_tags() {
	return get_query_var('tags');
}

function my_wp_tag_cloud( $args = '' ) {
	$pagelink = get_page_link();
	//$currtags = get_curr_tags();
	$currtags_array = get_curr_tags_array();
  
	$defaults = array(
		'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
		'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
		'exclude' => '', 'include' => '', 'taxonomy' => 'post_tag', 'echo' => true,
		'semantics' => 'add'
	);
	$args = wp_parse_args( $args, $defaults );
	
	$tags = get_terms( $args['taxonomy'], array_merge( $args, array( 'orderby' => 'count', 'order' => 'DESC' ) ) ); // Always query top tags
	
	if ( empty( $tags ) || is_wp_error( $tags ) )
		return;
	
	foreach ( $tags as $key => $tag ) {
		if ( 'del' == $args['semantics'] ) {
			$tagarr = $currtags_array;
			$dkey = array_search($tag->term_id, $tagarr);
			if ($dkey!== NULL) {
				unset($tagarr[$dkey]);
			}
                            
			$tagvar = implode(",", $tagarr);
			$link = add_query_arg( "tags", $tagvar, $pagelink );
		}
		else {
			$tagarr = array_merge($currtags_array, array($tag->term_id));
			$tagvar = implode(",", $tagarr);
			$link = add_query_arg( "tags", $tagvar, $pagelink );
		}
		// if dont
		//$link = "?".$query_string."&tag=".$tag->term_id;
                  
		if ( is_wp_error( $link ) )
			return false;
	
		$tags[ $key ]->link = $link;
		$tags[ $key ]->id = $tag->term_id;
	}
	
	$return = wp_generate_tag_cloud( $tags, $args ); // Here's where those top tags get sorted according to $args
	
	$return = apply_filters( 'wp_tag_cloud', $return, $args );
	
	if ( 'array' == $args['format'] || empty($args['echo']) )
		return $return;
	
	echo $return;
}


get_header(); 

$blueargs = "echo&semantics=add&exclude=".get_curr_tags();
$bluecloud = my_wp_tag_cloud($blueargs); 

$redcloud = '';
if (get_curr_tags()!='') {
	$redargs = "echo&semantics=del&include=".get_curr_tags();
	$redcloud = my_wp_tag_cloud($redargs); 
}

//echo $query_string;
$the_query = new WP_Query( array( 'tag__and' => get_curr_tags_array() ) );

// query_posts(''); 
?>
<div id="redcloud" style="color: red;">
	RED CLOUD
<?php echo $redcloud; ?>	
</div>

<div id="bluecloud" style="color: blue;"> BLUE CLOUD
<?php echo $bluecloud; ?>		
</div>


<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">
		<?php if ( $the_query->have_posts() ) : ?>

			<?php /* The loop */ ?>
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php twentythirteen_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

	</div><!-- #content -->
</div><!-- #primary -->

<?php wp_reset_postdata(); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>