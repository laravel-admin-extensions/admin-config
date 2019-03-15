<?php

return [
    /*
     * --------------------------------------------------------------------------
     * Define configuration groups
     * --------------------------------------------------------------------------
     * Each configuration group will be rendered as a TAB page
     */
    'admin_config_groups' => [
        'system' => 'system',
        'site',
    ],
    /**
     * --------------------------------------------------------------------------
     * Define configuration items
     * --------------------------------------------------------------------------
     * 配置项包含字段
     * '***' => ['label'=>'***', 'type'=>'', 'help'=>'', 'options'=>[]],
     * name 配置键
     * label 配置标题
     * type 配置项输入类型
     * options 可选项等额外配置
     * help 描述文字
     * 获取：config('weixin') config('weixin.appid')
     */
    'system' => [
        'system_config_1' => ['label'=>'label_for_config_1'],
        'system_config_2' => ['help'=>'help_for_config_2'],
    ],
    'site' => [
        'site_config_1' => ['callback'=>function ($field){$field->help('help_info');}],
        'site_config_2'
    ]
];