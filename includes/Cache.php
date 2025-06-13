<?php
class Cache {
    private $cache_dir;
    private $cache_time;
    private $cache_prefix;
    
    public function __construct($cache_dir = null, $cache_time = 300, $cache_prefix = 'cache_') {
        if($cache_dir === null) {
            $cache_dir = dirname(__FILE__) . '/../cache/';
        }
        $this->cache_dir = $cache_dir;
        $this->cache_time = $cache_time;
        $this->cache_prefix = $cache_prefix;
        
        if(!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0777, true);
        }
    }
    
    public function get($key) {
        $file = $this->getCacheFile($key);
        if(!file_exists($file)) {
            return false;
        }
        
        $data = file_get_contents($file);
        if($data === false) {
            return false;
        }
        
        $data = unserialize($data);
        if($data['expire'] > 0 && $data['expire'] < time()) {
            $this->delete($key);
            return false;
        }
        
        return $data['value'];
    }
    
    public function set($key, $value, $time = null) {
        $file = $this->getCacheFile($key);
        $time = $time === null ? $this->cache_time : $time;
        
        $data = array(
            'expire' => $time > 0 ? time() + $time : 0,
            'value' => $value
        );
        
        return file_put_contents($file, serialize($data)) !== false;
    }
    
    public function delete($key) {
        $file = $this->getCacheFile($key);
        if(file_exists($file)) {
            return unlink($file);
        }
        return true;
    }
    
    public function clear() {
        $files = glob($this->cache_dir . $this->cache_prefix . '*');
        foreach($files as $file) {
            if(is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }
    
    private function getCacheFile($key) {
        return $this->cache_dir . $this->cache_prefix . md5($key) . '.cache';
    }
    
    public function remember($key, $time, $callback) {
        $value = $this->get($key);
        if($value !== false) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $time);
        return $value;
    }
    
    public function increment($key, $value = 1) {
        $current = $this->get($key);
        if($current === false) {
            $current = 0;
        }
        
        $new = $current + $value;
        $this->set($key, $new);
        return $new;
    }
    
    public function decrement($key, $value = 1) {
        return $this->increment($key, -$value);
    }
    
    public function has($key) {
        return $this->get($key) !== false;
    }
    
    public function pull($key) {
        $value = $this->get($key);
        if($value !== false) {
            $this->delete($key);
        }
        return $value;
    }
    
    public function forever($key, $value) {
        return $this->set($key, $value, 0);
    }
    
    public function flush() {
        return $this->clear();
    }
    
    public function tags($names) {
        return new CacheTags($this, (array) $names);
    }
}

class CacheTags {
    private $cache;
    private $names;
    
    public function __construct($cache, $names) {
        $this->cache = $cache;
        $this->names = $names;
    }
    
    public function get($key) {
        return $this->cache->get($this->taggedKey($key));
    }
    
    public function set($key, $value, $time = null) {
        return $this->cache->set($this->taggedKey($key), $value, $time);
    }
    
    public function delete($key) {
        return $this->cache->delete($this->taggedKey($key));
    }
    
    public function flush() {
        foreach($this->names as $name) {
            $this->cache->delete('tag_' . $name);
        }
        return true;
    }
    
    private function taggedKey($key) {
        return implode(':', array_merge($this->names, array($key)));
    }
}
?> 