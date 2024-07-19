<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateMicrositeAction
{
    public function execute(int $id, array $data): bool
    {
        $microsite = Microsite::find($id);

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

        return $microsite->update($data);
    }
}
