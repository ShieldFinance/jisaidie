<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Transaction;
use Illuminate\Http\Request;
use Session;

class TransactionsController extends Controller
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
            $transactions = Transaction::where('service_id', 'LIKE', "%$keyword%")
				->orWhere('request', 'LIKE', "%$keyword%")
				->orWhere('response', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->orWhere('amount', 'LIKE', "%$keyword%")
				->orWhere('charges', 'LIKE', "%$keyword%")
				->orWhere('profile', 'LIKE', "%$keyword%")
                                ->orderBy('id','desc')
				->paginate($perPage);
        } else {
            $transactions = Transaction::paginate($perPage);
        }

        return view('admin/transactions.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/transactions.transactions.create');
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
			'service_id' => 'required',
			'request' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        Transaction::create($requestData);

        Session::flash('flash_message', 'Transaction added!');

        return redirect('admin/transactions');
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
        $transaction = Transaction::findOrFail($id);

        return view('admin/transactions.transactions.show', compact('transaction'));
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
        $transaction = Transaction::findOrFail($id);

        return view('admin/transactions.transactions.edit', compact('transaction'));
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
			'service_id' => 'required',
			'request' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        $transaction = Transaction::findOrFail($id);
        $transaction->update($requestData);

        Session::flash('flash_message', 'Transaction updated!');

        return redirect('admin/transactions');
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
        Transaction::destroy($id);

        Session::flash('flash_message', 'Transaction deleted!');

        return redirect('admin/transactions');
    }
}
