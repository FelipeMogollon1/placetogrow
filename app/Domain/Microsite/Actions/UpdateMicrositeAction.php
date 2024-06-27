<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class UpdateMicrositeAction
{
    public function execute(int $id, array $data): RedirectResponse
    {
        $microsite = Microsite::find($id);

        $data['logo'] = $data['logo'] ? $data['logo']->store('logo', ['disk' => 'public']) : $microsite->logo;

        $microsite->logo && $data['logo'] && Storage::disk('public')->delete($microsite->logo);

        $microsite->update($data);

        return to_route('microsites.index');
    }
}
