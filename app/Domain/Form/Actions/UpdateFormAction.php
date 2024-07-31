<?php

namespace App\Domain\Form\Actions;

use App\Infrastructure\Persistence\Models\Form;

class UpdateFormAction
{
    public function execute(int $id, array $data): ?bool
    {
       return Form::findOrFail($id)->update($data);
    }
}
