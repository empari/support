<?php namespace Empari\Support\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (class_exists(\Form::class)) {
            $this->registerCheckBox();
            $this->registerError();
            $this->registerFormGroup();
            $this->registerFormGroupInput();
            $this->registerSearch();
            $this->registerButtonsSubmitForm();
        }
    }

    public function registerError()
    {
        \Form::macro('error', function ($field, $errors){
            if(!str_contains($field, '.*') && $errors->has($field) || count($errors->get($field)) > 0){
                return view('errors.error_field', compact('field'));
            }
            return null;
        });
    }

    public function registerCheckBox()
    {
        \Form::macro('check', function ($field, $name, $value) {
            $checkbox = \Form::checkbox($field, $value, $value, ['id' => $field, 'class' => 'styled']);
            return
                "<div class=\"checkbox checkbox-primary\">
                    {$checkbox}
                    <label for=\"{$field}\">{$name}</label>
                </div>";
        });
    }

    public function registerFormGroup()
    {
        \Form::macro('openFormGroup', function($field = null, $errors = null){
            $result = false;
            if($field != null && $errors != null){
                if(is_array($field)){
                    foreach ($field as $value) {
                        if(!str_contains($value, '.*') && $errors->has($value) || count($errors->get($value)) > 0){
                            $result = true;
                            break;
                        }
                    }
                } else {
                    if(!str_contains($field, '.*') && $errors->has($field) || count($errors->get($field)) > 0){
                        $result = true;
                    }
                }
            }
            $hasError = $result ? ' has-error' : '';
            return "<div class=\"form-group{$hasError}\">";
        });

        \Form::macro('closeFormGroup', function(){
            return "</div>";
        });
    }

    public function registerFormGroupInput()
    {
        \Form::macro('formGroup', function ($field, $label, $errors) {
            $class_erro = $errors->has($field) ? 'has-error' : '';
            $string = "<div class=\"form-group $class_erro \">";
            $string .= \Form::label($field, $label, ['class' => 'control-label']);
            $string .= \Form::text($field, null, ['class' => 'form-control']);
            if ($class_erro):
                $string .= "<span class='help-block'><strong>{$errors->first($field)}</strong></span>";
            endif;
            $string .= "</div>";
            return $string;
        });
    }

    public function registerSearch()
    {
        // Atualizar mix scss
        // See: https://bootsnipp.com/snippets/featured/custom-search-input
        \Html::macro('search', function ($field) {
            $input = \Form::text($field, null, ['class' => 'form-control input-lg', 'placeholder' => 'Buscar']);
            $button = \Button::withValue()->prependIcon(\Icon::search())->submit();
            return "<div id=\"custom-search-input\" style=\"margin-top: 10px;\">
                        <div class=\"input-group col-md-12\">
                            {$input}
                            <span class=\"input-group-btn\">{$button}</span>
                        </div>
                    </div>";
        });
    }

    public function registerButtonsSubmitForm()
    {
        \Form::macro('btnSubmit', function () {
            return \Form::openFormGroup().
                    \Button::primary(\Icon::floppyDisk(). ' Salvar')->submit()->addAttributes(['style' => 'margin-right: 0.5em;']).
                    \Button::normal(\Icon::floppySaved(). ' Cancelar')->asLinkto(\URL::previous()).
                    \Form::closeFormGroup();
        });
    }
}

