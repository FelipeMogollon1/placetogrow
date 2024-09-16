<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Form;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateMicrositeAction
{
    public function execute(int $id, array $data): bool
    {
        $microsite = Microsite::findOrFail($id);

        if (isset($data['name']) && $microsite->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name'], '_') . '_' . Str::random(10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        }

        if (isset($data['logo']) && $data['logo']) {
            if ($microsite->logo) {
                Storage::disk('public')->delete($microsite->logo);
            }

            $data['logo'] = $data['logo']->store('logo', ['disk' => 'public']);
        } else {
            unset($data['logo']);
        }

        if (isset($data['microsite_type'])) {

            $form = Form::where('id', $microsite->form_id)->first();
            if ($form) {
                $form->update([
                    'configuration' => $this->jsonForm($data['microsite_type']),
                ]);
            }

            $data['form_id'] = $form->id;
        }

        return $microsite->update($data);
    }

    public function jsonForm(string $microsite_type): array
    {
        $filePath = base_path("app/Domain/Form/Json/{$microsite_type}.json");

        if (!file_exists($filePath)) {
            return [];
        }

        $json = file_get_contents($filePath);
        return json_decode($json, true);
    }
}
