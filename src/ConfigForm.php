<?php
namespace Fourn\AdminConfig;

use Encore\Admin\Form;
use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
//use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ConfigForm extends Form
{
    const SEPARATOR = '-';

    public function configEdit()
    {
        $this->getConfigValues();
        return $this;
    }

    public function getConfigValues($is_edit = false)
    {
        $data = $this->model->pluck('value', 'name')->toArray();
        $values = [];
        foreach ($data as $key => $val) {
            $k = str_replace('.', self::SEPARATOR, $key);
            $values[$k] = $val;
        }

        $this->builder()->fields()->each(function (Field $field) use ($values, $is_edit) {
            if ($field instanceof Field\MultipleImage
            or $field instanceof Field\MultipleFile) {
                if (isset($values[$field->column()])) {
                    $values[$field->column()] = explode(',', $values[$field->column()]);
                }
            }
            if ($is_edit) {
                $field->setOriginal($values);
            } else {
                $field->fill($values);
            }
        });
    }

    public function configUpdate()
    {
        $data = Request::all();

        $isEditable = $this->isEditable($data);

        $data = $this->handleEditable($data);

        $data = $this->handleFileDelete($data);

        $this->getConfigValues(true);

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            if (!$isEditable) {
                return back()->withInput()->withErrors($validationMessages);
            } else {
                return response()->json(['errors' => array_dot($validationMessages->getMessages())], 422);
            }
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $updates = $this->prepareUpdate($this->updates);

            if ($updates) {
                foreach ($updates as $key => $val) {
                    $name = str_replace(self::SEPARATOR, '.', $key);
                    if (is_null($val))
                        $val = '';
                    if (is_array($val)) {
                        $val = implode(',', $val);
                    }
                    $this->model->updateOrCreate(
                        ['name'=>$name],
                        ['value'=>$val]
                    );
                }
            }
        });

        if (($result = $this->callSaved()) instanceof Response) {
            return $result;
        }

        if ($response = $this->ajaxResponse(trans('admin.update_succeeded'))) {
            return $response;
        }

        admin_toastr(trans('admin.save_succeeded'));

        return redirect(admin_base_path('admin-config'));
    }
}