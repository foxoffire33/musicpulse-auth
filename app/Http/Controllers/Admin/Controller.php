<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const PAGINATE_SIZE= 50;

    public function __construct()
    {
        Gate::authorize('admin');
    }
}