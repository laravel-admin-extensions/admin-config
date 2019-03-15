<?php
namespace Fourn\AdminConfig;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigForm extends Form
{

    public function configEdit()
    {
        $data = $this->model->pluck('value', 'name')->toArray();
        $this->builder()->fields()->each(function (Field $field) use ($data) {
            $field->fill($data);
        });
        return $this;
    }

    public function configStore()
    {
        $data = Request::all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $inserts = $this->prepareInsert($this->updates);

            if ($inserts) {
                foreach ($inserts as $prefix => $items) {
                    if ($items) {
                        foreach ($items as $name => $value) {
                            $this->model->updateOrCreate(
                                ['name'=>"{$prefix}.{$name}"],
                                ['value'=>$value]
                            );
                        }
                    }
                }
            }
        });

        if ($response = $this->ajaxResponse(trans('admin.save_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterStore();
    }
}