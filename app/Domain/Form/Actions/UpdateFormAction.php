<?php

namespace App\Domain\Form\Actions;

use App\Infrastructure\Persistence\Models\Form;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Facades\Storage;

class UpdateFormAction
{
    public function execute(int $id, array $data): ?bool
    {
        $form = Form::findOrFail($id);

        if (isset($data['header']) && $data['header']) {
            if ($form->header) {
                Storage::disk('public')->delete($form->header);
            }

            $data['header'] = $data['header']->store('header', ['disk' => 'public']);
        } else {
            unset($data['header']);
        }

        if (isset($data['footer']) && $data['footer']) {
            if ($form->footer) {
                Storage::disk('public')->delete($form->footer);
            }

            $data['footer'] = $data['footer']->store('footer', ['disk' => 'public']);
        } else {
            unset($data['footer']);
        }

       return Form::findOrFail($id)->update($data);
    }
}
