<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Facades\Storage;

class UpdateMicrositeAction
{
    public function execute(int $id, array $data): bool
    {
        $microsite = Microsite::find($id);

        if (isset($data['logo']) && $data['logo']) {

            if ($microsite->logo) {
                Storage::disk('public')->delete($microsite->logo);
            }

            $data['logo'] = $data['logo']->store('logo', ['disk' => 'public']);
        }

        return $microsite->update($data);
    }
}
