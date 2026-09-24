<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Order;

class ReportService
{
    // Vulnerable: Query on unindexed column
    public function getOrdersByStatus($status)
    {
        // 'status' column not indexed - full table scan on millions of rows
        return DB::table('orders')
            ->where('status', $status)
            ->get();
    }

    // Vulnerable: Multiple unindexed columns
    public function searchOrders($email, $date)
    {
        // Neither 'customer_email' nor 'created_at' indexed
        // Extremely slow on large tables
        return Order::where('customer_email', $email)
            ->whereDate('created_at', $date)
            ->get();
    }

    // Vulnerable: LIKE query without index
    public function searchProducts($keyword)
    {
        // Full-text search on unindexed 'description' column
        return DB::table('products')
            ->where('description', 'LIKE', '%' . $keyword . '%')
            ->get();
    }

    // Vulnerable: Join on unindexed foreign key
    public function getOrdersWithCustomers()
    {
        // 'customer_id' in orders table not indexed
        // Nested loop join - O(n*m) complexity
        return DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*', 'customers.name')
            ->get();
    }

    // Vulnerable: COUNT on unindexed column in large table
    public function countPendingOrders()
    {
        // Full table scan to count
        return Order::where('status', 'pending')->count();
    }

    // Vulnerable: ORDER BY on unindexed column
    public function getRecentOrders()
    {
        // 'updated_at' not indexed - sorts entire result set
        return Order::orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();
    }
}
