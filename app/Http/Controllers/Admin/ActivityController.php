<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Activity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $per_page = 20;

    public function index()
    {
        $activities = Activity::with('user')->paginate($this->per_page);

        return view('admin.activities.index')->with(['activities' => $activities]);
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
    }

    public function edit($id)
    {
        return view('admin.activities.edit');
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        return redirect()->back();
    }
}
