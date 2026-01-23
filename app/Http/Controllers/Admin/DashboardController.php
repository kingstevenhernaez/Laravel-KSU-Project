<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = function_exists('getTenantId') ? (int) getTenantId() : 0;

        /**
         * 1) DataTables AJAX branch:
         * If DataTables calls this route expecting JSON, return JSON.
         */
        if ($request->ajax() && ($request->has('draw') || $request->has('columns') || $request->has('order'))) {
            return $this->transactionsDataTable($request, $tenantId);
        }

        /**
         * 2) Normal dashboard page branch (HTML view).
         */
        $cacheKey = "admin.dashboard.payload.tenant.{$tenantId}";

        $payload = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($tenantId) {
            $alumniQ = Alumni::query();
            $txQ     = Transaction::query();

            $alumniTable = $alumniQ->getModel()->getTable();
            $txTable     = $txQ->getModel()->getTable();

            if ($tenantId > 0) {
                if (Schema::hasColumn($alumniTable, 'tenant_id')) {
                    $alumniQ->where("{$alumniTable}.tenant_id", $tenantId);
                }
                if (Schema::hasColumn($txTable, 'tenant_id')) {
                    $txQ->where("{$txTable}.tenant_id", $tenantId);
                }
            }

            $totalAlumni = (int) (clone $alumniQ)->count();

            $currentMember = 0;
            if (Schema::hasTable('user_membership_plans')) {
                try {
                    $memberQ = DB::table('user_membership_plans');
                    if ($tenantId > 0 && Schema::hasColumn('user_membership_plans', 'tenant_id')) {
                        $memberQ->where('tenant_id', $tenantId);
                    }
                    if (Schema::hasColumn('user_membership_plans', 'status')) {
                        $memberQ->where('status', 1);
                    }
                    $currentMember = (int) $memberQ->count();
                } catch (\Throwable $e) {
                    $currentMember = 0;
                }
            }

            $totalUpcomingEvent = 0;
            if (Schema::hasTable('events')) {
                try {
                    $eventQ = DB::table('events');
                    if ($tenantId > 0 && Schema::hasColumn('events', 'tenant_id')) {
                        $eventQ->where('tenant_id', $tenantId);
                    }
                    if (Schema::hasColumn('events', 'date')) {
                        $eventQ->whereDate('date', '>=', now()->toDateString());
                    }
                    $totalUpcomingEvent = (int) $eventQ->count();
                } catch (\Throwable $e) {
                    $totalUpcomingEvent = 0;
                }
            }

            $memberThisMonth = 0;
            if (Schema::hasTable('user_membership_plans') && Schema::hasColumn('user_membership_plans', 'created_at')) {
                try {
                    $m = DB::table('user_membership_plans')
                        ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);

                    if ($tenantId > 0 && Schema::hasColumn('user_membership_plans', 'tenant_id')) {
                        $m->where('tenant_id', $tenantId);
                    }
                    if (Schema::hasColumn('user_membership_plans', 'status')) {
                        $m->where('status', 1);
                    }

                    $memberThisMonth = (int) $m->count();
                } catch (\Throwable $e) {
                    $memberThisMonth = 0;
                }
            }

            $transactionThisMonth = 0;
            if (Schema::hasColumn($txTable, 'created_at')) {
                $monthTx = (clone $txQ)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                $transactionThisMonth = (float) $monthTx->sum('amount');
            } else {
                $transactionThisMonth = (float) (clone $txQ)->sum('amount');
            }

            $dayList = '';
            $chartPrice = '';
            if (Schema::hasColumn($txTable, 'created_at')) {
                try {
                    $daily = (clone $txQ)
                        ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
                        ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                        ->groupBy('day')
                        ->orderBy('day')
                        ->get();

                    $days = [];
                    $totals = [];
                    foreach ($daily as $row) {
                        $days[] = $row->day;
                        $totals[] = (float) $row->total;
                    }
                    $dayList = implode(',', $days);
                    $chartPrice = implode(',', $totals);
                } catch (\Throwable $e) {
                    $dayList = '';
                    $chartPrice = '';
                }
            }

            $eventNames = '';
            $totalTickets = '';
            if (Schema::hasTable('event_tickets')) {
                try {
                    $q = DB::table('event_tickets');
                    if (Schema::hasColumn('event_tickets', 'event_id')) {
                        $rows = $q->selectRaw('event_id, COUNT(*) as total')
                            ->groupBy('event_id')
                            ->orderByDesc('total')
                            ->limit(10)
                            ->get();

                        $names = [];
                        $totals = [];
                        foreach ($rows as $r) {
                            $label = 'Event #' . $r->event_id;
                            if (Schema::hasTable('events')) {
                                $ev = DB::table('events')->where('id', $r->event_id);
                                if ($tenantId > 0 && Schema::hasColumn('events', 'tenant_id')) {
                                    $ev->where('tenant_id', $tenantId);
                                }
                                $evRow = $ev->first();
                                if ($evRow) {
                                    foreach (['title', 'name', 'event_name'] as $col) {
                                        if (isset($evRow->$col) && $evRow->$col) {
                                            $label = $evRow->$col;
                                            break;
                                        }
                                    }
                                }
                            }
                            $names[] = $label;
                            $totals[] = (int) $r->total;
                        }

                        $eventNames = implode(',', $names);
                        $totalTickets = implode(',', $totals);
                    }
                } catch (\Throwable $e) {
                    $eventNames = '';
                    $totalTickets = '';
                }
            }

            return [
                'pageTitle'            => 'Dashboard',

                'totalAlumni'          => $totalAlumni,
                'currentMember'        => $currentMember,
                'totalUpcomingEvent'   => $totalUpcomingEvent,
                'memberThisMonth'      => $memberThisMonth,
                'transactionThisMonth' => $transactionThisMonth,

                'dayList'              => $dayList,
                'chartPrice'           => $chartPrice,
                'eventNames'           => $eventNames,
                'totalTickets'         => $totalTickets,
            ];
        });

        return view('admin.dashboard', $payload);
    }

    private function transactionsDataTable(Request $request, int $tenantId)
    {
        $draw   = (int) $request->input('draw', 1);
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $base = Transaction::query();
        $txTable = $base->getModel()->getTable();

        if ($tenantId > 0 && Schema::hasColumn($txTable, 'tenant_id')) {
            $base->where("{$txTable}.tenant_id", $tenantId);
        }

        // total counts
        $recordsTotal = (clone $base)->count();
        $recordsFiltered = $recordsTotal;

        // Sorting (safe fallback to created_at desc)
        $base->orderBy(
            Schema::hasColumn($txTable, 'created_at') ? 'created_at' : $base->getModel()->getKeyName(),
            'desc'
        );

        // Paging
        $rows = (clone $base)->skip($start)->take($length)->get();

        // Build row output; keep it tolerant to missing columns/relations
        $data = $rows->map(function ($tx) {
            $name = '';
            try {
                if (method_exists($tx, 'user') && $tx->relationLoaded('user')) {
                    $name = $tx->user?->name ?? '';
                } elseif (method_exists($tx, 'user')) {
                    $name = $tx->user?->name ?? '';
                }
            } catch (\Throwable $e) {
                $name = '';
            }

            $purpose = $tx->purpose ?? ($tx->type ?? '');
            $trxId   = $tx->transaction_id ?? ($tx->trx_id ?? $tx->id);
            $method  = $tx->payment_method ?? ($tx->method ?? '');
            $date    = $tx->created_at ? $tx->created_at->format('Y-m-d H:i:s') : '';
            $amount  = $tx->amount ?? 0;

            return [
                'name'            => $name,
                'purpose'         => $purpose,
                'transaction_id'  => (string) $trxId,
                'payment_method'  => (string) $method,
                'created_at'      => $date,
                'amount'          => $amount,
            ];
        })->values();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
}
