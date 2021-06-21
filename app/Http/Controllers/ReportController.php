<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    private $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $monthlySales = $this->monthlySales();
        $weeklySales = $this->weeklySales();
        $monthlyInteractions = $this->monthlyInteraction();
        $weeklyInteractions = $this->weeklyInteractions();
        $monthlySold = $this->monthlySold();
        $weeklySold = $this->weeklySold();
        return response()->json(compact(
            "monthlySales",
            "weeklySales",
            "monthlyInteractions",
            "monthlySold",
            "weeklyInteractions",
            "weeklySold"
        ));
    }

    private function monthlySold(): array
    {
        $now = Carbon::now();
        $year = $now->format("Y");
        $monthlyData = [];

        for ($i = 1; $i <= 12; $i++) {
            $query = Purchase::whereYear("created_at", $year)
                ->whereMonth("created_at", $i)
                ->sum("count");
            $monthlyData[$this->months[$i - 1]] = (int) $query;
        }

        $interactions = Purchase::whereYear("created_at", $year)
            ->sum("count");
        $prevInteractions = Purchase::whereYear("created_at", $now->subYear())
            ->sum("count");

        $monthPos = array_search($now->monthName, array_keys($monthlyData), false);
        $monthlyData = array_slice($monthlyData, 0, $monthPos + 1);
        return [
            "data" => $monthlyData,
            "increased" => $this->increasedRate($interactions, $prevInteractions),
            "interactions" => (int) $interactions,
            "year" => "Year Of {$year}",
        ];
    }

    private function weeklySold(): array
    {
        $carbon = Carbon::now();
        $lastDayOfMonth = $carbon->day;
        $records = [];
        $start = $carbon->startOfMonth()->dayName;
        $numOfweeks = (int)ceil($lastDayOfMonth / 7);
        for ($i = 1; $i <= $lastDayOfMonth; $i++) {
            $weekly = Purchase::whereMonth("created_at", $carbon->format("m"))
                ->whereDay("created_at", $i)
                ->whereYear("created_at", $carbon->format("Y"))
                ->sum("count");
            //            $key = Carbon::parse("{$carbon->year}-{$carbon->month}-{$i}")->dayName;
            $records[$i] = (int)$weekly;
        }

        $interactions = Purchase::whereYear("created_at", $carbon->year)
            ->whereMonth("created_at", $carbon->month)
            ->sum("count");
        $prevInteractions = Purchase::whereYear("created_at", $carbon->year)
            ->whereMonth("created_at", $carbon->subMonth())
            ->sum("count");
        return [
            "data" => $records,
            "totalWeeks" => $numOfweeks,
            "for" => "Month of {$carbon->format('M Y')}",
            "start" => $start,
            "increased" => $this->increasedRate($interactions, $prevInteractions),
            "interactions" => (int)$interactions,
        ];
    }

    private function weeklyInteractions(): array
    {
        $carbon = Carbon::now();
        $lastDayOfMonth = $carbon->day;
        $records = [];
        $start = $carbon->startOfMonth()->dayName;
        $numOfweeks = (int)ceil($lastDayOfMonth / 7);
        for ($i = 1; $i <= $lastDayOfMonth; $i++) {
            $weekly = Payment::whereMonth("created_at", $carbon->format("m"))
                ->whereDay("created_at", $i)
                ->whereYear("created_at", $carbon->format("Y"))
                ->count();
            //            $key = Carbon::parse("{$carbon->year}-{$carbon->month}-{$i}")->dayName;
            $records[$i] = $weekly;
        }

        $interactions = Payment::whereYear("created_at", $carbon->year)
            ->whereMonth("created_at", $carbon->month)
            ->count();

        $prevInteractions = Payment::whereYear("created_at", $carbon->year)
            ->whereMonth("created_at", $carbon->subMonth())
            ->count();
        return [
            "data" => $records,
            "totalWeeks" => $numOfweeks,
            "for" => "Month of {$carbon->format('M Y')}",
            "start" => $start,
            "increased" => $this->increasedRate($interactions, $prevInteractions),
            "interactions" => $interactions,
        ];
    }
    public function monthlyInteraction(): array
    {
        $now = Carbon::now();
        $year = $now->format("Y");
        $monthlyData = [];

        for ($i = 1; $i <= 12; $i++) {
            $query = Payment::whereYear("created_at", $year)
                ->whereMonth("created_at", $i)
                ->count();
            $monthlyData[$this->months[$i - 1]] = $query;
        }

        $interactions = Payment::whereYear("created_at", $year)
            ->count();
        $prevInteractions = Payment::whereYear("created_at", $now->subYear())
            ->count();

        $monthPos = array_search($now->monthName, array_keys($monthlyData), false);
        $monthlyData = array_slice($monthlyData, 0, $monthPos + 1);
        return [
            "data" => $monthlyData,
            "increased" => $this->increasedRate($interactions, $prevInteractions),
            "interactions" => $interactions,
            "year" => "Year Of {$year}",
        ];
    }

    private function monthlySales(): array
    {
        $now = Carbon::now();
        $year = $now->format("Y");
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $yearlyQuery = Payment::whereYear("created_at", $year)
                ->whereMonth("created_at", $i)
                ->sum("amount");
            $monthlyData[$this->months[$i - 1]] = $yearlyQuery;
            $monthlyData[$this->months[$i - 1]] = (float)number_format($monthlyData[$this->months[$i - 1]], 2, ".", "");
        }

        $revenue = Payment::whereYear("created_at", $year)
            ->sum("amount");

        $prevRevenue = Payment::whereYear("created_at", $year - 1)
            ->sum("amount");

        if ($prevRevenue === 0 && $revenue === 0) {
            $increased = "0";
        } else if ($prevRevenue === 0) {
            $increased = "100";
        } else {
            $percent = ($revenue - $prevRevenue) / $prevRevenue * 100;
            $increased = number_format($percent, 1);
        }

        $monthPos = array_search($now->monthName, array_keys($monthlyData), false);
        $monthlyData = array_slice($monthlyData, 0, $monthPos + 1);

        return [
            "data" => $monthlyData,
            "revenue" => number_format($revenue, 2),
            "prev_revenue" => number_format($prevRevenue, 2),
            "increased" => $increased,
            "year" => "Year Of {$year}",
        ];
    }

    private function increasedRate(float $revenue, float $prevRevenue): string
    {
        if ($prevRevenue == 0 && $revenue == 0) {
            $increased = "0";
        } else if ($prevRevenue == 0) {
            $increased = "100";
        } else {
            $percent = ($revenue - $prevRevenue) / $prevRevenue * 100;
            $increased = number_format($percent, 1);
        }
        return $increased;
    }

    private function weeklySales(): array
    {
        $carbon = Carbon::now();
        $lastDayOfMonth = $carbon->day;
        $records = [];
        $start = $carbon->startOfMonth()->dayName;
        $numOfweeks = (int)ceil($lastDayOfMonth / 7);
        for ($i = 1; $i <= $lastDayOfMonth; $i++) {
            $weekly = Payment::whereMonth("created_at", $carbon->format("m"))
                ->whereDay("created_at", $i)
                ->whereYear("created_at", $carbon->format("Y"))
                ->sum("amount");
            //            $key = Carbon::parse("{$carbon->year}-{$carbon->month}-{$i}")->dayName;
            $records[$i] = (float)number_format($weekly, 2, ".", "");
        }

        $revenue = $this->totality($carbon);
        $prevRevenue = $this->totality($carbon, 1);

        return [
            "data" => $records,
            "totalWeeks" => $numOfweeks,
            "for" => "Month of {$carbon->format('M Y')}",
            "start" => $start,
            "increased" => $this->increasedRate($revenue, $prevRevenue),
            "prev_revenue" => number_format($prevRevenue, 2),
            "revenue" => $revenue
        ];
    }

    private function totality(Carbon $carbon, int $monthOffset = 0, int $yearOffset = 0): float
    {
        $newCarbon = $carbon;
        if ($monthOffset !== 0) {
            $newCarbon->subMonths($monthOffset);
        }
        if ($yearOffset !== 0) {
            $newCarbon->subYears($yearOffset);
        }
        return Payment::whereMonth("created_at", $newCarbon->format("m"))
            ->whereYear("created_at", $newCarbon->year)
            ->sum("amount");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
