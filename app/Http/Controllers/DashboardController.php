<?php

namespace App\Http\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $loans = DB::table('loan_details')->paginate(10);
        return view('dashboard', compact('loans'));
    }
    public function emiDetails()
    {
        if (!Schema::hasTable('emi_details')) {
            return view('emi-details');
        }
        $emis = DB::table('emi_details')->paginate(10);
        $columnNames = $this->fetchColumnNames();
        return view('emi-details', compact('emis', 'columnNames'));
    }
    public function processEmiData(Request $request)
    {
        try {
            $columnNames = $this->fetchColumnNames();

            DB::statement('DROP TABLE IF EXISTS emi_details');

            $query = 'CREATE TABLE emi_details (
            client_id INT,
            ' . implode(" DECIMAL(10, 2) DEFAULT 0.00, ", $columnNames) . ' DECIMAL(10, 2) DEFAULT 0.00
        )';
            DB::statement($query);

            $loanDetails = DB::table('loan_details')->get();
            foreach ($loanDetails as $loanDetail) {
                $emiValues = [];
                $amountExceptLastMonth = 0;

                $emiAmount = $loanDetail->loan_amount / $loanDetail->num_of_payment;
                $emiAmount = round($emiAmount, 2);
                $totalLoanAmount = $loanDetail->loan_amount ?? 0;

                $start = new DateTime($loanDetail->first_payment_date);
                $end = new DateTime($loanDetail->last_payment_date);
                $start->modify('first day of this month');
                $end->modify('last day of this month');
                $interval = DateInterval::createFromDateString('1 month');
                $period = new DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {
                    $monthKey = $dt->format("Y_M");
                    $emiValues[$monthKey] = $emiAmount;
                    // check for last month & adjust the amount
                    if ($dt->format("Y-m") == $end->format("Y-m")) {
                        $emiValues[$monthKey] = ($totalLoanAmount - $amountExceptLastMonth);
                    } else {
                        $emiValues[$monthKey] = $emiAmount;
                        $amountExceptLastMonth += $emiAmount;
                    }
                }

                $insertData = [
                    'client_id' => $loanDetail->client_id,
                ];

                $insertData = array_merge($insertData, $emiValues);
                DB::table('emi_details')->insert($insertData);
            }
            Session::flash('success', 'Process completed successfully!');
        } catch (\Exception $e) {
            Session::flash('error', 'An error occurred while processing the data!');
        }
        return redirect()->back();
    }
    public function fetchColumnNames()
    {
        $dates = DB::table('loan_details')
            ->select(DB::raw('MIN(first_payment_date) AS min_date, MAX(last_payment_date) AS max_date'))
            ->first();

        $minDate = $dates->min_date;
        $maxDate = $dates->max_date;

        $start = new DateTime($minDate);
        $end = new DateTime($maxDate);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $columnNames = [];
        foreach ($period as $dt) {
            $columnNames[] = $dt->format("Y_M");
        }

        return $columnNames;
    }
}
