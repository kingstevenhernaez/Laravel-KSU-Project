<?php

namespace App\Http\Services;

use App\Models\News;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Alumni;
use App\Models\EventTicket;
use App\Models\Notice;
use App\Models\JobPost;
use App\Models\Transaction;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use App\Models\UserMembershipPlan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use ResponseTrait;

    /**
     * Keep this short so changes reflect quickly while still removing the 3–10s delays.
     * You can increase later (e.g., 300 seconds) once stable.
     */
    private int $ttlSeconds = 60;

    private function cacheKey(string $suffix, $tenantId): string
    {
        return "ksu:dashboard:tenant:{$tenantId}:{$suffix}";
    }

    public function getUpcomingEvent()
    {
        $tenantId = getTenantId();

        $upcomingEvents = Cache::remember(
            $this->cacheKey('upcoming_events', $tenantId),
            $this->ttlSeconds,
            function () use ($tenantId) {
                return Event::where('tenant_id', $tenantId)
                    ->where('status', STATUS_ACTIVE)
                    ->where('date', '>', now())
                    ->orderBy('date', 'ASC')
                    ->with('category')
                    ->limit(2)
                    ->get();
            }
        );

        return $this->success($upcomingEvents);
    }

    public function getLatestJobs()
    {
        $tenantId = getTenantId();

        $latestJobs = Cache::remember(
            $this->cacheKey('latest_jobs', $tenantId),
            $this->ttlSeconds,
            function () use ($tenantId) {
                return JobPost::where('tenant_id', $tenantId)
                    ->where('status', STATUS_ACTIVE)
                    ->orderBy('application_deadline', 'DESC')
                    ->limit(2)
                    ->get();
            }
        );

        return $this->success($latestJobs);
    }

    public function getLatestNotice()
    {
        $tenantId = getTenantId();

        $latestNotices = Cache::remember(
            $this->cacheKey('latest_notices', $tenantId),
            $this->ttlSeconds,
            function () use ($tenantId) {
                return Notice::where('tenant_id', $tenantId)
                    ->where('status', STATUS_ACTIVE)
                    ->orderBy('id', 'DESC')
                    ->limit(2)
                    ->get();
            }
        );

        return $this->success($latestNotices);
    }

    public function getLatestNews()
    {
        $tenantId = getTenantId();

        $latestNews = Cache::remember(
            $this->cacheKey('latest_news', $tenantId),
            $this->ttlSeconds,
            function () use ($tenantId) {
                return News::where('tenant_id', $tenantId)
                    ->where('status', STATUS_ACTIVE)
                    ->orderBy('id', 'DESC')
                    ->with(['category', 'author'])
                    ->limit(2)
                    ->get();
            }
        );

        return $this->success($latestNews);
    }

    public function getMorePost($request)
    {
        // Keep as-is (frontend usage). Pagination already limits results.
        $data['posts'] = Post::orderBy('id', 'DESC')
            ->where('status', STATUS_ACTIVE)
            ->where('tenant_id', getTenantId())
            ->with(['comments', 'likes:id', 'author', 'media.file_manager'])
            ->withCount('replies')
            ->paginate(4);

        $response['html'] = View::make('alumni.partials.post', $data)->render();
        return $this->success($response);
    }

    /**
     * Dashboard metrics
     */

    public function totalAlumni($tenant_id)
    {
        // SAFEST + FASTEST: count users directly (avoids join scan).
        // Does not change any workflow; used only for dashboard number.
        return Cache::remember(
            $this->cacheKey('total_alumni', $tenant_id),
            $this->ttlSeconds,
            function () use ($tenant_id) {
                return User::where('tenant_id', $tenant_id)
                    ->where('status', STATUS_ACTIVE)
                    ->where('is_alumni', 1)
                    ->count();
            }
        );
    }

    public function currentMember($tenant_id)
    {
        return Cache::remember(
            $this->cacheKey('current_member', $tenant_id),
            $this->ttlSeconds,
            function () use ($tenant_id) {
                return User::where('tenant_id', $tenant_id)
                    ->where('users.is_alumni', 1)
                    ->whereHas('currentMembership')
                    ->count();
            }
        );
    }

    public function totalUpcomingEvent($tenant_id)
    {
        return Cache::remember(
            $this->cacheKey('upcoming_event_count', $tenant_id),
            $this->ttlSeconds,
            function () use ($tenant_id) {
                return Event::where('tenant_id', $tenant_id)
                    ->where('status', STATUS_ACTIVE)
                    ->where('date', '>', now())
                    ->count();
            }
        );
    }

    public function memberThisMonth($tenant_id)
    {
        // Index-friendly month range
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        return Cache::remember(
            $this->cacheKey('member_this_month', $tenant_id),
            $this->ttlSeconds,
            function () use ($tenant_id, $start, $end) {
                return UserMembershipPlan::where('tenant_id', $tenant_id)
                    ->whereBetween('created_at', [$start, $end])
                    ->count();
            }
        );
    }

    public function transactionThisMonth($tenant_id)
    {
        // Index-friendly month range
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        return Cache::remember(
            $this->cacheKey('transaction_this_month', $tenant_id),
            $this->ttlSeconds,
            function () use ($tenant_id, $start, $end) {
                return (float) Transaction::where('tenant_id', $tenant_id)
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('amount');
            }
        );
    }

    public function allTransactionList($tenant_id)
    {
        // Keep behavior same; only reduce selected columns slightly.
        $transaction = Transaction::join('users', 'users.id', '=', 'transactions.user_id')
            ->where('transactions.tenant_id', $tenant_id)
            ->select([
                'transactions.id',
                'transactions.user_id',
                'transactions.tenant_id',
                'transactions.amount',
                'transactions.type',
                'transactions.payment_time',
                'transactions.created_at',
                'transactions.updated_at',
                'users.name as user_name',
            ])
            ->orderBy('transactions.id', 'DESC');

        return datatables($transaction)
            ->addColumn('name', function ($data) {
                return htmlspecialchars($data->user_name);
            })
            ->addColumn('amount', function ($data) {
                return showPrice($data->amount);
            })
            ->addColumn('created_at', function ($data) {
                return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('jS F, h:i:s A');
            })
            ->rawColumns(['created_at', 'name', 'amount'])
            ->make(true);
    }

    public function dashboardDailyMembershipPaymentChart($tenant_id)
    {
        return Cache::remember(
            $this->cacheKey('daily_membership_payment_chart', $tenant_id),
            $this->ttlSeconds,
            function () use ($tenant_id) {
                $firstDay = Carbon::now()->startOfMonth();
                $lastDay = Carbon::now()->endOfMonth();
                $currentMonthDaysCount = $firstDay->diff(now())->d;

                $transactionData = Transaction::where('tenant_id', $tenant_id)
                    ->whereIn('type', [TRANSACTION_MEMBERSHIP, TRANSACTION_EVENT])
                    ->whereBetween('payment_time', [$firstDay, $lastDay])
                    ->groupBy(DB::raw("DATE_FORMAT(payment_time,'%Y-%m-%d')"))
                    ->orderBy('payment_time', 'desc')
                    ->select(DB::raw("DATE_FORMAT(payment_time,'%b %d') as day, sum(amount) as total"))
                    ->get();

                $price = [];
                foreach ($transactionData as $row) {
                    $price[$row->day] = $row->total;
                }

                return [
                    'mainData' => $transactionData,
                    'days' => $transactionData->pluck('day')->toArray(),
                    'price' => $price,
                    'current_month_days_count' => $currentMonthDaysCount,
                ];
            }
        );
    }

    public function dashboardTopEventTicketChart($tenantId)
    {
        return Cache::remember(
            $this->cacheKey('top_event_ticket_chart', $tenantId),
            $this->ttlSeconds,
            function () use ($tenantId) {
                $eventTickets = EventTicket::join('events', 'events.id', '=', 'event_tickets.event_id')
                    ->where('event_tickets.tenant_id', $tenantId)
                    ->groupBy('event_id')
                    ->select('events.title as event_name', DB::raw("count(ticket_number) as total_ticket"))
                    ->orderBy('total_ticket', 'desc')
                    ->skip(0)->take(5)
                    ->get();

                return [
                    'mainData' => $eventTickets,
                    'totalTicket' => $eventTickets->pluck('total_ticket')->toArray(),
                    'eventName' => $eventTickets->pluck('event_name')->toArray(),
                ];
            }
        );
    }

    public function getSuperAdminOrderSummary()
    {
        // Leave as-is (not the current admin navigation problem).
        $packages = Package::where(['status' => STATUS_ACTIVE])->select('name', 'id')->get();
        $twelveMonthsAgo = now()->subMonths(12);

        $userPackageCounts = UserPackage::where('start_date', '>=', $twelveMonthsAgo)
            ->select([
                'package_id',
                DB::raw('DATE_FORMAT(start_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            ])
            ->groupBy('package_id')
            ->groupBy(DB::raw('DATE_FORMAT(start_date, "%Y-%m")'))
            ->orderBy('package_id')
            ->orderBy('month')
            ->get()
            ->groupBy('package_id');

        $response = [];

        foreach ($packages as $package) {
            $data = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthYear = $date->format('Y-m');

                $monthlyData = $userPackageCounts->get($package->id, collect())->firstWhere('month', $monthYear);
                $count = $monthlyData ? $monthlyData->total : 0;
                $data[] = $count;
            }

            $response[] = [
                'name' => $package->name,
                'data' => $data
            ];
        }

        $last12Months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $last12Months->push(now()->subMonths($i)->format('M Y'));
        }

        return [
            'chartData' => json_encode($response),
            'chartCategory' => json_encode($last12Months->toArray())
        ];
    }
}
