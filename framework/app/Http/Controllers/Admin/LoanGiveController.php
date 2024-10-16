<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\LoanGive;
use App\Model\LoanCollect;
use App\Model\User;
use Illuminate\Http\Request;
use Auth;
use DB;


class LoanGiveController extends Controller
{
    public function index()
    {
        $loanTakes = LoanGive::orderBy('created_at', 'desc')->paginate(10);
        return view('loan_give.index', compact('loanTakes'));
    }

    public function create()
    {
        return view('loan_give.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'from' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $validatedData['remaining_amount'] = $validatedData['amount'];

        LoanGive::create($validatedData);

        return redirect()->route('loan-give.index')
            ->with('success', 'Loan take entry created successfully.');
    }

    public function edit($id)
    {
        $loanTake = LoanGive::findOrFail($id);
        return view('loan_give.edit', compact('loanTake'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'from' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);
        $validatedData['remaining_amount'] = $validatedData['amount'];
        $loanTake = LoanGive::findOrFail($id);
        $loanTake->update($validatedData);

        return redirect()->route('loan-give.index')
            ->with('success', 'Loan take entry updated successfully.');
    }

    public function destroy($id)
    {
        $loanTake = LoanGive::findOrFail($id);
        $loanTake->delete();

        return redirect()->route('loan-give.index')
            ->with('success', 'Loan take entry deleted successfully.');
    }

    public function returnLoan($id)
    {
        $loanTake = LoanGive::findOrFail($id);
        return view('loan_give.return', compact('loanTake'));
    }

    public function processReturn(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0|max:' . $request->remaining_amount,
        ]);

        $loanGive = LoanGive::findOrFail($id);

        DB::transaction(function () use ($loanGive, $validatedData) {
            LoanCollect::create([
                'loan_give_id' => $loanGive->id,
                'date' => $validatedData['date'],
                'amount' => $validatedData['amount'],
            ]);

            $loanGive->remaining_amount -= $validatedData['amount'];
            $loanGive->save();
        });

        return redirect()->route('loan-give.show', $id)
            ->with('success', 'Loan return processed successfully.');
    }

    public function show($id)
    {
        $loanTake = LoanGive::with('returns')->findOrFail($id);
        return view('loan_give.show', compact('loanTake'));
    }
}