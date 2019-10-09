<?php

namespace Fourn\AdminConfig\Http\Controllers;

use Encore\Admin\Form\Footer;
use Encore\Admin\Form\Tools;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;
use Fourn\AdminConfig\AdminConfigModel;
use Fourn\AdminConfig\ConfigForm;
use Illuminate\Routing\Controller;

class AdminConfigController extends Controller
{
    public $publicFieldFoo = [
        'default', 'attribute', 'help', 'placeholder', 'rules', 'options',
        'rows', 'format', 'states', 'symbol', 'max', 'min',
        'uniqueName', 'removable', 'stacked'
    ];

    public $rangeFoo = [
        'timeRange', 'dateRange', 'datetimeRange'
    ];

    public function index(Content $content)
    {
        return $content
            ->header(config('admin.extensions.admin-config.title'))
            ->description(config('admin.extensions.admin-config.description'))
            ->body($this->form()->configEdit());
    }

    protected function form()
    {
        $tabs = config('admin-config.admin_config_groups');
        $permissions = config('admin-config.admin_config_permissions');
        $form = new ConfigForm(new AdminConfigModel());
        if ($tabs) {
            foreach ($tabs as $prefix => $title) {
                // Skip building the tab if no permission
                if (!Admin::user()->isAdministrator() && !empty($permissions[$prefix]) && !Admin::user()->inRoles($permissions[$prefix])) {
                    continue;
                }

                // When prefixes are configured only, the label key value can be undefined
                if (is_numeric($prefix) && is_string($title)) {
                    $prefix = $title;
                }
                $fields = config('admin-config.' . $prefix);
                $form->tab($title, function (ConfigForm $form) use ($fields, $prefix){
                    foreach ($fields as $name => $settings) {

                        // When only the field name is configured, the field key value can be undefined
                        if (is_numeric($name) && is_string($settings)) {
                            $name = $settings;
                            $settings = [];
                        }

                        // The field type must have type as the key name
                        $fieldType = isset($settings['type']) ? $settings['type'] : 'text';
                        $fieldName = $prefix . ConfigForm::SEPARATOR . $name;
                        unset($settings['type']);

                        // Determine whether the field type is supported
                        if (isset($form::$availableFields[$fieldType])) {

                            foreach ($settings as $settingKey => $settingValue) {

                                // Filter out serpentine invocation methods in the configuration to support single or no arguments
                                $key = $settingKey;
                                // Snake methods with no parameters
                                if (is_numeric($settingKey) && is_string($settingValue)) {
                                    $settingKey = $settingValue;
                                }
                                if (in_array($settingKey, $this->getFieldFoo())) {
                                    if ($settingKey == $settingValue) {
                                        $snakelikes[$settingValue] = $settingValue;
                                    } else {
                                        $snakelikes[$settingKey] = $settingValue;
                                    }
                                    unset($settings[$key]);
                                }

                                // Filter out the callback method
                                if ($settingValue instanceof \Closure) {
                                    $callbacks[] = $settingValue;
                                    unset($settings[$key]);
                                }
                            }

                            // Build the field with the remaining parameters
                            $settings = array_values($settings);
                            if (in_array($fieldType, $this->rangeFoo)) {
                                $fieldNameEnd = $fieldName . ConfigForm::SEPARATOR . 'end';
                                $fieldName = $fieldName . ConfigForm::SEPARATOR . 'start';
                                array_unshift($settings, $fieldNameEnd);
                            }
                            $field = $form->$fieldType($fieldName, ...$settings);


                            // Call the snake method
                            if (isset($snakelikes)) {
                                foreach ($snakelikes as $foo => $params) {
                                    if ($foo == $params) {
                                        $field->$foo();
                                    } else {
                                        $field->$foo($params);
                                    }
                                }
                                unset($snakelikes);
                            }

                            // Call the callback method
                            if (isset($callbacks)) {
                                foreach ($callbacks as $callback) {
                                    call_user_func($callback, $field);
                                }
                                unset($callback);
                            }
                        }
                    }
                });
            }
        }
        $form->setAction(admin_base_path('admin-config'));
        $form->tools(function (Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->footer(function (Footer $footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        $form->setTitle(config('admin.extensions.admin-config.action', ' '));
        return $form;
    }

    public function update()
    {
        return $this->form()->configUpdate();
    }

    protected function getFieldFoo()
    {
        return $this->publicFieldFoo;
    }
}