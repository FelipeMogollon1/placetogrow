<?php

namespace App\Http\Controllers\Admin;

use App\Constants\DocumentTypes;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Models\Form;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormController extends Controller
{

    public function index()
    {

    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:forms,id',
            'configuration' => 'required|array',
        ]);

        $form = Form::findOrFail($validated['id']);
        $form->update([
            'configuration' => $validated['configuration']
        ]);

        return to_route('microsites.show')->with('success', 'User created.');
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {

    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'configuration' => 'required|array',
        ]);

        $form = Form::findOrFail($id);
        $form->update($validated);

        return redirect()->back();
    }




    public function destroy(string $id)
    {

    }
}
