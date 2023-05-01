<?php

namespace App\Actions\Admin\Admin;

class DestroyAction
{
    public function execute($product)
    {
        $product->delete();
    }
}
