<?php

namespace App\Enums;

enum InputType: string
{

    case Text = 'text';
    case Url = 'url';
    case TextArea = 'textarea';

    case Color = 'color';

    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case Select = 'select';

    case Tel = 'tel';
    case Number = 'number';
    case Range = 'range';

    case File = 'file';
    case Email = 'email';

    case Date = 'date';
    case DateTimeLocal = 'datetime-local';
    case Time = 'time';
    case Week = 'week';
    case Month = 'month';
}
