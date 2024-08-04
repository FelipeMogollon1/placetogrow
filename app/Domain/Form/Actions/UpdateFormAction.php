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

        if (isset($data['head']) && $data['head']) {
            if ($form->head) {
                Storage::disk('public')->delete($form->head);
            }

            $data['head'] = $data['head']->store('head', ['disk' => 'public']);
        } else {
            unset($data['head']);
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
