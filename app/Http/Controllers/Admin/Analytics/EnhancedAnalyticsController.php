<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\KsuAlumniRecord;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserMembershipPlan;
use Illuminate\Support\Facades\DB;

class EnhancedAnalyticsController extends Controller
{
    public function index()
    {
        $tenantId = function_exists('getTenantId') ? getTenantId() : null;

        // Users (Alumni)
        $usersQ = User::query();
        if (!is_null($tenantId) && DB::getSchemaBuilder()->hasColumn('users', 'tenant_id')) {
            $usersQ->where('tenant_id', $tenantId);
        }
        $totalUsers = (clone $usersQ)->count();

        // Synced registry count
        $registryQ = KsuAlumniRecord::query();
        if (!is_null($tenantId)) {
            $registryQ->where('tenant_id', $tenantId);
        }
        $totalRegistry = (clone $registryQ)->count();

        // Membership plans
        $membershipQ = UserMembershipPlan::query();
        if (!is_null($tenantId)) {
            $membershipQ->where('tenant_id', $tenantId);
        }
        $activeMemberships = (clone $membershipQ)->where('status', 1)->count();

        // Transactions
        $txQ = Transaction::query();
        if (!is_null($tenantId)) {
            $txQ->where('tenant_id', $tenantId);
        }
        $totalTransactions = (clone $txQ)->count();
        $totalAmount = (clone $txQ)->sum('amount');

        // Lightweight distributions
        $byYear = (clone $registryQ)
            ->select('graduation_year', DB::raw('COUNT(*) as total'))
            ->whereNotNull('graduation_year')
            ->groupBy('graduation_year')
            ->orderBy('graduation_year', 'desc')
            ->limit(10)
            ->get();

        $byProgram = (clone $registryQ)
            ->select('program_name', DB::raw('COUNT(*) as total'))
            ->whereNotNull('program_name')
            ->groupBy('program_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'totalUsers',
            'totalRegistry',
            'activeMemberships',
            'totalTransactions',
            'totalAmount',
            'byYear',
            'byProgram'
        ));
    }
}
