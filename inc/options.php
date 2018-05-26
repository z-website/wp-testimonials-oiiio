<?php global $oiiio_testimonials_options;
	if ( !isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>wp testimonials</h2>
	<?php if ( false !== $_REQUEST['updated'] ) : ?>
        <div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
    <?php endif; // If the form has just been submitted, this shows the notification ?>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span>wp testimonials settings</span></h3>
						<div class="inside">
                        <form method="post" action="options.php">
						<?php $settings = get_option( 'oiiio_testimonials_options', $oiiio_testimonials_options ); ?>
                        <?php settings_fields( 'testimonials_p_options' ); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <td scope="row"><label for="process-bar">Process bar color : </label></td>
                                <td><input id="process-bar" type="text" name="oiiio_testimonials_options[process-bar]" value="<?php echo stripslashes($settings['process-bar']); ?>" class="my-color-field" />
                                <p class="description"><code> default color #47a3da</code></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><label for="font-color">Font color : </label></td>
                                <td><input id="font-color" type="text" name="oiiio_testimonials_options[font-color]" value="<?php echo stripslashes($settings['font-color']); ?>" class="my-color-field" />
                                <p class="description"><code> default color #383838</code></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><label for="name-color">Name color : </label></td>
                                <td><input id="name-color" type="text" name="oiiio_testimonials_options[name-color]" value="<?php echo stripslashes($settings['name-color']); ?>" class="my-color-field" />
                                <p class="description"><code> default color #47a3da</code></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td scope="row"><label for="speed">Process bar speed : </label></td>
                                <td><input id="speed" type="text" name="oiiio_testimonials_options[speed]" value="<?php echo stripslashes($settings['speed']); ?>" />
                                <p class="description"><code> default speed 10000</code></p>
                                </td>
                            </tr>                            
                        </table>
                        <p><input class="button-primary" type="submit" name="Example" value="save" /> </p>
					</form>
						</div>
					</div>
				</div>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3><span>Short Code</span></h3>
						<div class="inside">
							<p class="description">Where you wanna see the testimonials just place this short code there : <code>[testimonials]</code>
							<br />
							<br />
							If you wanna place testimonials on a page template then use this code there : <br />
							<code>&lt;?php echo do_shortcode("[testimonials]"); ?&gt;</code>
							<br />
							<br />
							<a href="http://oiiio.tech/contact" target="_blank">Ping me for getting help</a>
							<br />
							<br />
							<a href="http://oiiio.tech/documentation" target="_blank">view plugin documentations</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>