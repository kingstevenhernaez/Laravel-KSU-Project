<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Users: dashboard uses tenant_id + is_alumni + status
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['tenant_id', 'is_alumni', 'status'], 'idx_users_tenant_alumni_status');
            });
        }

        // Transactions: dashboard uses tenant_id + created_at, and tenant_id + payment_time + type
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->index(['tenant_id', 'created_at'], 'idx_tx_tenant_created_at');
                $table->index(['tenant_id', 'payment_time', 'type'], 'idx_tx_tenant_payment_time_type');
                $table->index(['tenant_id', 'user_id'], 'idx_tx_tenant_user');
            });
        }

        // Membership plans: tenant_id + created_at
        if (Schema::hasTable('user_membership_plans')) {
            Schema::table('user_membership_plans', function (Blueprint $table) {
                $table->index(['tenant_id', 'created_at'], 'idx_ump_tenant_created_at');
            });
        }

        // Events: tenant_id + status + date
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->index(['tenant_id', 'status', 'date'], 'idx_events_tenant_status_date');
            });
        }

        // Event tickets: tenant_id + event_id
        if (Schema::hasTable('event_tickets')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                $table->index(['tenant_id', 'event_id'], 'idx_event_tickets_tenant_event');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('idx_users_tenant_alumni_status');
            });
        }

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropIndex('idx_tx_tenant_created_at');
                $table->dropIndex('idx_tx_tenant_payment_time_type');
                $table->dropIndex('idx_tx_tenant_user');
            });
        }

        if (Schema::hasTable('user_membership_plans')) {
            Schema::table('user_membership_plans', function (Blueprint $table) {
                $table->dropIndex('idx_ump_tenant_created_at');
            });
        }

        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropIndex('idx_events_tenant_status_date');
            });
        }

        if (Schema::hasTable('event_tickets')) {
            Schema::table('event_tickets', function (Blueprint $table) {
                $table->dropIndex('idx_event_tickets_tenant_event');
            });
        }
    }
};
