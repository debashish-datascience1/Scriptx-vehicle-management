        <?php
        $fuel = new FuelModel();
		$fuel->vehicle_id = $request->get('vehicle_id');
		$fuel->user_id = $request->get('user_id');
		$condition = FuelModel::orderBy('id', 'desc')->where('vehicle_id', $request->get('vehicle_id'))->first();
		
		if ($condition != null) {

			$fuel->start_meter = $request->get('start_meter');
			$fuel->end_meter = "0";
			$fuel->consumption = "0";
			$condition->end_meter = $end = $request->get('start_meter');
			
			if ($request->get('qty') == 0) {
				$condition->consumption = $con = 0;
			} else {
				$condition->consumption = $con = ($end - $condition->start_meter) / $condition->qty;
			}
			
			$condition->save();

		} else {

			$fuel->start_meter = $request->get('start_meter');
			$fuel->end_meter = "0";
			$fuel->consumption = "0";

		}
		$fuel->reference = $request->get('reference');
		$fuel->province = $request->get('province');
		$fuel->note = $request->get('note');
		$fuel->qty = $request->get('qty');
		$fuel->fuel_from = $request->get('fuel_from');
		$fuel->fuel_type = $request->get('fuel_type');
		$fuel->vendor_name = $request->get('vendor_name');
		$fuel->cost_per_unit = $request->get('cost_per_unit');
		$fuel->date = $request->get('date');
		$fuel->complete = $request->get("complete");
		$fuel->save();

		// $expense = new Expense();
		// $expense->vehicle_id = $request->get('vehicle_id');
		// $expense->user_id = $request->get('user_id');
		// $expense->expense_type = '8';
		// $expense->comment = $request->get('note');
		// $expense->date = $request->get('date');
		// $amount = $request->get('qty') * $request->get('cost_per_unit');
		// $expense->amount = $amount;
		// $expense->exp_id = $fuel->id;
		// $expense->save();


		// Accounting
		if(!empty($request->qty) && !empty($request->cost_per_unit)){
			$account['from_id'] = $fuel->id; //Fuel id
			$account['type'] = 24; //Debit 
			$account['param_id'] = 20; //From Fuel
			$account['advance_for'] = null; //No advance given
			$account['total'] = $request->get('qty') * $request->get('cost_per_unit');
			

			$transid = Transaction::create($account)->id;
			$trash = ['type'=>24,'from'=>20,'id'=>$transid];
			$transaction_id = Helper::transaction_id($trash);
			Transaction::find($transid)->update(['transaction_id'=>$transaction_id]);
			
			$income['transaction_id'] = $transid;
			$income['payment_method'] = 16; //Cash
			$income['date'] = date("Y-m-d H:i:s");
			$income['amount'] = 0;
			$income['remaining'] = $request->get('qty') * $request->get('cost_per_unit');;
			$income['remarks'] = null;

            IncomeExpense::create($income);
        }
		VehicleModel::where('id', $request->vehicle_id)->update(['mileage' => $request->start_meter]);
		return redirect('admin/fuel');