<?php

namespace App\Actions\Admin\Admin;

use App\Http\Requests\Admin\Admin\UpdateRequest;

class UpdateAction
{
    public function execute(UpdateRequest $request, $product)
    {
        $product->update($request->validated());
    }
}
