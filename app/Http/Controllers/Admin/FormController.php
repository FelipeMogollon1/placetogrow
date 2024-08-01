<?php

namespace App\Http\Controllers\Admin;


use App\Domain\Form\Actions\UpdateFormAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Form\UpdateFormRequest;
use Illuminate\Http\RedirectResponse;


class FormController extends Controller
{
    public function update(UpdateFormRequest $request, string $id, UpdateFormAction $action): RedirectResponse
    {
        $action->execute($id,$request->validated());

        return redirect()->back()->with('success', 'Form updated.');
    }

}
