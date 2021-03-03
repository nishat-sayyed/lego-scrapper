<?php

namespace App\Http\Controllers;

use App\Models\LegoItem;

class LegoItemController extends Controller
{
    public function index()
    {
        return LegoItem::all();
    }
}
