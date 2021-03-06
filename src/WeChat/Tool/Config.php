<?php
//+-----------------------------------------------------------
//| Man barricades against himself.
//+-----------------------------------------------------------
//| Author:Janas <janmas@126.com>
//+-----------------------------------------------------------
//| 配置类注入到WeChat\Logic\Base类
//+-----------------------------------------------------------

namespace WeChat\Tool;



use WeChat\Exception\ConfigException;

class Config
{
    public static $config;
    public static $instance;

    public static function instance(){
        if(!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register($config = []){
        self::$config =  $this->load();
        if(!empty($config)){
            array_merge(self::$config,$config);
        }
        return self::$instance;
    }

    /** 获取单个config
     * @param $key
     * @return mixed
     * @throws ConfigException
     */
    public function get($key){
        if(empty(self::$config)){
            self::$instance->load();
        }
        if(array_key_exists($key,self::$config)){
            return self::$config[$key];
        }
        throw new ConfigException('配置项[' . $key . ']不存在','1000',__FILE__,__LINE__);
    }

    /**
     * 获取整个配置文件
     * @param string $key
     * @return mixed
     */
    public function load(){
        $dir = dirname(realpath($_SERVER['DOCUMENT_ROOT']));
        #TODO 其他框架需要自己增加判断或者直接把配置写入Config/config.php文件
        if(is_file($dir. DS . 'think') || is_file($dir . DS . 'artisan') || is_file($dir . DS . 'yii')){
            return require $dir . DS . 'config' . DS . 'janas-wechatapi.php';
        }else{
            return require dirname(__DIR__) . DS . 'Config' . DS . 'config.php';
        }
    }

    /**
     * 添加临时配置项
     * @param $key
     * @param $value
     */
    public function set($key,$value=''){
        if(is_array($key)){
            foreach($key as $k=>$v){
                self::$config[$k] = $v;
            }
        }else{
            self::$config[$key] = $value;
        }
    }
}