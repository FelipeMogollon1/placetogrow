<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Facades\Storage;

class DestroyMicrositeAction
{
    public function execute(string $id): bool
    {
        $microsite = Microsite::findOrFail($id);

        if ($microsite->logo) {
            Storage::disk('public')->delete($microsite->logo);
        }

        return $microsite->delete();
    }
}
