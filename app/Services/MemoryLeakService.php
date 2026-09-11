<?php

namespace App\Services;

class MemoryLeakService
{
    private $cache = [];
    
    /**
     * Memory leak vulnerability
     * Bug: Unbounded cache growth without cleanup
     */
    public function processLargeDataset($data)
    {
        foreach ($data as $item) {
            // VULNERABLE: Cache grows indefinitely
            $this->cache[$item['id']] = $item;
            
            // No cleanup, no size limit, no TTL
            $this->expensiveOperation($item);
        }
        
        return count($this->cache);
    }
    
    /**
     * Memory leak via event listeners
     */
    public function registerListeners()
    {
        // VULNERABLE: Listeners never removed
        \Event::listen('post.created', function ($post) {
            $this->cache[$post->id] = $post;
        });
        
        \Event::listen('post.updated', function ($post) {
            $this->cache[$post->id] = $post;
        });
        
        // In long-running process, cache grows forever
    }
    
    /**
     * Circular reference memory leak
     */
    public function createCircularReference()
    {
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();
        
        // VULNERABLE: Circular references prevent garbage collection
        $obj1->ref = $obj2;
        $obj2->ref = $obj1;
        
        $this->cache[] = $obj1;
    }
    
    private function expensiveOperation($item)
    {
        // Simulate expensive operation
        usleep(100);
    }
}
