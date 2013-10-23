<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */


$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

<aside id="tags"><?php _e('NAVEGACION HOLISTA'); ?>
<?php
$redcloud = '';
if (get_curr_tags()!='') {
	$redargs = "smallest=10&largest=10&format=list&orderby=count&order=DESC&echo&semantics=del&include=".get_curr_tags();
	$redcloud = my_wp_tag_cloud($redargs);
}

 
?>
			<br>
			<div id="hologrammar" style="color: red;"> 
			<br> RESTRICCIONES ACTIVADAS: <br> <?php echo $redcloud; ?>
			=  POSTS ACTIVOS  = 
			<?php
			$greenargs = "echo&semantics=add&exclude=".get_curr_tags();
			$greencloud = my_wp_tag_cloud($greenargs);
			?>
			__________________________

			</div>
			<div id="hologrammar" style="color: green;">
			<br> ETIQUETAS INACTIVAS: <br> <?php echo $greencloud; ?>
			__________________________
			<br>

			</div>
			<br>
 </aside>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->
<?php endif; ?>