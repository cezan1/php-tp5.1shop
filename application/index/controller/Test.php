<?php
namespace app\index\controller;
use think\Controller;

Class Test extends Controller{
     public function index(){
         return $this->fetch('admin@index/index
         ');
     }

}