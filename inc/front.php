<?php /*

Genesis Coming Soon
https://qosmicro.com/plugins/coming-soon-for-genesis
 _____     _____ _____ _             
|     |___|   __|     |_|___ ___ ___ 
|  |  | . |__   | | | | |  _|  _| . |
|__  _|___|_____|_|_|_|_|___|_| |___|
   |__|                              

================================================================== */


//* Registers a new admin page for the Genesis Coming Soon plugin.
class Genesis_Coming_Soon_Front {

	
	private $options = array();


	// Displays the Comming Soon Page
	public function __construct() {
		
		$this->options = stripslashes_deep( get_option( GCS_SETTINGS ) );
		
		if( 'disabled' == $this->options['status'] &&
			!isset($_GET['gcs_preview']) ) return;

		#Redirect to Custom Page
		add_action( 'template_redirect', array( $this, 'redirect_page' ) );

		#Disable Toolbar
		show_admin_bar(false);
		
		#If Using Custom Page... Do Nothing More
		if( $this->options['pageid'] > 0 ) return;

		#Add page body/html class
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
		add_filter( 'language_attributes', array( $this, 'add_html_class' ) );

		#Disable Unnecessary Actions/Features
		$this->clean_theme();
		
		#Send Headers
		if( 'maintenance' == $this->options['status'] ) {
			header('HTTP/1.1 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
			header('Retry-After: 86400');
		}

		#Replace Content
		$this->find_and_remove( 'genesis_do_loop' );
		add_action( 'genesis_loop', array( $this, 'coming_soon_content' ) );

	}

	
	// Redirects to Custom PAge if Necessary
	public function redirect_page() {
		global $post;
		$op_pageid = (integer) $this->options['pageid'];
		if( !is_home() &&
			!is_front_page() &&
			$post->ID != $op_pageid ) {
			$url = get_permalink( $op_pageid );
			if( !$url || !$op_pageid ) $url = home_url();
			if( isset($_GET['gcs_preview']) ) {
				if( strpos( $url, '?' ) !== false ) $url .= '&gcs_preview=1';
				else $url .= '?gcs_preview=1';
			}
			wp_safe_redirect( $url, ($this->options['status']!='maintenance')?302:503 );
			exit();
		}
	}


	// Adds GCS Body Class
	public function add_body_class( $classes ) {
		$classes[] = 'gcs-custom-body';
		return $classes;
	}


	// Adds GCS HTML Class
	public function add_html_class( $output ) {
		return $output . ' class="gcs-custom-html"';
	}


	// Scripts & Fonts
	public function scripts_and_fonts() {
		// Dequeue Skip Links
		wp_dequeue_script( 'skip-links' );
		// Add Fonts if Needed
		if( $this->options['fonts'] ) {
			switch( $this->options['fonts'] ) {
				case 1:
					wp_enqueue_style( 'gcs-libre-baskerville', '//fonts.googleapis.com/css?family=Libre+Baskerville:400i' ); 
					wp_enqueue_style( 'gcs-ubuntu', '//fonts.googleapis.com/css?family=Ubuntu:400,400i' ); 
					break;
				case 2:
					wp_enqueue_style( 'gcs-bree-serif', '//fonts.googleapis.com/css?family=Bree+Serif' ); 
					wp_enqueue_style( 'gcs-imprima', '//fonts.googleapis.com/css?family=Imprima' ); 
					break;
				case 3:
					wp_enqueue_style( 'gcs-montserrat', '//fonts.googleapis.com/css?family=Montserrat' ); 
					wp_enqueue_style( 'gcs-pontano-sans', '//fonts.googleapis.com/css?family=Pontano+Sans' ); 
					break;
				case 4:
					wp_enqueue_style( 'gcs-oswald', '//fonts.googleapis.com/css?family=Oswald' ); 
					wp_enqueue_style( 'gcs-muli', '//fonts.googleapis.com/css?family=Muli' ); 
					break;
				case 5:
					wp_enqueue_style( 'gcs-pt-serif', '//fonts.googleapis.com/css?family=PT+Serif' ); 
					wp_enqueue_style( 'gcs-pt-sans', '//fonts.googleapis.com/css?family=PT+Sans' ); 
					break;
				case 6:
					wp_enqueue_style( 'gcs-vollkorn', '//fonts.googleapis.com/css?family=Vollkorn:700' ); 
					wp_enqueue_style( 'gcs-raleway', '//fonts.googleapis.com/css?family=Raleway:300,300i' ); 
					break;
				case 7:
					wp_enqueue_style( 'gcs-roboto', '//fonts.googleapis.com/css?family=Roboto:300,300i,700' ); 
					break;
				case 8:
					wp_enqueue_style( 'gcs-josefin-slab', '//fonts.googleapis.com/css?family=Josefin+Slab' ); 
					wp_enqueue_style( 'gcs-maven-pro', '//fonts.googleapis.com/css?family=Maven+Pro' ); 
					break;
				case 9:
					wp_enqueue_style( 'gcs-oxygen', '//fonts.googleapis.com/css?family=Oxygen' ); 
					wp_enqueue_style( 'gcs-source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i' ); 
					break;
				case 10:
					wp_enqueue_style( 'gcs-paytone-one', '//fonts.googleapis.com/css?family=Paytone+One' ); 
					wp_enqueue_style( 'gcs-droid-sans', '//fonts.googleapis.com/css?family=Droid+Sans' ); 
					break;
			}
		}
	}


	// Display Custom Content
	public function coming_soon_content() {
		?>
		<div id="gcs-custom-imagebg"></div>
		<?php if( $this->options['bgdim']!='' ) : ?>
		<div id="gcs-custom-imageover"></div>
		<?php endif; ?>
		<div id="gcs-custom-content">
			<?php if( $this->options['logo']!='' ) : ?>
				<img id="gcs-custom-logo" src="<?php echo $this->options['logo']; ?>" alt="<?php echo $this->options['headline']; ?>">
			<?php endif; ?>
			<?php if( $this->options['headline']!='' ) : ?>
				<h1 id="gcs-custom-headline"><?php echo $this->options['headline']; ?></h1>
			<?php endif; ?>
			<?php if( $this->options['message']!='' ) : ?>
				<div id="gcs-custom-message"><?php echo $this->options['message']; ?></div>
			<?php endif; ?>
		</div>
		<style type="text/css">
			#gcs-custom-imagebg {
				<?php if( $this->options['bgimage']!='' ) : ?>
				background: <?php echo $this->options['bgcolor']; ?> url('<?php echo $this->options['bgimage']; ?>') <?php echo $this->options['repeatbg']; ?> <?php echo $this->options['positionbg']; ?>;
				<?php else : ?>
				background: <?php echo $this->options['bgcolor']; ?>;
				<?php endif; ?>
				<?php if( $this->options['responsive']!='' ) : ?>
				background-size: cover !important;
				<?php endif; ?>
			}
			#gcs-custom-imageover {
				<?php if( $this->options['bgdim']!='' ) : ?>
				background: rgba(0, 0, 0, 0.5);
				<?php endif; ?>
			}
			#gcs-custom-headline {
				color: <?php echo $this->options['titlecolor']; ?>;
				<?php if( $this->options['fonts'] ) : ?>
					<?php switch( $this->options['fonts'] ) {
						case 1: ?>
							font-style: italic;
							font-family: 'Libre Baskerville', serif; 
							<?php break;
						case 2: ?>
							font-family: 'Bree Serif', serif;
							<?php break;
						case 3: ?>
							text-transform: uppercase;
							font-family: 'Montserrat', sans-serif; 
							<?php break;
						case 4: ?>
							text-transform: uppercase;
							font-family: 'Oswald', sans-serif; 
							<?php break;
						case 5: ?>
							font-family: 'PT Serif', serif;
							<?php break;
						case 6: ?>
							font-weight: 700;
							font-family: 'Vollkorn', serif; 
							<?php break;
						case 7: ?>
							font-weight: 700;
							font-family: 'Roboto', sans-serif; 
							<?php break;
						case 8: ?>
							text-transform: uppercase;
							font-family: 'Josefin Slab', serif; 
							<?php break;
						case 9: ?>
							text-transform: uppercase;
							font-family: 'Oxygen', sans-serif; 
							<?php break;
						case 10: ?>
							font-family: 'Paytone One', sans-serif;
							<?php break;
					} ?>
				<?php endif; ?>
			}
			#gcs-custom-content {
				width: 100%;
				color: <?php echo $this->options['textcolor']; ?>;
				max-width: <?php echo $this->options['maxwidth']; ?>px;
				<?php
				$boxposition = explode(' ', $this->options['position']);
				if( $boxposition[0] == $boxposition[1] ) : ?>
					top: 50%;
					left: 50%;
					-webkit-transform: translate(-50%, -50%);
					-moz-transform: translate(-50%, -50%);
					-ms-transform: translate(-50%, -50%);
					transform: translate(-50%, -50%);
				<?php else :
					if( 'left' == $boxposition[0] ) echo 'left: 0;';
					else if( 'right' == $boxposition[0] ) echo 'right: 0;';
					else if( 'center' == $boxposition[0] ) : ?>
						left: 50%;
						-webkit-transform: translateX(-50%);
						-moz-transform: translateX(-50%);
						-ms-transform: translateX(-50%);
						transform: translateX(-50%);
					<?php endif;
					if( 'top' == $boxposition[1] ) echo 'top: 0;';
					else if( 'bottom' == $boxposition[1] ) echo 'bottom: 0;';
					else if( 'center' == $boxposition[1] ) : ?>
						top: 50%;
						-webkit-transform: translateY(-50%);
						-moz-transform: translateY(-50%);
						-ms-transform: translateY(-50%);
						transform: translateY(-50%);
					<?php endif;
				endif;
				?>
				<?php if( $this->options['messagedim']!='' ) : ?>
				background: <?php echo $this->options['boxcolor']; ?>;
				<?php endif; ?>
				<?php if( $this->options['fonts'] ) : ?>
					font-weight: normal;
					<?php switch( $this->options['fonts'] ) {
						case 1: ?>
							font-family: 'Ubuntu', sans-serif; 
							<?php break;
						case 2: ?>
							font-family: 'Imprima', sans-serif;
							<?php break;
						case 3: ?>
							font-family: 'Pontano Sans', sans-serif;
							<?php break;
						case 4: ?>
							font-family: 'Muli', sans-serif;
							<?php break;
						case 5: ?>
							font-family: 'PT Sans', sans-serif;
							<?php break;
						case 6: ?>
							font-family: 'Raleway', sans-serif; 
							<?php break;
						case 7: ?>
							font-weight: 300;
							font-family: 'Roboto', sans-serif; 
							<?php break;
						case 8: ?>
							font-family: 'Maven Pro', sans-serif; 
							<?php break;
						case 9: ?>
							font-family: 'Source Sans Pro', sans-serif;
							<?php break;
						case 10: ?>
							font-family: 'Droid Sans', sans-serif;
							<?php break;
					} ?>
				<?php endif; ?>
			}
			#gcs-custom-content a {
				color: <?php echo $this->options['linkcolor']; ?>;
				<?php if( $this->options['fonts'] ) : ?>
					font-weight: normal;
					<?php switch( $this->options['fonts'] ) {
						case 1: ?>
							font-family: 'Ubuntu', sans-serif; 
							<?php break;
						case 2: ?>
							font-family: 'Imprima', sans-serif;
							<?php break;
						case 3: ?>
							font-family: 'Pontano Sans', sans-serif;
							<?php break;
						case 4: ?>
							font-family: 'Muli', sans-serif;
							<?php break;
						case 5: ?>
							font-family: 'PT Sans', sans-serif;
							<?php break;
						case 6: ?>
							font-family: 'Raleway', sans-serif; 
							<?php break;
						case 7: ?>
							font-weight: 300;
							font-family: 'Roboto', sans-serif; 
							<?php break;
						case 8: ?>
							font-family: 'Maven Pro', sans-serif; 
							<?php break;
						case 9: ?>
							font-family: 'Source Sans Pro', sans-serif;
							<?php break;
						case 10: ?>
							font-family: 'Droid Sans', sans-serif;
							<?php break;
					} ?>
				<?php endif; ?>
			}
			<?php echo $this->options['customcss']; ?>
		</style>
		<?php
	}


	// Removes Unnecessary Content
	protected function clean_theme() {

		#Remove Menus
		remove_theme_support( 'genesis-menus' );

		#Remove Footer Widgets
		remove_theme_support( 'genesis-footer-widgets' );

		#Remove Skip Links, Breadcrumbs & Comments
		$this->find_and_remove( 'genesis_skip_links' );
		$this->find_and_remove( 'genesis_do_breadcrumbs' );
		$this->find_and_remove( 'genesis_get_comments_template' );

		#Remove Header
		$this->find_and_remove( 'genesis_header', 'all' );
		$this->find_and_remove( 'genesis_do_header', 'all' );
		$this->find_and_remove( 'genesis_after_header', 'all' );
		$this->find_and_remove( 'genesis_before_header', 'all' );

		#Remove Footer
		$this->find_and_remove( 'genesis_footer', 'all' );
		$this->find_and_remove( 'genesis_do_footer', 'all' );
		$this->find_and_remove( 'genesis_after_footer', 'all' );
		$this->find_and_remove( 'genesis_before_footer', 'all' );

		#Remove Custom Functions
		if( '' != $this->options['customfun'] ) {
			$ignore_functions = explode( ',', $this->options['customfun'] );
			foreach( $ignore_functions as $function ) $this->find_and_remove( $function );
		}

		#Force full width content layout
		add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

		#Scripts & Fonts
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_fonts' ) );

	}


	// Finds & Remoces Actions
	protected function find_and_remove( $remove = '', $filter = '' ) {
		global $wp_filter;
		echo '<!-- ';
		var_dump($wp_filter);
		echo ' -->';
		foreach( $wp_filter as $tag => $actions ) 
			foreach( $actions as $priority => $functions )
				foreach( $functions as $function => $data )
					if( $remove == $function || 
					  ( $remove == $tag && 'all' == $filter )) remove_action( $tag, $function, $priority );
	}

}


//* Instantiate the class to show the frontend
add_action( 'wp', 'comingsoon_front' );
function comingsoon_front() {
	global $_genesis_coming_soon_front;
	$_genesis_coming_soon_front = new Genesis_Coming_Soon_Front();
}




























/* --- end */