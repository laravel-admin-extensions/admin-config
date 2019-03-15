<?php

namespace Fourn\AdminConfig\Http\Controllers;

use Encore\Admin\Form\Footer;
use Encore\Admin\Form\Tools;
use Encore\Admin\Layout\Content;
use Fourn\AdminConfig\AdminConfigModel;
use Fourn\AdminConfig\ConfigForm;
use Illuminate\Routing\Controller;

class AdminConfigController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('Title')
            ->description('Description')
            ->body($this->form()->configEdit());
    }

    protected function form()
    {
        $tabs = [
            'weixin',
        ];
        $fields = [
            'appid' => ['label'=>'公众号id', 'type'=>'text'],
            'appid2' => ['label'=>'公众号id2', 'type'=>'select', 'options'=>['op1', 'op2']],
            'appid3' => ['label'=>'公众号id3'],
            'appid4' => ['label'=>'公众号id4'],
        ];
        // 单一参数连续用法
        $publicFieldFoo = [
            'default', 'attribute', 'help', 'placeholder', 'rules', 'options',
            'rows', 'format', 'states', 'symbol', 'max', 'min',
            'uniqueName', 'removable', 'stacked'
        ];
        // $arguments
//        $tabs = config('adminconfig.admin_config_groups');
        $form = new ConfigForm(new AdminConfigModel());
        if ($tabs) {
            foreach ($tabs as $prefix => $title) {
                if (is_numeric($prefix) && is_string($title)) {
                    $prefix = $title;
                }
//                $fields = config('adminconfig.' . $prefix);
                $form->tab($title, function (ConfigForm $form) use ($fields, $prefix, $publicFieldFoo){
                    foreach ($fields as $name => $settings) {
                        if (is_numeric($name) && is_string($settings)) {
                            $name = $settings;
                            $settings = [];
                        }
                        $fieldType = isset($settings['type']) ? $settings['type'] : 'text';
                        $fieldName = "{$prefix}.{$name}";
                        if (isset($form::$availableFields[$fieldType])) {
                            // 构建field
                            $field = $form->$fieldType($fieldName, $settings['label'] ?? $name);

                            // 调用单一参数或者没有参数的蛇形方法
                            foreach ($settings as $settingKey => $settingValue) {
                                // 没有参数的蛇形方法
                                if (is_numeric($settingKey) && is_string($settingValue)) {
                                    $settingKey = $settingValue;
                                }
                                // 过滤出 单一参数或者没有参数的蛇形方法
                                if (in_array($settingKey, $publicFieldFoo)) {
                                    if ($settingKey = $settingValue) {
                                        $field->$settingKey();
                                    } else {
                                        $field->$settingKey($settingValue);
                                    }
                                }
                            }
                            if (isset($settings['callback']) && $settings['callback'] instanceof \Closure) {
                                call_user_func($settings['callback'], $field);
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
        $form->setTitle('Config');
        return $form;
    }

    public function store()
    {
        return $this->form()->configStore();
    }
}