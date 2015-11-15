<?php

/*
    "Add Actions and Filters" Copyright (C) 2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Add Actions and Filters for WordPress.

    Add Actions and Filters is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Add Actions and Filters is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

class AddActionsAndFilters_ViewEditPage
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    // todo: need item data model for saving & retrieving

    public function __construct(&$plugin)
    {
        $this->plugin = $plugin;
    }

    public function display($item)
    {
        ?>
        <div class="wrap">
        <table width="100%">
            <tbody>
            <tr>
                <td align="left"><h2><?php _e('Edit Code', 'add-actions-and-filters'); ?></h2></td>
                <td align="right">
                    <a href="<?php echo 'admin.php?page=' . $this->plugin->getAdminPageSlug() ?>">
                        <img width="128" height="50"
                             src="<?php echo $this->plugin->getPluginFileUrl('img/icon-256x100.png') ?>">
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <?php


        // todo
        print_r($item);

        echo '</div>';
    }

    public function old_display() {
        // todo: change to new edit page code
        ?>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="SSABNHHPSVWT6">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0"
                   name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>

        <form action='' method='post'>
            <input type="submit" id="savecode" value="Save"/>

            <p id="codesavestatus">
                <?php
                $fatalCode = $this->getOption('fatal_code', '');
                $code = $this->getOption('code');
                $displayCode = $code;
                if (!empty($fatalCode) && $fatalCode != $code) {
                    $displayCode = $fatalCode;
                    $this->updateOption('fatal_code', '');
                    ?><span style="font-weight: bold; background-color: yellow"><?php
                    _e('NOT SAVED: Code was not saved because it causes a PHP FATAL ERROR.', 'add-actions-and-filters');
                    ?></span><?php
                }
                ?>
            </p>
            <label
                for="code"><?php _e('Put PHP code here to define functions and add them as <a target="_addactions" href="http://codex.wordpress.org/Function_Reference/add_action">actions</a> or <a target="_addfilter" href="http://codex.wordpress.org/Function_Reference/add_filter">filters</a>. Also add <a target="_scripts" href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script">scripts</a> and <a target="_styles" href="http://codex.wordpress.org/Function_Reference/wp_enqueue_style">styles</a>.', 'add-actions-and-filters'); ?></label>
            <textarea id="code" style="height: 650px; width: 100%;"
                      name="test_1"><?php echo $displayCode; ?></textarea>
        </form>

        <script language="Javascript" type="text/javascript">
            // initialisation
            editAreaLoader.init({
                id: "code"	// id of the textarea to transform
                , start_highlight: true	// if start with highlight
                , allow_resize: "both", allow_toggle: true, word_wrap: true, language: "en", syntax: "php"
            });

            jQuery("#savecode").click(
                function () {
                    jQuery.ajax(
                        {
                            "url": "<?php echo admin_url('admin-ajax.php') ?>?action=addactionsandfilters_save",
                            "type": "POST",
                            "data": "code=" + encodeURIComponent(editAreaLoader.getValue("code")),
                            "success": function (data, textStatus) {
                                //jQuery("#codesavestatus").html(data);
                                location.reload();
                            },
                            "error": function (textStatus, errorThrown) {
                                jQuery("#codesavestatus").html(errorThrown);
                            },
                            "beforeSend": function () {
                                jQuery("#codesavestatus").html('<img src="<?php echo plugins_url('img/load.gif', __FILE__); ?>">');
                            }
                        }
                    );
                    return false;
                });
        </script>
        <?php
    }

}