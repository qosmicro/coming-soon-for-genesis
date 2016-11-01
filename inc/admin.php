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
class Genesis_Coming_Soon_Admin extends Genesis_Admin_Boxes {


	// Override Function that Constructs ID Attributes
	protected function get_field_id( $id ) {
		return sprintf( '%s_%s', $this->settings_field, $id );
	}


	// Create an admin menu item and settings page.
	public function __construct() {

		$page_id  = 'genesis-coming-soon';

		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis - Coming Soon Page', 'coming-soon-for-genesis' ),
				'menu_title'  => __( 'Coming Soon Page', 'coming-soon-for-genesis' ),
				'capability'  => 'manage_options',
			)
		);

		$page_ops = array();

		$settings_field = GCS_SETTINGS;

		$default_settings = array(
			'status'     => 'disabled',
			'pageid'     => '0',
			'logo'       => '',
			'headline'   => get_bloginfo('name'),
			'message'    => '<p>'.get_bloginfo('description').'</p>',
			'bgcolor'    => '#f5f6fa',
			'bgimage'    => '',
			'bgdim'      => '0',
			'messagedim' => '0',
			'responsive' => '1',
			'repeatbg'   => 'no-repeat',
			'positionbg' => 'center top',
			'position'   => 'center center',
			'maxwidth'   => '500',
			'titlecolor' => '#000000',
			'textcolor'  => '#333333',
			'linkcolor'  => '#0000ee',
			'fonts'      => '0',
			'boxcolor'   => 'none',
			'customcss'  => '',
			'customfun'  => '',
		);

		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitizer_filters' ) );

	}


	// Initialize the Sanitization Filter
	public function sanitizer_filters() {

		genesis_add_option_filter(
			'one_zero',
			$this->settings_field,
			array(
				'bgdim',
				'responsive',
				'messagedim',
			)
		);

		genesis_add_option_filter(
			'no_html',
			$this->settings_field,
			array(
				'status',
				'headline',
				'bgcolor',
				'repeatbg',
				'positionbg',
				'position',
				'textcolor',
				'linkcolor',
				'boxcolor',
				'customcss',
				'customfun',
			)
		);

		genesis_add_option_filter(
			'absint',
			$this->settings_field,
			array(
				'fonts',
				'pageid',
				'maxwidth',
			)
		);

		genesis_add_option_filter(
			'requires_unfiltered_html',
			$this->settings_field,
			array(
				'message',
			)
		);

		genesis_add_option_filter(
			'url',
			$this->settings_field,
			array(
				'logo',
				'bgimage',
			)
		);

	}


	// Register Meta Boxes
	function metaboxes() {
		add_meta_box( 'genesis-coming-soon-general', __( 'General', 'coming-soon-for-genesis' ), array( $this, 'general_box' ), $this->pagehook, 'main', 'high' );
		add_meta_box( 'genesis-coming-soon-content', __( 'Content', 'coming-soon-for-genesis' ), array( $this, 'content_box' ), $this->pagehook, 'main', 'default' );
		add_meta_box( 'genesis-coming-soon-background-design', __( 'Background Design', 'coming-soon-for-genesis' ), array( $this, 'background_design_box' ), $this->pagehook, 'main', 'default' );
		add_meta_box( 'genesis-coming-soon-content-design', __( 'Content Design', 'coming-soon-for-genesis' ), array( $this, 'content_design_box' ), $this->pagehook, 'main', 'default' );
		add_meta_box( 'genesis-coming-soon-extras', __( 'Extras', 'coming-soon-for-genesis' ), array( $this, 'extras_box' ), $this->pagehook, 'main', 'default' );
	}


	// Callback for General meta box
	function general_box() {

		?>
		<table class="form-table genesis-coming-soon">
		<tbody>
			
			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Status', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><p><?php _e( 'Status', 'coming-soon-for-genesis' ); ?></p></legend>
						<p>
							<input type="radio" name="<?php $this->field_name('status'); ?>" id="<?php $this->field_id('status'); ?>_disabled" value="disabled"<?php echo checked( $this->get_field_value('status'), 'disabled' ); ?>>
							<label for="<?php $this->field_id('status'); ?>_disabled"><?php _e( 'Disabled', 'coming-soon-for-genesis' ); ?></label>
							<br>
							<input type="radio" name="<?php $this->field_name('status'); ?>" id="<?php $this->field_id('status'); ?>_coming_soon" value="coming_soon"<?php echo checked( $this->get_field_value('status'), 'coming_soon' ); ?>>
							<label for="<?php $this->field_id('status'); ?>_coming_soon"><?php _e( 'Enable Coming Soon Mode', 'coming-soon-for-genesis' ); ?></label>
							<br>
							<input type="radio" name="<?php $this->field_name('status'); ?>" id="<?php $this->field_id('status'); ?>_maintenance" value="maintenance"<?php echo checked( $this->get_field_value('status'), 'maintenance' ); ?>>
							<label for="<?php $this->field_id('status'); ?>_maintenance"><?php _e( 'Enable Maintenance Mode', 'coming-soon-for-genesis' ); ?></label>
						</p>
						<p>
							<span class="description">
								<?php _e( 'When you are logged in you will see your normal website.', 'coming-soon-for-genesis' ); ?><br>
								<?php printf( 
										wp_kses( __( 'Logged out visitors will <a href="%s" target="_blank">see the Coming Soon or Maintenance page</a>.', 'coming-soon-for-genesis' ), 
												 array(  'a' => array( 'href' => array(), 'target' => array( '_blank' ) ) ) ), 
												 esc_url( home_url('/') . '?gcs_preview=true' ) ); ?>
							</span>
						</p>
					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Page to Show', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('pageid'); ?>" class="screen-reader-text"><?php _e( 'Page to Show', 'coming-soon-for-genesis' ); ?></label>
						<select name="<?php $this->field_name('pageid'); ?>" id="<?php $this->field_id('pageid'); ?>" class="postform">
							<option value="0"<?php selected( $this->get_field_value('pageid'), '0' ); ?>><?php _e( 'Coming Soon Settings Below', 'coming-soon-for-genesis' ); ?></option>
							<?php
							if( $gcs_pages = get_pages() )
								foreach( $gcs_pages as $page ) : 
									$select_title = $page->post_title;
									if( strlen($select_title) > 80 ) {
										$select_title = substr($select_title, 0, 80);
										$select_title = substr($select_title, 0, strrpos($select_title, ' '));
										$select_title .= ' ...';
									}
									?>
									<option value="<?php echo $page->ID; ?>"<?php selected( $this->get_field_value('pageid'), $page->ID ); ?>><?php echo esc_attr( $select_title ); ?></option>
								<?php endforeach; ?>
						</select>
					</p>
				</td>
			</tr>

		</tbody>
		</table>
		<?php

	}


	// Callback for Content meta box
	function content_box() {

		?>
		<table class="form-table genesis-coming-soon">
		<tbody>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Logo', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p class="gcs-upload-control">
						<span class="gcs-zero-size">
							<label for="<?php $this->field_id('logo'); ?>" class="screen-reader-text"><?php _e( 'Logo', 'coming-soon-for-genesis' ); ?></label>
							<input type="text" name="<?php $this->field_name('logo'); ?>" id="<?php $this->field_id('logo'); ?>" value="<?php echo esc_attr( $this->get_field_value('logo') ); ?>" class="regular-text">
							<a href="#" data-target="<?php $this->field_id('logo'); ?>" 
										data-boxtitle="<?php _e( 'Select a Logo Image', 'coming-soon-for-genesis' ); ?>" 
										data-boxbutton="<?php _e( 'Select Logo', 'coming-soon-for-genesis' ); ?>" 
										class="button-secondary upload-button"><?php _e('Media Image Library', 'coming-soon-for-genesis'); ?></a>
						</span>
						<br>
						<span class="description"><?php _e( 'Upload a logo or teaser image (or) enter the url to your image.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Headline', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('headline'); ?>" class="screen-reader-text"><?php _e( 'Headline', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('headline'); ?>" id="<?php $this->field_id('headline'); ?>" value="<?php echo esc_attr( $this->get_field_value('headline') ); ?>" class="regular-text">
						<br>
						<span class="description"><?php printf( __( 'Enter the %s headline for your page.', 'coming-soon-for-genesis' ), genesis_code( '<h1>' ) ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Message', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<label for="<?php $this->field_id('message'); ?>" class="screen-reader-text"><?php _e( 'Message', 'coming-soon-for-genesis' ); ?></label>
					<?php
					do_action( 'edit_form_advanced', array(
						'text' => $this->get_field_value('message'),
						'id'   => $this->get_field_id('message'),
						'args' => array( 'textarea_name' => $this->get_field_name('message'), 'textarea_rows' => 8 ),
						)); ?>
					<span class="description"><?php _e( 'Tell the visitor what to expect from your site.', 'coming-soon-for-genesis' ); ?></span>
				</td>
			</tr>

		</tbody>
		</table>
		<?php

	}


	// Callback for Design meta box
	function background_design_box() {

		?>
		<table class="form-table genesis-coming-soon">
		<tbody>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Background Color', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('bgcolor'); ?>" class="screen-reader-text"><?php _e( 'Background Color', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('bgcolor'); ?>" id="<?php $this->field_id('bgcolor'); ?>" value="<?php echo esc_attr( $this->get_field_value('bgcolor') ); ?>" class="color-field">
						<br>
						<span class="description"><?php _e( 'By default images will cover the entire background.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Background Image', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p class="gcs-upload-control">
						<span class="gcs-zero-size">
							<label for="<?php $this->field_id('bgimage'); ?>" class="screen-reader-text"><?php _e( 'Background Image', 'coming-soon-for-genesis' ); ?></label>
							<input type="text" name="<?php $this->field_name('bgimage'); ?>" id="<?php $this->field_id('bgimage'); ?>" value="<?php echo esc_attr( $this->get_field_value('bgimage') ); ?>" class="regular-text">
							<a href="#" data-target="<?php $this->field_id('bgimage'); ?>" 
										data-boxtitle="<?php _e( 'Select a Background', 'coming-soon-for-genesis' ); ?>" 
										data-boxbutton="<?php _e( 'Select Background', 'coming-soon-for-genesis' ); ?>" 
										class="button-secondary upload-button"><?php _e('Media Image Library', 'coming-soon-for-genesis'); ?></a>
						</span>
						<br>
						<span class="description"><span><?php _e( 'Use a full size image to prevent a blurry or pixelated background.', 'coming-soon-for-genesis' ); ?></span></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Dim Background', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<input type="checkbox" name="<?php $this->field_name('bgdim'); ?>" id="<?php $this->field_id('bgdim'); ?>" value="1"<?php echo checked( $this->get_field_value('bgdim') ); ?>>
						<label for="<?php $this->field_id('bgdim'); ?>"><?php _e( 'Yes', 'coming-soon-for-genesis' ); ?></label>
						<br>
						<span class="description"><?php _e( 'This will add an overlay over your image dimming it.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Responsive Background', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<input type="checkbox" name="<?php $this->field_name('responsive'); ?>" id="<?php $this->field_id('responsive'); ?>" value="1"<?php echo checked( $this->get_field_value('responsive') ); ?>>
						<label for="<?php $this->field_id('responsive'); ?>"><?php _e( 'Yes', 'coming-soon-for-genesis' ); ?></label>
						<br>
						<span class="description"><?php _e( 'The background area is completely covered by the background image.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Repeat Background', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('repeatbg'); ?>" class="screen-reader-text"><?php _e( 'Repeat Background', 'coming-soon-for-genesis' ); ?></label>
						<select name="<?php $this->field_name('repeatbg'); ?>" id="<?php $this->field_id('repeatbg'); ?>" class="postform">
							<option value="no-repeat"<?php selected( $this->get_field_value('repeatbg'), 'no-repeat' ); ?>><?php _e( 'No Repeat', 'coming-soon-for-genesis' ); ?></option>
							<option value="repeat"<?php selected( $this->get_field_value('repeatbg'), 'repeat' ); ?>><?php _e( 'Tile', 'coming-soon-for-genesis' ); ?></option>
							<option value="repeat-x"<?php selected( $this->get_field_value('repeatbg'), 'repeat-x' ); ?>><?php _e( 'Tile Horizontally', 'coming-soon-for-genesis' ); ?></option>
							<option value="repeat-y"<?php selected( $this->get_field_value('repeatbg'), 'repeat-y' ); ?>><?php _e( 'Tile Vertically', 'coming-soon-for-genesis' ); ?></option>
						</select>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Background Position', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('positionbg'); ?>" class="screen-reader-text"><?php _e( 'Background Position', 'coming-soon-for-genesis' ); ?></label>
						<select name="<?php $this->field_name('positionbg'); ?>" id="<?php $this->field_id('positionbg'); ?>" class="postform">
							<option value="left top"<?php selected( $this->get_field_value('positionbg'), 'left top' ); ?>><?php _e( 'Left Top', 'coming-soon-for-genesis' ); ?></option>
							<option value="center top"<?php selected( $this->get_field_value('positionbg'), 'center top' ); ?>><?php _e( 'Center Top', 'coming-soon-for-genesis' ); ?></option>
							<option value="right top"<?php selected( $this->get_field_value('positionbg'), 'right top' ); ?>><?php _e( 'Right Top', 'coming-soon-for-genesis' ); ?></option>
							<option value="left center"<?php selected( $this->get_field_value('positionbg'), 'left center' ); ?>><?php _e( 'Left Center', 'coming-soon-for-genesis' ); ?></option>
							<option value="center center"<?php selected( $this->get_field_value('positionbg'), 'center center' ); ?>><?php _e( 'Center Center', 'coming-soon-for-genesis' ); ?></option>
							<option value="right center"<?php selected( $this->get_field_value('positionbg'), 'right center' ); ?>><?php _e( 'Right Center', 'coming-soon-for-genesis' ); ?></option>
							<option value="left bottom"<?php selected( $this->get_field_value('positionbg'), 'left bottom' ); ?>><?php _e( 'Left Bottom', 'coming-soon-for-genesis' ); ?></option>
							<option value="center bottom"<?php selected( $this->get_field_value('positionbg'), 'center bottom' ); ?>><?php _e( 'Center Bottom', 'coming-soon-for-genesis' ); ?></option>
							<option value="right bottom"<?php selected( $this->get_field_value('positionbg'), 'right bottom' ); ?>><?php _e( 'Right Bottom', 'coming-soon-for-genesis' ); ?></option>
						</select>
					</p>
				</td>
			</tr>

		</tbody>
		</table>
		<?php

	}


	// Callback for Design meta box
	function content_design_box() {

		?>
		<table class="form-table genesis-coming-soon">
		<tbody>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Max Width', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('maxwidth'); ?>" class="screen-reader-text"><?php _e( 'Max Width', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('maxwidth'); ?>" id="<?php $this->field_name('maxwidth'); ?>" value="<?php echo esc_attr( $this->get_field_value('maxwidth') ); ?>" class="small-text" size="3">
						<?php _e( 'px', 'coming-soon-for-genesis' ); ?><br>
						<span class="description"><?php _e( 'Enter the max width of the content.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Box Content', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<input type="checkbox" name="<?php $this->field_name('messagedim'); ?>" id="<?php $this->field_id('messagedim'); ?>" value="1"<?php echo checked( $this->get_field_value('messagedim') ); ?>>
						<label for="<?php $this->field_id('messagedim'); ?>"><?php _e( 'Yes', 'coming-soon-for-genesis' ); ?></label>
						<br>
						<span class="description"><?php _e( 'This will add color behind your content creating a box.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Box Color', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('position'); ?>" class="screen-reader-text"><?php _e( 'Box Color', 'coming-soon-for-genesis' ); ?></label>
						<select name="<?php $this->field_name('boxcolor'); ?>" id="<?php $this->field_id('boxcolor'); ?>" class="postform">
							<option value="none"<?php selected( $this->get_field_value('boxcolor'), 'none' ); ?>><?php _e( 'None', 'coming-soon-for-genesis' ); ?></option>
							<option value="#ffffff"<?php selected( $this->get_field_value('boxcolor'), '#ffffff' ); ?>><?php _e( 'White', 'coming-soon-for-genesis' ); ?></option>
							<option value="#000000"<?php selected( $this->get_field_value('boxcolor'), '#000000' ); ?>><?php _e( 'Black', 'coming-soon-for-genesis' ); ?></option>
							<option value="rgba(255,255,255,0.75)"<?php selected( $this->get_field_value('boxcolor'), 'rgba(255,255,255,0.75)' ); ?>><?php _e( 'White 75%', 'coming-soon-for-genesis' ); ?></option>
							<option value="rgba(0,0,0,0.5)"<?php selected( $this->get_field_value('boxcolor'), 'rgba(0,0,0,0.5)' ); ?>><?php _e( 'Black 50%', 'coming-soon-for-genesis' ); ?></option>
						</select>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Content Position', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('position'); ?>" class="screen-reader-text"><?php _e( 'Content Position', 'coming-soon-for-genesis' ); ?></label>
						<select name="<?php $this->field_name('position'); ?>" id="<?php $this->field_id('position'); ?>" class="postform">
							<option value="left top"<?php selected( $this->get_field_value('position'), 'left top' ); ?>><?php _e( 'Left Top', 'coming-soon-for-genesis' ); ?></option>
							<option value="center top"<?php selected( $this->get_field_value('position'), 'center top' ); ?>><?php _e( 'Center Top', 'coming-soon-for-genesis' ); ?></option>
							<option value="right top"<?php selected( $this->get_field_value('position'), 'right top' ); ?>><?php _e( 'Right Top', 'coming-soon-for-genesis' ); ?></option>
							<option value="left center"<?php selected( $this->get_field_value('position'), 'left center' ); ?>><?php _e( 'Left Center', 'coming-soon-for-genesis' ); ?></option>
							<option value="center center"<?php selected( $this->get_field_value('position'), 'center center' ); ?>><?php _e( 'Center Center', 'coming-soon-for-genesis' ); ?></option>
							<option value="right center"<?php selected( $this->get_field_value('position'), 'right center' ); ?>><?php _e( 'Right Center', 'coming-soon-for-genesis' ); ?></option>
							<option value="left bottom"<?php selected( $this->get_field_value('position'), 'left bottom' ); ?>><?php _e( 'Left Bottom', 'coming-soon-for-genesis' ); ?></option>
							<option value="center bottom"<?php selected( $this->get_field_value('position'), 'center bottom' ); ?>><?php _e( 'Center Bottom', 'coming-soon-for-genesis' ); ?></option>
							<option value="right bottom"<?php selected( $this->get_field_value('position'), 'right bottom' ); ?>><?php _e( 'Right Bottom', 'coming-soon-for-genesis' ); ?></option>
						</select>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Title Color', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('titlecolor'); ?>" class="screen-reader-text"><?php _e( 'Title Color', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('titlecolor'); ?>" id="<?php $this->field_id('titlecolor'); ?>" value="<?php echo esc_attr( $this->get_field_value('titlecolor') ); ?>" class="color-field">
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Text Color', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('textcolor'); ?>" class="screen-reader-text"><?php _e( 'Text Color', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('textcolor'); ?>" id="<?php $this->field_id('textcolor'); ?>" value="<?php echo esc_attr( $this->get_field_value('textcolor') ); ?>" class="color-field">
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Link Color', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('linkcolor'); ?>" class="screen-reader-text"><?php _e( 'Link Color', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('linkcolor'); ?>" id="<?php $this->field_id('linkcolor'); ?>" value="<?php echo esc_attr( $this->get_field_value('linkcolor') ); ?>" class="color-field">
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Font Combinations', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('fonts'); ?>" class="screen-reader-text"><?php _e( 'Font Combinations', 'coming-soon-for-genesis' ); ?></label>
						<select name="<?php $this->field_name('fonts'); ?>" id="<?php $this->field_id('fonts'); ?>" class="postform">
							<option value="0"<?php selected( $this->get_field_value('fonts'), '0' ); ?>><?php _e( 'Theme Defaults', 'coming-soon-for-genesis' ); ?></option>
							<option value="1"<?php selected( $this->get_field_value('fonts'), '1' ); ?>><?php _e( 'Libre Baskerville / Ubuntu', 'coming-soon-for-genesis' ); ?></option>
							<option value="2"<?php selected( $this->get_field_value('fonts'), '2' ); ?>><?php _e( 'Bree Serif / Imprima', 'coming-soon-for-genesis' ); ?></option>
							<option value="3"<?php selected( $this->get_field_value('fonts'), '3' ); ?>><?php _e( 'Montserrat / Pontano Sans', 'coming-soon-for-genesis' ); ?></option>
							<option value="4"<?php selected( $this->get_field_value('fonts'), '4' ); ?>><?php _e( 'Oswald / Muli', 'coming-soon-for-genesis' ); ?></option>
							<option value="5"<?php selected( $this->get_field_value('fonts'), '5' ); ?>><?php _e( 'Pt Serif / PT Sans', 'coming-soon-for-genesis' ); ?></option>
							<option value="6"<?php selected( $this->get_field_value('fonts'), '6' ); ?>><?php _e( 'Vollkorn / Raleway Light', 'coming-soon-for-genesis' ); ?></option>
							<option value="7"<?php selected( $this->get_field_value('fonts'), '7' ); ?>><?php _e( 'Roboto / Roboto Light', 'coming-soon-for-genesis' ); ?></option>
							<option value="8"<?php selected( $this->get_field_value('fonts'), '8' ); ?>><?php _e( 'Josefin Slab / Maven Pro', 'coming-soon-for-genesis' ); ?></option>
							<option value="9"<?php selected( $this->get_field_value('fonts'), '9' ); ?>><?php _e( 'Oxygen / Source Sans Pro', 'coming-soon-for-genesis' ); ?></option>
							<option value="10"<?php selected( $this->get_field_value('fonts'), '10' ); ?>><?php _e( 'Paytone One / Droid Sans', 'coming-soon-for-genesis' ); ?></option>
						</select>
						<br>
						<span class="description"><?php _e( 'You can choose one of these Google Font Combinations instead of you theme default fonts.', 'coming-soon-for-genesis' ); ?></span>
					</p>
				</td>
			</tr>

		</tbody>
		</table>
		<?php

	}


	// Callback for Extras meta box
	function extras_box() {

		?>
		<table class="form-table genesis-coming-soon">
		<tbody>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Custom CSS', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('customcss'); ?>" class="screen-reader-text"><?php _e( 'Custom CSS', 'coming-soon-for-genesis' ); ?></label>
						<textarea spellcheck="false" name="<?php $this->field_name('customcss'); ?>" id="<?php $this->field_id( 'customcss' ); ?>" class="large-text" rows="6"><?php echo esc_attr( $this->get_field_value('customcss') ); ?></textarea>
						<span class="description"><?php printf( __( 'Add your custom CSS here. No need to include the %s tag.', 'coming-soon-for-genesis' ), genesis_code( '<style>' ) ); ?></span>
					</p>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="option-title"><span><?php _e( 'Custom Functions', 'coming-soon-for-genesis' ); ?></span></th>
				<td>
					<p>
						<label for="<?php $this->field_id('customfun'); ?>" class="screen-reader-text"><?php _e( 'Custom Functions', 'coming-soon-for-genesis' ); ?></label>
						<input type="text" name="<?php $this->field_name('customfun'); ?>" id="<?php $this->field_id('customfun'); ?>" value="<?php echo esc_attr( $this->get_field_value('customfun') ); ?>" class="large-text">
						<br>
						<span class="description">
							<?php _e( 'The plugin will do its best to hide the header and footer from the Coming Soon Page.', 'coming-soon-for-genesis' ); ?><br>
							<?php _e( 'If you reposition something to customize your theme, it may not be recognized by the plugin.', 'coming-soon-for-genesis' ); ?><br>
							<?php _e( 'You can write the functions you want the plugin to ignore separating them by commas. For instance, if you theme has something like...', 'coming-soon-for-genesis' ); ?><br>
							<code class="blockcode">
							remove_action( 'genesis_footer', 'genesis_do_footer' );<br>
							add_action( 'genesis_after', 'genesis_do_footer', 10 );<br>
							add_action( 'genesis_after', 'custom_extra_footer', 15 );
							</code><br>
							<?php _e( '... this value should be: ', 'coming-soon-for-genesis' ); ?><code>genesis_do_footer,custom_extra_footer</code>.
						</span>
					</p>
				</td>
			</tr>

		</tbody>
		</table>
		<?php

	}

}


//* Instantiate the class to create the menu
add_action( 'genesis_admin_menu', 'comingsoon_settings_menu' );
function comingsoon_settings_menu() {
	global $_genesis_coming_soon_admin;
	$_genesis_coming_soon_admin = new Genesis_Coming_Soon_Admin();
}





























/* --- end */