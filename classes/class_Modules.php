<?php

class Modules {

    public $modules;

    public function Modules () {

    }

    public function getModules () {
        require("modules.php");
        return $modules;
    }

    public function getMenu () {
        require("modules.php");
        $menu=array();
        foreach ($modules as $item=>$val) {
            $menu[$item]=$val['name'];
        }
        return $menu;
    }

    public function getForm () {
        $type=$this->classname; //Classname = type
        require("modules.php");
        $this->modules = $modules;
        $ar=array();
        foreach ($this->modules as $item=>$val) {
            if ($item==$type) $ar=array("parameters"=>$val['parameters'], "related"=>$val['related']);
        }
        return $ar;
    }

    public function is_related ($type,$param) {
        require("modules.php");
        //echo $modules[$type]['related'][$param]["table"];
        if ($modules[$type]['related'][$param]) {
            $flag = true;
        }
        if ($flag) return true; else return false;
    }

    public function updateModulesData () {

    }
}
