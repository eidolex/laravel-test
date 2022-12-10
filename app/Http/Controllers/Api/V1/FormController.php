<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormCreateRequest;
use App\Http\Resources\FormResource;
use App\Models\Form;
use App\Models\FormInput;
use App\Services\FormService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{

    /**
     * @var FormService
     */
    private $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormCreateRequest $request)
    {
        $userId = auth()->id();
        $data = $request->safe()->all();

        $form = $this->formService->createNewForm($userId, $data);

        return new FormResource($form);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $form = $this->formService->getForm($id, true);

        return new FormResource($form);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormCreateRequest $request, int $form)
    {
        $userId = auth()->id();
        $data = $request->safe()->all();

        $form = $this->formService->editForm($userId, $form, $data);

        return new FormResource($form);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = auth()->id();

        $this->formService->deleteForm($userId, $id);

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
