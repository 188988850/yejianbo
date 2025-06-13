<?php
class Cache {
    private static $instance = null;
    private $handler = null;
    private $prefix = '';
    private $defaultExpire = 3600;

    private function __construct() {
        $this->prefix = CACHE_PREFIX;
        $this->defaultExpire = CACHE_EXPIRE;
        $this->initHandler();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initHandler() {
        switch (CACHE_TYPE) {
            case 'redis':
                if (class_exists('Redis')) {
                    $this->handler = new Redis();
                    try {
                        $this->handler->connect(REDIS_HOST, REDIS_PORT);
                        if (REDIS_PASSWORD) {
                            $this->handler->auth(REDIS_PASSWORD);
                        }
                        $this->handler->select(REDIS_DATABASE);
                    } catch (Exception $e) {
                        error_log('Redis connection failed: ' . $e->getMessage());
                        $this->handler = null;
                    }
                }
                break;

            case 'memcached':
                if (class_exists('Memcached')) {
                    $this->handler = new Memcached();
                    $this->handler->addServer(MEMCACHED_HOST, MEMCACHED_PORT);
                }
                break;

            case 'file':
            default:
                $this->handler = new FileCache();
                break;
        }
    }

    public function set($key, $value, $expire = null) {
        if ($this->handler === null) {
            return false;
        }

        $key = $this->prefix . $key;
        $expire = $expire ?? $this->defaultExpire;

        if ($this->handler instanceof Redis) {
            return $this->handler->setex($key, $expire, serialize($value));
        } elseif ($this->handler instanceof Memcached) {
            return $this->handler->set($key, $value, $expire);
        } else {
            return $this->handler->set($key, $value, $expire);
        }
    }

    public function get($key) {
        if ($this->handler === null) {
            return false;
        }

        $key = $this->prefix . $key;

        if ($this->handler instanceof Redis) {
            $value = $this->handler->get($key);
            return $value !== false ? unserialize($value) : false;
        } elseif ($this->handler instanceof Memcached) {
            return $this->handler->get($key);
        } else {
            return $this->handler->get($key);
        }
    }

    public function delete($key) {
        if ($this->handler === null) {
            return false;
        }

        $key = $this->prefix . $key;

        if ($this->handler instanceof Redis) {
            return $this->handler->del($key);
        } elseif ($this->handler instanceof Memcached) {
            return $this->handler->delete($key);
        } else {
            return $this->handler->delete($key);
        }
    }

    public function clear() {
        if ($this->handler === null) {
            return false;
        }

        if ($this->handler instanceof Redis) {
            $keys = $this->handler->keys($this->prefix . '*');
            foreach ($keys as $key) {
                $this->handler->del($key);
            }
            return true;
        } elseif ($this->handler instanceof Memcached) {
            return $this->handler->flush();
        } else {
            return $this->handler->clear();
        }
    }

    public function increment($key, $value = 1) {
        if ($this->handler === null) {
            return false;
        }

        $key = $this->prefix . $key;

        if ($this->handler instanceof Redis) {
            return $this->handler->incrBy($key, $value);
        } elseif ($this->handler instanceof Memcached) {
            return $this->handler->increment($key, $value);
        } else {
            return $this->handler->increment($key, $value);
        }
    }

    public function decrement($key, $value = 1) {
        if ($this->handler === null) {
            return false;
        }

        $key = $this->prefix . $key;

        if ($this->handler instanceof Redis) {
            return $this->handler->decrBy($key, $value);
        } elseif ($this->handler instanceof Memcached) {
            return $this->handler->decrement($key, $value);
        } else {
            return $this->handler->decrement($key, $value);
        }
    }

    public function exists($key) {
        if(!$this->enabled) return false;
        
        try {
            return $this->redis->exists($this->prefix . $key);
        } catch(Exception $e) {
            error_log('缓存检查失败: ' . $e->getMessage());
            return false;
        }
    }
    
    public function remember($key, $time, $callback) {
        $value = $this->get($key);
        if($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $time);
        return $value;
    }
    
    public function has($key) {
        return $this->get($key) !== null;
    }
    
    public function pull($key) {
        $value = $this->get($key);
        if($value !== null) {
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

class FileCache {
    private $cacheDir;

    public function __construct() {
        $this->cacheDir = dirname(__FILE__) . '/../cache/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    public function set($key, $value, $expire = 3600) {
        $filename = $this->getCacheFile($key);
        $data = [
            'expire' => time() + $expire,
            'data' => $value
        ];
        return file_put_contents($filename, serialize($data));
    }

    public function get($key) {
        $filename = $this->getCacheFile($key);
        if (!file_exists($filename)) {
            return false;
        }

        $data = unserialize(file_get_contents($filename));
        if ($data['expire'] < time()) {
            unlink($filename);
            return false;
        }

        return $data['data'];
    }

    public function delete($key) {
        $filename = $this->getCacheFile($key);
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return true;
    }

    public function clear() {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    public function increment($key, $value = 1) {
        $current = $this->get($key);
        if ($current === false) {
            $current = 0;
        }
        $new = $current + $value;
        $this->set($key, $new);
        return $new;
    }

    public function decrement($key, $value = 1) {
        $current = $this->get($key);
        if ($current === false) {
            $current = 0;
        }
        $new = $current - $value;
        $this->set($key, $new);
        return $new;
    }

    private function getCacheFile($key) {
        return $this->cacheDir . md5($key) . '.cache';
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

// 初始化缓存
$cache = Cache::getInstance();
?> 