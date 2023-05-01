<?php

namespace App\Actions\Admin\Admin;

use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Models\Admin;

class StoreAction
{
    public function execute(StoreRequest $request)
    {
        return Admin::create(['name' => $request->validated()['name'],
            'email' => $request->validated()['email'],
            'password' => bcrypt($request->validated()['password'])]);
    }
}
