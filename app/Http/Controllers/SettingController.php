<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SettingController extends Controller
{
    public function page()
    {

        return Inertia::render('settings/index');
    }
}
