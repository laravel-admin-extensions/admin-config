<?php

namespace Fourn\AdminConfig;

use Illuminate\Database\Eloquent\Model;

class AdminConfigModel extends Model
{
    protected $fillable = ['name', 'value'];

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.config.table', 'admin_config'));
    }
}
