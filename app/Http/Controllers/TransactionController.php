<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $datas = DB::select("select transactions.id as id, no_transaction, count(transaction_id) as total_item, sum(quantity) as total_quantity
            from transactions join transaction_details on transactions.id = transaction_details.transaction_id
            group by transactions.no_transaction, transaction_details.transaction_id;
        ");

        return view('transaction.index', compact('datas'));
    }

    public function create()
    {
        return view('transaction.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        $trans = Transaction::create([
            'no_transaction'        => $request->transaction_no,
            'transaction_date'      => $request->transaction_date,
        ]);

        if (!$trans) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => $trans
            ], 400);
        }

        foreach ($request->item_name as $key => $value) {
            $td = TransactionDetail::create([
                'transaction_id'    => $trans->id,
                'item'              => $request->item_name[$key],
                'quantity'          => $request->quantity[$key],
            ]);
            if (!$td) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message'     => $td
                ], 400);
            }
        }

        DB::commit();
        return response()->json([
            'status'     => true,
            'message'   => 'mantap',
            'url'     => route('transaction.index'),
        ], 200);
    }

    public function edit($id)
    {
        $datas = Transaction::with('transactionDetails')->find($id);

        return view('transaction.edit', compact('datas'));
    }

    public function show($id)
    {
        $datas = Transaction::with('transactionDetails')->find($id);

        return view('transaction.detail', compact('datas'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $trans = Transaction::find($id);
        $trans->no_transaction        = $request->transaction_no;
        $trans->transaction_date      = $request->transaction_date;
        $trans->save();

        if (!$trans) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => $trans
            ], 400);
        }

        foreach ($request->id as $key => $value) {
            if ($value) {
                $td = TransactionDetail::find($value);
                $td->item              = $request->item_name[$key];
                $td->quantity          = $request->quantity[$key];
                $td->save();
            }else{
                $td = TransactionDetail::create([
                    'transaction_id'    => $id,
                    'item'              => $request->item_name[$key],
                    'quantity'          => $request->quantity[$key],
                ]);
            }

            if (!$td) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message'     => $td
                ], 400);
            }
        }

        $td_id = explode(',', $request->td_id);
        foreach ($td_id as $value) {
            $td = TransactionDetail::find($value);
            $td->delete();

            if (!$td) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message'     => $td
                ], 400);
            }
        }

        DB::commit();
        return response()->json([
            'status'     => true,
            'message'   => 'mantap',
            'url'     => route('transaction.index'),
        ], 200);


    }

    public function delete($id)
    {
        try {
            $ticket = Transaction::findOrfail($id);
            $ticket->transactionDetails()->delete();

            return response()->json([
                'error'     => false,
                'id'        => $id,
                'message'   => 'mantap',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error'     => true,
                'message'   => $th,
            ]);
        }
    }
}
