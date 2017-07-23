<?php

namespace App\Http\Controllers\Loans;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Loan;
use Illuminate\Http\Request;
use Session;

class LoanController extends Controller
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
            $loan = Loan::where('customer_id', 'LIKE', "%$keyword%")
				->orWhere('amount_requested', 'LIKE', "%$keyword%")
				->orWhere('amount_processed', 'LIKE', "%$keyword%")
				->orWhere('daily_interest', 'LIKE', "%$keyword%")
				->orWhere('fees', 'LIKE', "%$keyword%")
				->orWhere('total', 'LIKE', "%$keyword%")
				->orWhere('transaction_ref', 'LIKE', "%$keyword%")
				->orWhere('paid', 'LIKE', "%$keyword%")
				->orWhere('invoiced', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->orWhere('net_salary', 'LIKE', "%$keyword%")
				->orWhere('date_disbursed', 'LIKE', "%$keyword%")
				->orWhere('deleted', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $loan = Loan::paginate($perPage);
        }

        return view('admin/loans.loan.index', compact('loan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/loans.loan.create');
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
			'customer_id' => 'required',
			'amount_requested' => 'required',
			'daily_interest' => 'required',
			'total' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        Loan::create($requestData);

        Session::flash('flash_message', 'Loan added!');

        return redirect('admin/loan');
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
        $loan = Loan::findOrFail($id);

        return view('admin/loans.loan.show', compact('loan'));
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
        $loan = Loan::findOrFail($id);

        return view('admin/loans.loan.edit', compact('loan'));
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
			'customer_id' => 'required',
			'amount_requested' => 'required',
			'daily_interest' => 'required',
			'total' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        $loan = Loan::findOrFail($id);
        $loan->update($requestData);

        Session::flash('flash_message', 'Loan updated!');

        return redirect('admin/loan');
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
        Loan::destroy($id);

        Session::flash('flash_message', 'Loan deleted!');

        return redirect('admin/loan');
    }
}
