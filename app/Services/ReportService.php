<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Missing database index vulnerability
     * Bug: Query on unindexed column causes full table scan
     */
    public function getPopularPosts($categoryId, $limit = 10)
    {
        // VULNERABLE: Query on 'views' column without index
        // With millions of posts, this causes 5+ second query time
        
        return Post::where('category_id', $categoryId)
            ->where('status', 'published')
            ->orderBy('views', 'desc') // views column has NO INDEX!
            ->limit($limit)
            ->get();
    }
    
    /**
     * Another missing index - created_at range query
     */
    public function getPostsByDateRange($start, $end)
    {
        // VULNERABLE: Range query on unindexed created_at
        return Post::whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Composite query without composite index
     */
    public function getUserPostStats($userId)
    {
        // VULNERABLE: Query on user_id + status without composite index
        // Should have INDEX(user_id, status, created_at)
        
        return DB::table('posts')
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
