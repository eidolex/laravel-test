<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormSubmitRequest;
use App\Services\FormService;

class FormSubmitController extends Controller
{
    public function __invoke(FormSubmitRequest $request, FormService $formService)
    {
        $data = $request->safe()->all();

        $formService->submitForm($data['form_id'], $data);

        return response()->json([
            'message' => 'success',
        ]);
    }
}
