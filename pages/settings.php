<?php
if (!defined('ABSPATH')) {
    die(__('security check failure','marctv-ajax-trash-comments'));
}
?>
<div class="wrap">
    <h2><?php echo __('Moderate Comments Settings', 'marctv-ajax-trash-comments'); ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields($this->pluginPrefix . '-settings-group'); ?>
        <?php do_settings_sections($this->pluginPrefix . '-settings-group'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __('Moderation text', 'marctv-ajax-trash-comments'); ?></th>
                <td>
                        <p><label for="marctv-moderation-text">
                                <?php echo __('This text will be used to replace the comment text when you click "Replace".', 'marctv-ajax-trash-comments'); ?>
                            </label></p>
                        <p>
                            <textarea name="marctv-moderation-text" rows="10" cols="50" id="marctv-moderation-text" class="large-text code"><?php echo get_option('marctv-moderation-text'); ?></textarea>
                        </p>
                    </fieldset>

                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Display settings', 'marctv-ajax-trash-comments'); ?></th>
                <td>
                    <label for="marctv-moderate_members_only"><input
                            id="<?php echo $this->pluginPrefix . '_members_only'; ?>"
                            name="<?php echo $this->pluginPrefix . '_members_only'; ?>"
                            type="checkbox" <?php checked(get_option($this->pluginPrefix . '_members_only'), 'on') ?> />
                        <?php echo __('Only logged in users may report comments', 'marctv-ajax-trash-comments'); ?></label>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
</div>
