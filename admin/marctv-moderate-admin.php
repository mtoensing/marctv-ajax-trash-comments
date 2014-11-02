<div class="wrap">
	<h2>MarcTV Moderate Comments</h2>
	<h3>MarcTV Moderate Comments Options</h3>
	<form method="post" action="options.php">
		<?php settings_fields( 'marctv-moderate-settings-group' ); ?>
		<?php do_settings_sections( 'marctv-moderate-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Moderationtext</th>
				<td><textarea rows="4" cols="50" type="text" name="moderation_text"><?php echo get_option('moderation_text'); ?></textarea></td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>
</div>