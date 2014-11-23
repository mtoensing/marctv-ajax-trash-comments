<?php
/*
Plugin Name: MarcTV Moderate Comments
Plugin URI: http://marctv.de/blog/marctv-wordpress-plugins/
Description: Grants visitors the ability to report inappropriate comments and admins to replace and trash them in the frontend.
Version:  1.2
Author:  Marc Tönsing, Peter Berglund
Author URI: marctv.de
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class MarcTVModerateComments
{

    private $version = '1.2';
    private $pluginPrefix = 'marctv-moderate';
    private $pluginUrl;
    private $strings;

    public function __construct()
    {
        $this->pluginUrl = plugins_url(false, __FILE__);

        load_plugin_textdomain('marctv-moderate', false, dirname(plugin_basename(__FILE__)) . '/language/');

        $this->strings = $this->getStrings();

        if (is_admin()) {
            $this->backendInit();
        }

        $this->frontendInit();

    }

    /**
     * Defines what happens on plugin activation.
     */
    public function activate()
    {

        /* if comment moderation text is not set get the localized string. */
        if (!get_option('marctv-moderation-text')) {
            /* Loading the textdomain. I could not figure out to prevent this here. */
            load_plugin_textdomain('marctv-moderate', false, dirname(plugin_basename(__FILE__)) . '/languages/');

            /* If the moderation text is empty fill it with the default text */
            update_option('marctv-moderation-text', __('[incorrect topic]', 'marctv-moderate'));
        }
    }

    /**
     * Registers the reported comments page as a sub page to comments in admin.
     */
    public function registerCommentsPage()
    {
        if (!($count = get_transient($this->pluginPrefix . '_count'))) {
            $count = $this->getCount();
            set_transient($this->pluginPrefix . '_count', $count, 30 * MINUTE_IN_SECONDS);
        }
        $bubble = '<span class="update-plugins count-' . $count . '"><span class="update-count">' . number_format_i18n($count) . '</span></span>';
        $text = $this->strings['menu_title'] . $bubble;

        add_comments_page($this->strings['page_title'], $text, 'moderate_comments', $this->pluginPrefix . '_reported', array($this, 'commentsPage'));
    }

    /**
     * Actions and filters for frontend.
     */
    public function frontendInit()
    {
        add_filter('comment_text', array($this, 'printModerateLinks'));

        add_action('wp_ajax_' . $this->pluginPrefix . '_trash', array($this, 'trashComment'));
        add_action('wp_ajax_' . $this->pluginPrefix . '_replace', array($this, 'replaceComment'));
        add_action('wp_ajax_' . $this->pluginPrefix . '_flag', array($this, 'flagComment'));
        add_action('wp_ajax_nopriv_' . $this->pluginPrefix . '_flag', array($this, 'flagComment'));
        add_action('wp_print_styles', array($this, 'enqueScripts'));

    }


    public function enqueScripts()
    {

        /* Load css and js for vistors */
        wp_enqueue_script($this->pluginPrefix . '_script', $this->pluginUrl . '/marctv-moderate.js', array('jquery'), $this->version, true);

        wp_enqueue_style($this->pluginPrefix . '_style', $this->pluginUrl . "/marctv-moderate.css", false, $this->version);

        /* Load js for moderators and admins */
        if (current_user_can('moderate_comments') && is_single()) {
            wp_enqueue_script($this->pluginPrefix . '_admin_script', $this->pluginUrl . '/marctv-moderate-admin.js', array('jquery'), $this->version, true);
        }

        $translations = array(
            'pluginprefix' => $this->pluginPrefix,
            'ajaxurl' => admin_url('admin-ajax.php'),
            'confirm_report' => $this->strings['confirm_report'],
            'confirm_replace' => $this->strings['confirm_replace'],
            'adminurl' => admin_url('admin-ajax.php'),
            'trash_string' => $this->strings['trash'],
            'untrash_string' => $this->strings['untrash'],
            'trashing_string' => $this->strings['trashing'],
            'untrashing_string' => $this->strings['untrashing'],
            'error_string' => $this->strings['error'],
            'replacing_string' => $this->strings['replacing'],
            'reporting_string' => $this->strings['reporting'],
            'replaced_string' => $this->strings['replace_success']
        );


        wp_localize_script($this->pluginPrefix . '_script', 'marctvmoderatejs', $translations);


    }

    /**
     * Actions for backend.
     */
    public function backendInit()
    {
        add_action('admin_menu', array($this, 'registerCommentsPage'));
        add_action('admin_menu', array($this, 'registerSettingsPage'));
        add_action('admin_action_' . $this->pluginPrefix . '_ignore', array($this, 'ignoreReport'));
        add_action('admin_action_' . $this->pluginPrefix . '_replace', array($this, 'replaceCommentReport'));
        add_action('admin_init', array($this, 'registerSettings'));
    }


    /**
     * Add a menu item to the admin bar.
     */
    public function registerSettingsPage()
    {
        add_options_page('MarcTV Moderate Comments', 'Moderate Comments', 'manage_options', $this->pluginPrefix, array($this, 'showSettingsPage'));
    }

    /**
     * Includes the settings page.
     */
    public function showSettingsPage()
    {
        include('pages/settings.php');
    }

    /**
     * Sets all strings used by the plugin. Use the 'report_comments_strings' filter to modify them yourself.
     * @return string
     */
    public function getStrings()
    {
        $strings = array(
            // Title for link in the menu.
            'menu_title' => __('Reported', 'marctv-moderate'),
            // Title for the reported comments page.
            'page_title' => __('Reported comments', 'marctv-moderate'),
            // Confirm dialog on front end for replace.
            'confirm_replace' => __('Are you sure you want to replace this comment? This action can not be undone!', 'marctv-moderate'),
            // Confirm dialog on front end for reporting.
            'confirm_report' => __('Are you sure you want to report this comment?', 'marctv-moderate'),
            // Message to show user after successfully reporting a comment.
            'report_success' => __('The comment has been reported.', 'marctv-moderate'),
            // Message to show user after reporting a comment has failed.
            'report_failed' => __('The comment has been reported.', 'marctv-moderate'),
            // Message to show user after successfully replacing a comment.
            'replace_success' => __('The comment text has been replaced', 'marctv-moderate'),
            // Message to show user after replacing a comment has failed.
            'replace_failed' => __('The comment has already been replaced', 'marctv-moderate'),
            // Text for the report link shown below each comment.
            'report' => __('Report', 'marctv-moderate'),
            // Text for the trash link shown below each comment.
            'trash' => __('Trash', 'marctv-moderate'),
            // Text for the replace link shown below each comment.
            'untrash' => __('Untrash', 'marctv-moderate'),
            // Text for the replace link shown below each comment.
            'replace' => __('Replace', 'marctv-moderate'),
            // Text in admin for link that deems the comment OK.
            'ignore_report' => __('Comment is ok', 'marctv-moderate'),
            // Action of moving a comment in the trash.
            'trashing' => __('trashing', 'marctv-moderate'),
            // Action of moving a comment out of the trash.
            'untrashing' => __('untrashing', 'marctv-moderate'),
            // Error message
            'error' => __('an error occurred.', 'marctv-moderate'),
            // Action while replacing a comment.
            'replacing' => __('replacing', 'marctv-moderate'),
            // Action while reporting a comment.
            'reporting' => __('reporting', 'marctv-moderate'),
            // Error message shown when a comment can't be found.
            'invalid_comment' => __('The comment does not exist', 'marctv-moderate'),
            // Header for settings field.
            'settings_header' => __('Report Comments Settings', 'marctv-moderate'),
            // Description for members only setting.
            'settings_members_only' => __('Only logged in users may report comments', 'marctv-moderate')
        );

        return apply_filters('report_comments_strings', $strings);
    }

    /**
     * Fetches comments flagged as reported and displays them in a table.
     */
    public function commentsPage()
    {
        if (!current_user_can('moderate_comments')) {
            die(__('Cheatin&#8217; uh?'));
        }

        global $wpdb;

        $comments = $wpdb->get_results(
            $wpdb->prepare("
				SELECT * FROM $wpdb->commentmeta 
				INNER JOIN $wpdb->comments on $wpdb->comments.comment_id = $wpdb->commentmeta.comment_id
				WHERE $wpdb->comments.comment_approved = 1 AND meta_key = %s AND meta_value = 1 LIMIT 0, 25",
                $this->pluginPrefix . '_reported')
        );
        $count = count($comments);
        set_transient($this->pluginPrefix . '_count', $count, 1 * HOUR_IN_SECONDS);
        include('pages/comments-list.php');
    }

    /**
     * Returns how many reported comments are in the system.
     * @return int
     */
    private function getCount()
    {
        global $wpdb;

        $comments = $wpdb->get_results(
            $wpdb->prepare("
				SELECT * FROM $wpdb->commentmeta 
				INNER JOIN $wpdb->comments on $wpdb->comments.comment_id = $wpdb->commentmeta.comment_id
				WHERE $wpdb->comments.comment_approved = 1 AND meta_key = %s AND meta_value = 1 LIMIT 0, 10",
                $this->pluginPrefix . '_reported')
        );
        return count($comments);
    }

    /**
     * Flags a comment as reported. Won't flag a comment that has been flagged before and approved.
     * @param  int $id Comment id.
     * @return bool
     */
    private function flag($id)
    {
        $value = get_comment_meta($id, $this->pluginPrefix . '_reported', true);
        if ($value < 0) {
            return false;
        }
        return add_comment_meta($id, $this->pluginPrefix . '_reported', true, true);
    }

    /**
     * Ajax-callable function which flags a comment as reported.
     * Dies with message to be displayed to user.
     */
    public function flagComment()
    {
        $id = (int)$_POST['id'];
        if (!wp_verify_nonce($_POST['_ajax_nonce'], "report-comment-" . $id) || $id != $_POST['id'] || !check_ajax_referer("report-comment-" . $id)) {
            die(__('Cheatin&#8217; uh?'));
        }

        if (get_option($this->pluginPrefix . '_members_only') && !is_user_logged_in()) {
            die(__('Cheatin&#8217; uh?'));
        }

        if (!$this->flag($id)) {
            // This may happen when the comment has been reported once, but deemed ok by an admin, or
            // when something went wrong. Either way, we won't bother the visitor with that information
            // and we'll show the same message for both sucess and failed here by default.
            die($this->strings['report_failed']);
        }
        die($this->strings['report_success']);
    }

    /**
     * Ajax-callable function which replaces a comment with a defined text.
     */
    public function replaceComment()
    {
        $id = (int)$_POST['id'];
        if (!wp_verify_nonce($_POST['_ajax_nonce'], "replace-comment-" . $id) || $id != $_POST['id'] || !check_ajax_referer("replace-comment-" . $id)) {
            die(__('Cheatin&#8217; uh?'));
        }

        if (get_option($this->pluginPrefix . '_members_only') && !is_user_logged_in()) {
            die(__('Cheatin&#8217; uh?'));
        }

        /* Replace comment with moderation text */
        $comment_arr = array();
        $comment_arr['comment_ID'] = $id;
        $comment_arr['comment_content'] = get_option('marctv-moderation-text');

        if (!wp_update_comment($comment_arr)) {
            die($this->strings['replace_failed']);
        }

        die($this->strings['replace_success']);
    }

    /**
     * Ajax-callable function which puts a comment in the trash.
     */
    public function trashComment()
    {
        $id = (int)$_POST['id'];

        $comment_status = wp_get_comment_status($id);

        if (!wp_verify_nonce($_POST['_ajax_nonce'], "trash-comment-" . $id) || $id != $_POST['id'] || !check_ajax_referer("trash-comment-" . $id)) {
            die(__('Cheatin&#8217; uh?'));
        }

        if (get_option($this->pluginPrefix . '_members_only') && !is_user_logged_in()) {
            die(__('Cheatin&#8217; uh?'));
        }


        switch ($comment_status) {
            case 'approved':
                if (wp_trash_comment($id)) {
                    die('trashed');
                }
                break;
            case 'trash':
                if (wp_untrash_comment($id)) {
                    die('untrashed');
                }
                break;
            default:
                die('error');
        }
    }

    /**
     * Constructs "report this comment" link.
     * @return string
     */
    private function getReportLink()
    {
        $id = get_comment_ID();
        $class = $this->pluginPrefix . "-report";
        $nonce = wp_create_nonce("report-comment-" . $id);

        $link = sprintf('<a href="#" data-nonce="%s" data-cid="%s" class="%s">%s</a>',
            $nonce,
            $id,
            $class,
            $this->strings['report']
        );
        return $link;
    }

    /**
     * Constructs "trash this comment" link.
     * @return string
     */
    private function getTrashLink()
    {
        $id = get_comment_ID();
        $class = $this->pluginPrefix . "-trash";
        $nonce = wp_create_nonce("trash-comment-" . $id);
        $link = sprintf('<a href="#" data-nonce="%s" data-cid="%s" class="%s">%s</a>',
            $nonce,
            $id,
            $class,
            $this->strings['trash']
        );
        return $link;
    }

    /**
     * Constructs "replace this comment" link.
     * @return string
     */
    private function getReplaceLink()
    {
        $id = get_comment_ID();
        $class = $this->pluginPrefix . "-replace";
        $nonce = wp_create_nonce("replace-comment-" . $id);
        $link = sprintf('<a href="#" data-nonce="%s" data-cid="%s" class="%s">%s</a>',
            $nonce,
            $id,
            $class,
            $this->strings['replace']
        );
        return $link;
    }


    /**
     * Appends a "report this comment" link after the "reply" link below a comment.
     */
    public function printModerateLinks($comment_reply_link)
    {
        if (is_single()) {
            if (current_user_can('moderate_comments')) {
                return $comment_reply_link . '<br /><br />' . $this->getReportLink() . ' | ' . $this->getTrashLink() . ' | ' . $this->getReplaceLink();
            } else {
                if (!get_option($this->pluginPrefix . '_members_only')) {
                    return $comment_reply_link . '<br /><br />' . $this->getReportLink();
                }
            }
        }
        return $comment_reply_link;

    }

    /**
     * Unflags the comment as reported.
     */
    public function ignoreReport()
    {
        if (isset($_GET['c']) && isset($_GET['_wpnonce'])) {
            if (!wp_verify_nonce($_GET['_wpnonce'], 'ignore-report_' . $_GET['c']) || !current_user_can('moderate_comments')) {
                die(__('Cheatin&#8217; uh?'));
            }
            $id = absint($_GET['c']);
            if (!get_comment($id)) {
                die($this->strings['invalid_comment']);
            }
            // We set the meta value to -1, and by that it wont be able to be reported again.
            // Once deemed ok -> always ok.
            # todo: add this as an option (being able to report the comment again or not)
            update_comment_meta($id, $this->pluginPrefix . '_reported', -1);

            wp_redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * replaces the comment in the reported comments list.
     */
    public function replaceCommentReport()
    {
        if (isset($_GET['c']) && isset($_GET['_wpnonce'])) {
            if (!wp_verify_nonce($_GET['_wpnonce'], 'replace-comment-' . $_GET['c']) || !current_user_can('moderate_comments')) {
                die(__('Cheatin&#8217; uh?'));
            }
            $id = absint($_GET['c']);
            if (!get_comment($id)) {
                die($this->strings['invalid_comment']);
            }

            /* Replace comment with moderation text */
            $comment_arr = array();
            $comment_arr['comment_ID'] = $id;
            $comment_arr['comment_content'] = get_option('marctv-moderation-text');

            if (!wp_update_comment($comment_arr)) {
                die($this->strings['replace_failed']);
            }

            wp_redirect($_SERVER['HTTP_REFERER']);
        }
    }


    /**
     * Registers settings for plugin.
     */
    public function registerSettings()
    {
        add_settings_section($this->pluginPrefix . '_settings',
            $this->strings['settings_header'],
            null,
            'discussion'
        );

        add_settings_field($this->pluginPrefix . '_members_only',
            $this->strings['settings_members_only'],
            array($this, 'settingsCallback'),
            'discussion',
            $this->pluginPrefix . '_settings'
        );

        register_setting($this->pluginPrefix . '-settings-group', 'marctv-moderation-text');
        register_setting($this->pluginPrefix . '-settings-group', $this->pluginPrefix . '_members_only');
        register_setting('discussion', $this->pluginPrefix . '_members_only');
    }

    /**
     * Displays settings field
     */
    public function settingsCallback()
    {
        ?>
        <input name="<?php echo $this->pluginPrefix . '_members_only'; ?>"
               type="checkbox" <?php checked(get_option($this->pluginPrefix . '_members_only'), 'on') ?> />
    <?php
    }
}


/* Activation */
register_activation_hook(
    __FILE__,
    array(
        'MarcTVModerateComments',
        'activate'
    )
);

/**
 * Initialize plugin.
 */
new MarcTVModerateComments();
