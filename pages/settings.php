<?php
if (!defined('ABSPATH')) {
    die(__('Cheatin&#8217; uh?'));
}

?>
<div class="wrap">
	<h2>MarcTV Moderate Comments</h2>
	<h3><?php echo __( 'Moderate Comments Settings', 'marctv-moderate' ); ?></h3>
	<form method="post" action="options.php">
		<?php settings_fields( $this->pluginPrefix . '-settings-group' ); ?>
		<?php do_settings_sections( $this->pluginPrefix . '-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php echo __( 'Moderation text', $this->pluginPrefix ); ?></th>
				<td><textarea rows="4" cols="50" type="text" name="marctv-moderation-text"><?php echo get_option('marctv-moderation-text'); ?></textarea>
				<p class="description"><?php echo __( 'This text will be used to replace the comment text if you click "replace comment text".', $this->pluginPrefix ); ?></p></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo __( 'Undo warning accepted?', $this->pluginPrefix ); ?></th>
				<td><input name="<?php echo $this->pluginPrefix. '_members_only'; ?>" type="checkbox" <?php checked(get_option($this->pluginPrefix. '_members_only'), 'on') ?> />

				<p class="description"><?php echo __('Indicates that you have read and accepted the warning that replacing the comment text with the moderation text above can not be undone.', 'marctv-moderate'); ?></p>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>
</div>