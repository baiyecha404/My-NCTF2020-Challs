<?php

function protect($url)
{
    $tmp_url = urldecode($url);
    if(strpos($tmp_url, "file://") !== false or strpos($tmp_url, "@") !== false)
    {
        die("<h2>Hacking attempt prevented (LFI).</h2>");
    }
    if(strpos($tmp_url, "-o") !== false or strpos($tmp_url, "-F") !== false or strpos($tmp_url, "-K") !== false)
    {
        die("<h2>Hacking attempt prevented (Command Injection).</h2>");
    }
    
    $scheme = parse_url($tmp_url)["scheme"];
    $host   = parse_url($tmp_url)["host"];
    $port   = parse_url($tmp_url)["port"];
    
    if (!empty($scheme) && !preg_match('/^http?$/i', $scheme) || 
    !empty($host)   && !in_array($host, [$_SERVER['SERVER_NAME'], 'blog.soreatu.com']) ||
    !empty($port)   && !in_array($port, [$_SERVER['SERVER_PORT'],'80','443']))
    {
        die("<h2>Hacking attempt prevented (SSRF).</h2>");
    }
    return $url;
}

function url_get_contents ($url) {
    $safe_url = protect($url);
    $url = escapeshellarg($safe_url);
    $cmd = "curl ".$url;
    $res = shell_exec($cmd);
    return $res;
}


class TemplateHandler{

    public $filename;
    public $handle;

    public function __wakeup()
    {
        if(file_exists($this->filename)){
            echo 'Template file exists.';
        }
    }

    public function __destruct()
    {
        echo $this->handle;
    }
}


class SimpleRSS{
    public $data;
    public $obj;

    public function __construct(){
        $this->init();
    }

    public function init($data,$obj){
        $this->obj=$obj;
        $this->data=$data;
    }

    public function __toString()
    {
        $this->obj->visible();
        return 'RSS template is visible now.';
    }
}

class FileHandler{

    public $filename;
    public $content;
    public $handle;

    public function __construct($filename,$content,$handle){
        $this->filename=$filename;
        $this->content=$content;
        $this->handle=$handle;
    }

    public function write(){
        file_put_contents(__DIR__.'/cache_logs/'.$this->filename,$this->content);
    }

    public function __call($name, $arguments){
        if (array_key_exists($name, $this->handle)) {
            call_user_func_array($this->handle[$name], $arguments);
        }
    }
}


