<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'label',
        'input_type',
        'rules',
        'choices',
        'attributes',
        'input_order',
    ];

    protected $casts = [
        'choices' => AsArrayObject::class,
        'attributes' => AsArrayObject::class,
        'input_type' => InputType::class,
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
