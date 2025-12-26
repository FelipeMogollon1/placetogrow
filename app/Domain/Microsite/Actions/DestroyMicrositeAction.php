<?php

namespace App\Domain\Microsite\Actions;

use App\Constants\PaymentStatus;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Subscription;
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

    public function hasSubscriptions(int $micrositeId): bool
    {
        return Subscription::where('microsite_id', $micrositeId)
            ->where('status', '<>', PaymentStatus::REJECTED->value)
            ->exists();
    }
}
