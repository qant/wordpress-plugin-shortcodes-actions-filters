<?php

/*
    "Add Shortcodes, Actions and Filters" Copyright (C) 2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

    public function __construct(&$plugin)
    {
        $this->plugin = $plugin;
    }

    public function display($item)
    {
        $this->outputCodeMirrorScriptsAndCss();
        $this->outputHeader();
        $this->outputCodeEditor($item);
    }


    /**
     * Add CodeMirror scripts for the code editor
     */
    public function outputCodeMirrorScriptsAndCss()
    {
        $libs = array(
            'lib/codemirror.js',
            'lib/codemirror.css',
            'addon/edit/matchbrackets.js',
            'mode/htmlmixed/htmlmixed.js',
            'mode/xml/xml.js',
            'mode/javascript/javascript.js',
            'mode/css/css.js',
            'mode/clike/clike.js',
            'mode/php/php.js',
        );
        $baseUrl = $this->plugin->getPluginFileUrl('codemirror-5.9');
        foreach ($libs as $lib) {
            if (substr($lib, -3) == '.js') {
                ?>
                <script src="<?php echo "$baseUrl/$lib" ?>"></script>
                <?php
            } else if (substr($lib, -4) == '.css') {
                ?>
                <link rel="stylesheet" href="<?php echo "$baseUrl/$lib" ?>">
                <?php
            }
        }
    }

    /**
     * Add top header table
     */
    public function outputHeader()
    {
        ?>
        <div class="wrap">
            <table width="100%">
                <tbody>
                <tr>
                    <td align="left"><h2><?php _e('Code Editor', 'add-actions-and-filters'); ?></h2></td>
                    <td align="right">
                        <a href="<?php echo $this->plugin->getAdminPageUrl() ?>">
                            <img width="128" height="50"
                                 src="<?php echo $this->plugin->getPluginFileUrl('img/icon-256x100.png') ?>">
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Output the main contents of the page including the code editor and metadata fields
     * @param $item array created by AddActionsAndFilters_DataModel
     */
    public function outputCodeEditor($item)
    {
        ?>
        <div class="wrap">

            <table width="100%">
                <tbody>
                <tr>
                    <td valign="top">
                        <label for="name"><?php _e('Name') ?></label>
                    </td>
                    <td valign="top">
                        <input id="name" type="text" value="<?php echo $item['name'] ?>" size="25"/>
                    </td>
                    <td valign="top">
                        <label for="description"><?php _e('Description') ?></label>
                    </td>
                    <td valign="top">
                        <textarea title="description" id="description"
                                  cols="80"><?php echo $item['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                        <input type="checkbox" id="activated" name="activated"
                               value="true" <?php if ($item['enabled']) echo 'checked' ?>>
                        <label for="enabled"><?php _e('Activated') ?></label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="shortcode" name="shortcode"
                               value="true" <?php if ($item['shortcode']) echo 'checked' ?>><label
                            for="shortcode"><?php _e('Shortcode') ?></label>
                    </td>
                </tr>
                </tbody>
            </table>

            <textarea title="code" id="code"><?php echo $item['code'] ?></textarea>

            <script>
                var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                    lineNumbers: true,
                    matchBrackets: true,
                    mode: "text/x-php",
                    indentUnit: 4,
                    indentWithTabs: true
                });
            </script>


            <div id="codesavestatus">&nbsp;</div>
            <?php submit_button('Save', 'primary', 'savecode'); ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery('#savecode').click(function () {
                        var item = {
                            <?php
                            if (isset($item['id'])) {
                                echo '"id": ' . $item['id'] . ',';
                            } ?>
                            "name": jQuery('#name').val(),
                            "description": jQuery('#description').val(),
                            "enabled": jQuery('#activated').is(':checked'),
                            "shortcode": jQuery('#shortcode').is(':checked'),
                            "code": editor.getValue()
                        };
                        //console.log(item); // debug
                        jQuery.ajax(
                            {
                                "url": "<?php echo admin_url('admin-ajax.php') ?>?action=addactionsandfilters_save",
                                "type": "POST",
                                "data": item,
                                "success": function (data, textStatus) {
                                    window.location.replace('<?php echo $this->plugin->getAdminPageUrl() ?>&id=' + data + '&action=edit');
                                },
                                "error": function (textStatus, errorThrown) {
                                    jQuery("#codesavestatus").html(textStatus.statusText);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                },
                                "beforeSend": function () {
                                    jQuery("#codesavestatus").html('<img src="<?php echo plugins_url('img/load.gif', __FILE__); ?>">');
                                }
                            }
                        );
                    })
                });
            </script>
        </div>

        <?php
    }

}