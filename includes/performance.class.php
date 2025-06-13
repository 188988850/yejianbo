<?php
class Performance {
    private static $instance = null;
    private $startTime;
    private $memoryStart;
    private $queries = [];
    private $apiCalls = [];
    private $cacheHits = 0;
    private $cacheMisses = 0;
    private $slowQueries = [];
    private $slowApiCalls = [];
    
    private function __construct() {
        $this->startTime = microtime(true);
        $this->memoryStart = memory_get_usage();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function startQuery($sql) {
        $this->queries[] = [
            'sql' => $sql,
            'start' => microtime(true)
        ];
    }
    
    public function endQuery() {
        if (!empty($this->queries)) {
            $query = array_pop($this->queries);
            $duration = microtime(true) - $query['start'];
            
            if ($duration > 0.1) { // 超过100ms的查询视为慢查询
                $this->slowQueries[] = [
                    'sql' => $query['sql'],
                    'duration' => $duration
                ];
            }
        }
    }
    
    public function startApiCall($endpoint) {
        $this->apiCalls[] = [
            'endpoint' => $endpoint,
            'start' => microtime(true)
        ];
    }
    
    public function endApiCall() {
        if (!empty($this->apiCalls)) {
            $call = array_pop($this->apiCalls);
            $duration = microtime(true) - $call['start'];
            
            if ($duration > 0.5) { // 超过500ms的API调用视为慢调用
                $this->slowApiCalls[] = [
                    'endpoint' => $call['endpoint'],
                    'duration' => $duration
                ];
            }
        }
    }
    
    public function logCacheHit() {
        $this->cacheHits++;
    }
    
    public function logCacheMiss() {
        $this->cacheMisses++;
    }
    
    public function getMetrics() {
        $totalTime = microtime(true) - $this->startTime;
        $memoryUsage = memory_get_usage() - $this->memoryStart;
        $peakMemory = memory_get_peak_usage();
        
        return [
            'execution_time' => $totalTime * 1000, // 转换为毫秒
            'memory_usage' => $memoryUsage,
            'peak_memory' => $peakMemory,
            'sql_queries' => count($this->queries),
            'api_calls' => count($this->apiCalls),
            'cache_hits' => $this->cacheHits,
            'cache_misses' => $this->cacheMisses,
            'cache_hit_rate' => $this->cacheHits + $this->cacheMisses > 0 
                ? ($this->cacheHits / ($this->cacheHits + $this->cacheMisses)) * 100 
                : 0
        ];
    }
    
    public function getOptimizationReports() {
        $reports = [];
        
        // 检查慢查询
        if (!empty($this->slowQueries)) {
            $reports[] = [
                'type' => '数据库优化',
                'description' => '发现 ' . count($this->slowQueries) . ' 个慢查询，建议优化SQL语句或添加索引',
                'priority' => '高',
                'details' => array_slice($this->slowQueries, 0, 5) // 只显示前5个慢查询
            ];
        }
        
        // 检查慢API调用
        if (!empty($this->slowApiCalls)) {
            $reports[] = [
                'type' => 'API优化',
                'description' => '发现 ' . count($this->slowApiCalls) . ' 个慢API调用，建议优化接口性能',
                'priority' => '中',
                'details' => array_slice($this->slowApiCalls, 0, 5)
            ];
        }
        
        // 检查缓存命中率
        $cacheHitRate = $this->cacheHits + $this->cacheMisses > 0 
            ? ($this->cacheHits / ($this->cacheHits + $this->cacheMisses)) * 100 
            : 0;
            
        if ($cacheHitRate < 70) {
            $reports[] = [
                'type' => '缓存优化',
                'description' => '缓存命中率较低 (' . number_format($cacheHitRate, 2) . '%)，建议优化缓存策略',
                'priority' => '中',
                'details' => [
                    'hits' => $this->cacheHits,
                    'misses' => $this->cacheMisses,
                    'hit_rate' => $cacheHitRate
                ]
            ];
        }
        
        // 检查内存使用
        $memoryUsage = memory_get_usage();
        if ($memoryUsage > 50 * 1024 * 1024) { // 超过50MB
            $reports[] = [
                'type' => '内存优化',
                'description' => '内存使用较高 (' . $this->formatBytes($memoryUsage) . ')，建议优化内存使用',
                'priority' => '高',
                'details' => [
                    'current' => $memoryUsage,
                    'peak' => memory_get_peak_usage()
                ]
            ];
        }
        
        return $reports;
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
?> 