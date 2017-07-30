<?php

namespace App\Http\Controllers\Screens;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Screen;
use Illuminate\Http\Request;
use Session;

class ScreensController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $screens = Screen::where('title', 'LIKE', "%$keyword%")
				->orWhere('message', 'LIKE', "%$keyword%")
				->orWhere('icon', 'LIKE', "%$keyword%")
				->orWhere('order', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $screens = Screen::paginate($perPage);
        }

        return view('screens.screens.index', compact('screens'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('screens.screens.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'title' => 'required',
			'message' => 'required',
			'order' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        Screen::create($requestData);

        Session::flash('flash_message', 'Screen added!');

        return redirect('admin/screens');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $screen = Screen::findOrFail($id);

        return view('screens.screens.show', compact('screen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $screen = Screen::findOrFail($id);

        return view('screens.screens.edit', compact('screen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
			'title' => 'required',
			'message' => 'required',
			'order' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        $iconPath = '';
        if ($request->hasFile('icon')) {
         $iconPath = $request->icon->store('images','public');
         $requestData['icon']=$iconPath;
        }
        
        $screen = Screen::findOrFail($id);
        $screen->update($requestData);

        Session::flash('flash_message', 'Screen updated!');

        return redirect('admin/screens');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Screen::destroy($id);

        Session::flash('flash_message', 'Screen deleted!');

        return redirect('admin/screens');
    }
}
