<?php

return array(
    /*
      |--------------------------------------------------------------------------
      | Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | The following language lines contain the default error messages used by
      | the validator class. Some of these rules have multiple versions such
      | as the size rules. Feel free to tweak each of these messages here.
      |
     */

    "accepted" => "The :attribute must be accepted.",
    "active_url" => "The :attribute is not a valid URL.",
    "after" => "The :attribute must be a date after :date.",
    "alpha" => "The :attribute may only contain letters.",
    "alpha_dash" => "The :attribute may only contain letters, numbers, and dashes.",
    "alpha_num" => "The :attribute may only contain letters and numbers.",
    "alpha_spaces"     => "El :attribute solo puede contener letras y espacios.",
    "array" => "The :attribute must be an array.",
    "before" => "The :attribute must be a date before :date.",
    "between" => array(
        "numeric" => "The :attribute must be between :min and :max.",
        "file" => "The :attribute must be between :min and :max kilobytes.",
        "string" => "The :attribute must be between :min and :max characters.",
        "array" => "The :attribute must have between :min and :max items.",
    ),
    "confirmed" => "The :attribute confirmation does not match.",
    "date" => "The :attribute is not a valid date.",
    "date_format" => "The :attribute does not match the format :format.",
    "different" => "The :attribute and :other must be different.",
    "digits" => "El campo :attribute debe contener :digits digitos.",
    "digits_between" => "The :attribute must be between :min and :max digits.",
    "email" => "El :attribute tiene un formato invalido.",
    "exists" => "El campo :attribute es invalido.",
    "image" => "The :attribute must be an image.",
    "in" => "The selected :attribute is invalid.",
    "integer" => "The :attribute must be an integer.",
    "ip" => "The :attribute must be a valid IP address.",
    "max" => array(
        "numeric" => "The :attribute may not be greater than :max.",
        "file" => "The :attribute may not be greater than :max kilobytes.",
        "string" => "The :attribute may not be greater than :max characters.",
        "array" => "The :attribute may not have more than :max items.",
    ),
    "mimes" => "The :attribute must be a file of type: :values.",
    "min" => array(
        "numeric" => "The :attribute must be at least :min.",
        "file" => "The :attribute must be at least :min kilobytes.",
        "string" => "The :attribute must be at least :min characters.",
        "array" => "The :attribute must have at least :min items.",
    ),
    "not_in" => "The selected :attribute is invalid.",
    "numeric" => "The :attribute must be a number.",
    "regex" => "The :attribute format is invalid.",
    "required" => "El campo :attribute es requerido.",
    "required_if" => "The :attribute field is required when :other is :value.",
    "required_with" => "The :attribute field is required when :values is present.",
    "required_with_all" => "The :attribute field is required when :values is present.",
    "required_without" => "The :attribute field is required when :values is not present.",
    "required_without_all" => "The :attribute field is required when none of :values are present.",
    "same" => "The :attribute and :other must match.",
    "size" => array(
        "numeric" => "The :attribute must be :size.",
        "file" => "The :attribute must be :size kilobytes.",
        "string" => "The :attribute must be :size characters.",
        "array" => "The :attribute must contain :size items.",
    ),
    "unique" => "The :attribute has already been taken.",
    "url" => "The :attribute format is invalid.",
    "time" => "El formato :attribute ingresado no es valido",
    "mobile_ar" => "Por favor, asegurate de ingresar el :attribute con el area (Ej. 011) y sin el prefijo 15.",
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Language Lines
      |--------------------------------------------------------------------------
      |
      | Here you may specify custom validation messages for attributes using the
      | convention "attribute.rule" to name the lines. This makes it quick to
      | specify a specific custom language line for a given attribute rule.
      |
     */
    'custom' => array(
        'attribute-name' => array(
            'rule-name' => 'custom-message',
        ),
        'category_name' => array(
            'unique' => 'El :attribute ya esta siendo utilizado',
        ),
        'password' => array(
            'required' => 'Por favor, ingresa tu :attribute',
        ),
        'mobile' => array(
            'required' => 'Por favor, ingresa tu :attribute',
            'exists' => 'Por favor, asegurate de que el :attribute ingresado es el tuyo',
        ),
        'name' => array(
            'required' => 'Por favor, ingresa tu :attribute',
        ),
        'id_user_type' => array(
            'required' => 'Por favor, selecciona el :attribute',
        )
    ),
    /*
      |--------------------------------------------------------------------------
      | Custom Validation Attributes
      |--------------------------------------------------------------------------
      |
      | The following language lines are used to swap attribute place-holders
      | with something more reader friendly such as E-Mail Address instead
      | of "email". This simply helps us make messages a little cleaner.
      |
     */
    'attributes' => array(
        'category_name' => 'Nombre de categor&iacute;a',
        'password' => 'clave',
        'mobile' => 'numero de celular',
        'name' => 'nombre',
        'id_user_type' => 'tipo de cuenta',
    ),
);
