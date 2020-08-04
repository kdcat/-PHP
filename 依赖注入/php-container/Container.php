<?php
namespace KDCAT\Container;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Exception;
class Container implements ContainerInterface{
    //注册树
    protected $binds = [];
    /**
     * [__construct 构造函数]
     * @Author   kdcat
     * @DateTime 2020-08-04
     * @param    array      $binds [注册类数组]
     */
    public function __construct(array $binds = []){
        //绑定名称 绑定类名
        foreach ($binds as $bindName => $bindClass) {
            $this->set($bindName, $bindClass);
        }
    }
    /**
     * [set 设置绑定类]
     * @Author   czt
     * @DateTime 2020-08-04
     * @param    string     $bindName  [绑定名称]
     * @param    string     $className [类名称]
     */
    public function set(string $bindName, string $className)
    {
        if ($this->has($bindName)) {
            throw new Exception("该名称已被注册：${bindName}", 1);
        }
        $this->binds[$bindName] = $this->resolve($className);
        return true;
    }
    /**
     * [get 返回注册的对象]
     * @Author   czt
     * @DateTime 2020-08-04
     * @param    string     $bindName [description]
     * @return   [type]               [description]
     */
    public function get($bindName)
    {
        if (!$this->has($bindName)) {
            throw new Exception("未注册的名称${bindName}", 1);
        }
        return $this->binds[$bindName];
    }
    /**
     * [has 是否已经注册]
     * @Author   czt
     * @DateTime 2020-08-04
     * @param    [type]     $bindName [description]
     * @return   boolean              [description]
     */
    public function has($bindName){
        return isset($this->binds[$bindName]);
    }

    /**
     * [resolve 递归解决依赖关系]
     * @Author   czt
     * @DateTime 2020-08-04
     * @param    string     $className [description]
     * @return   [type]                [description]
     */
    public function resolve(string $className)
    {
        try{
            $class = new ReflectionClass($className);
        }catch(\Exception $e){
            throw new Exception("反射创建失败：${className};".$e->getMessage(), 1);
        }
        $obj = null;
        $inParam = [];
        //存在构造函数
        $constructor = $class->getConstructor();
        if ($constructor) {
            //获取构造函数的参数
            $params = $constructor->getParameters();
            foreach ($params as $key => $param) {
                //获取构造函数的依赖注入类参数
                $paramClassName = $param->getClass()->name;
                $inParam[] = $this->resolve($paramClassName);
            }
            $obj = $class->newInstanceArgs($inParam);
        }else{
            //不存在构造函数，直接实例化类
            $obj = new $className;
        }
        return $obj;
    }
}



