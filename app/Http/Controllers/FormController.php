<?php

namespace App\Http\Controllers;

use App\Services\FormService;
use Illuminate\Http\Request;

class FormController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($slug, FormService $formService)
    {
        $form = $formService->getFormBySlug($slug);
        return view('form', [
            'form' => $form,
        ]);
    }
}
