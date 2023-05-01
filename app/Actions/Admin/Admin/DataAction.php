<?php

namespace App\Actions\Admin\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;

class DataAction
{
    public function execute(Request $request)
    {
        return Admin::select('id', 'name', 'email', 'created_at')->orderBy('id')->get();
    }
}
