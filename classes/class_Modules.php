<?php

class Modules {

    public function Modules () {

    }

    public function getModules () {
        require_once("modules.php");
        /*foreach ($modules as $mod=>$val) {
            if ($type==$mod) {
                foreach ($modules[$mod] as $module) {
                    foreach ($module as $x=>$val) {
                        $keys[]=$x;
                    }
                }
            }
        }*/
        return $modules;
    }

    public function getMenu () {
        require_once("modules.php");
        $menu=array();
        foreach ($modules as $item=>$val) {
            $menu[$item]=$val['name'];
        }
        return $menu;
    }

    public function getForm () {
        $type=$this->classname; //Classname = type
        require_once("modules.php");
        $ar=array();
        foreach ($modules as $item=>$val) {
            if ($item==$type) $ar=$val['parameters'];
        }
        return $ar;
    }

    public function updateModulesData () {

    }
}
