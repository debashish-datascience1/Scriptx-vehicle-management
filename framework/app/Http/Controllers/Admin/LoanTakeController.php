<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\LoanTake;
use App\Model\LoanReturn;
use App\Model\User;
use Illuminate\Http\Request;
use Auth;
use DB;


class LoanTakeController extends Controller
{
    public function index()
    {
        $loanTakes = LoanTake::orderBy('created_at', 'desc')->paginate(10);
        return view('loan_take.index', compact('loanTakes'));
    }

    public function create()
    {
        return view('loan_take.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'from' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $validatedData['remaining_amount'] = $validatedData['amount'];

        LoanTake::create($validatedData);

        return redirect()->route('loan-take.index')
            ->with('success', 'Loan take entry created successfully.');
    }

    public function edit($id)
    {
        $loanTake = LoanTake::findOrFail($id);
        return view('loan_take.edit', compact('loanTake'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'from' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);
        $validatedData['remaining_amount'] = $validatedData['amount'];
        $loanTake = LoanTake::findOrFail($id);
        $loanTake->update($validatedData);

        return redirect()->route('loan-take.index')
            ->with('success', 'Loan take entry updated successfully.');
    }

    public function destroy($id)
    {
        $loanTake = LoanTake::findOrFail($id);
        $loanTake->delete();

        return redirect()->route('loan-take.index')
            ->with('success', 'Loan take entry deleted successfully.');
    }

    public function returnLoan($id)
    {
        $loanTake = LoanTake::findOrFail($id);
        return view('loan_take.return', compact('loanTake'));
    }

    public function processReturn(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0|max:' . $request->remaining_amount,
        ]);

        $loanTake = LoanTake::findOrFail($id);

        DB::transaction(function () use ($loanTake, $validatedData) {
            LoanReturn::create([
                'loan_take_id' => $loanTake->id,
                'date' => $validatedData['date'],
                'amount' => $validatedData['amount'],
            ]);

            $loanTake->remaining_amount -= $validatedData['amount'];
            $loanTake->save();
        });

        return redirect()->route('loan-take.show', $id)
            ->with('success', 'Loan return processed successfully.');
    }

    public function show($id)
    {
        $loanTake = LoanTake::with('returns')->findOrFail($id);
        return view('loan_take.show', compact('loanTake'));
    }
}