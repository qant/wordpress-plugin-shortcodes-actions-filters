<?php

/*
    "Add Shortcodes, Actions and Filters" Copyright (C) 2013-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('AddActionsAndFilters_ViewImportExport.php');
require_once('AddActionsAndFilters_DataModel.php');

class AddActionsAndFilters_ImportExportActions
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;


    public function __construct(&$plugin)
    {
        $this->plugin = $plugin;
    }

    public function handleImpExp()
    {
        $view = new AddActionsAndFilters_ViewImportExport($this->plugin);

        if (isset($_REQUEST['action'])) {
            switch ($_REQUEST['action']) {

                case 'import_scep':
                    $view->outputHeader();
                    $this->importScepShortCodes();
                    break;

                case 'exportall':
                    $view->outputHeader();
                    $this->exportToFile(null);
                    break;

                case 'importfile':
                    $view->outputHeader();
                    $this->importFromFile(null);
                    break;

                default:
                    $view->display();
            }
        } else {
            $view->display();
        }

    }

    public function exportToFile($file_name)
    {
        // todo
        echo 'Not yet implemented';
        print_r($_REQUEST);
    }

    public function importFromFile($file_name)
    {
        // todo
        echo 'Not yet implemented';
        print_r($_REQUEST);
    }

    public function importScepShortCodes()
    {
        $dataModel = new AddActionsAndFilters_DataModel($this->plugin, null);
        foreach ($_REQUEST as $key => $value) {
            if ($value == 'true') {
                $shortCode = array();
                $shortCode['shortcode'] = true;
                $shortCode['name'] = $key;
                $shortCode['description'] =  get_option("scep_description_$key");
                $shortCode['enabled'] =  get_option("scep_enabled_$key");
                $shortCode['code'] =  get_option("scep_phpcode_$key");
                //$buffer = get_option("scep_buffer_$key");
                //$param = get_option("scep_param_$key");

                $id = $dataModel->saveItem($shortCode);
                $url = $this->plugin->getAdminPageUrl() . "&id=$id&action=edit";
                echo __('Imported', 'add-actions-and-filters') .  " <a target='_blank' href='$url'>$key</a></br>";

                // Deactivate SCEP shortcode
                update_option("scep_enabled_$key", 0);
            }
        }

    }
}