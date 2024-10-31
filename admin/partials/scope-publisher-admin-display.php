<?php
/**
 * Admin area view for the plugin
 *
 * @link       https://github.com/avramz
 * @since      1.0.0
 *
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/admin/partials
 */

?>
<div class="scope-publisher-wrap">
    <h1><?php _e('Welcome to Scope!', 'scope-publisher'); ?></h1>
    <div id="activated" class="scope-hide">
        <h2><?php _e('You have successfully activated a box!', 'scope-publisher'); ?></h2>
        <button class="scope-btn" id="deactivateScope"><?php _e('Deactivate', 'scope-publisher'); ?></button>
        <h2>Categories</h2>
        <p>Blog posts that are pushed from Scope are assigned to the categories selected below:</p>
        <?php
        $categories = get_categories(array("hide_empty" => 0,
            "type" => "post",
            "orderby" => "name",
            "order" => "ASC"));
        $selected_categories = Scope_Publisher_Admin::scope_pub_get_selected_categories();

        foreach ($categories as $category) {
            $checked = in_array($category->term_id, $selected_categories) ? 'checked="checked"' : '';
            echo '<div class="category" id="category-list">
                <label><input type="checkbox" value="' . $category->term_id . '" ' . $checked . ' > ' . $category->name . '</label>
            </div>';
        }
        ?>
    </div>
    <form novalidate id="scope-activator-form">
        <div id="activate" class="scope-hide">
            <div class="activation">
                <h2><?php _e('Enter Activation key:', 'scope-publisher'); ?> <input type="text"
                                                                                    id="scope-activation-key"/></h2>
                <button class="scope-btn" id="activateScope"
                        type="submit"><?php _e('Activate', 'scope-publisher'); ?></button>
            </div>
        </div>
    </form>
</div>