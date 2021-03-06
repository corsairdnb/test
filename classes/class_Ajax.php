<?php

class Ajax extends Modules {

    public $data;
    protected $classname;
    protected $params;
    protected $object;

    public function Ajax ($data) {
        $this->data=json_decode($data,true);
        $this->classname=$this->data['type'];
        $this->id=$this->data['i'];
        $this->params=$this->parse($this->classname,$this->data);
        $this->object=new $this->classname;
    }

    //Проверка параметра: Возвращает значение, либо false
    public function param ($paramName) {
        return (!empty($this->data[$paramName]))?$this->data[$paramName]:false;
    }

    //Создать нечто
    public function create () {
        return ($this->object->create($this->params))?true:false;
    }

    //обновить
    public function update () {
        return ($this->object->update($this->params))?true:false;
    }

    //Возвращает массив (самое последнее сверху), либо false
    public function get () {
        return ($response=$this->object->get($this->params))?$response:false;
    }

    //Возвращает массив с актуальными ключами
    public function getKeys () {
        return ($response=$this->object->getKeys())?$response:false;
    }

    //Возвращает массив с отчетами
    public function getReports () {
        return ($response=$this->object->getReports())?$response:false;
    }

    //Удаляет и возвращает true, либо false
    public function remove () {
        return ($this->object->remove(array("table"=>$this->classname,"id"=>$this->id)))?true:false;
    }

    private function parse ($type,$data) {
        $related_create=array();
        $ar=array();
        unset($data['where']);
        unset($data['datafrom']);
        foreach ($data as $key=>$val) {
            if ($this->is_related($type,$key)) {
                $related_create[$key] = $val;
                unset($ar[$key]);
            } else {
                $ar[$key]=$val;
            }
        }
        unset($ar['update']);
        unset($ar['upd_id']);
        unset($ar['type']);
        unset($ar['action']);
        unset($ar['related']);
        unset($ar['id']);
        $cols="";
        $vals="";
        foreach ($ar as $el=>$v) {
            $cols.="`".$el."`,";
            $vals.="'".$v."',";
        }
        $cols=substr($cols, 0, -1);
        $vals=substr($vals, 0, -1);
        if ($this->param("where")) {
            $where=$this->param("where");
        } else { $where=$this->id;}
        return array(
            "table"=>$type,
            "cols"=>$cols,
            "vals"=>$vals,
            "where"=>$where,
            "update"=>$this->data['upd_id'],
            "related"=>$related_create,
            "rel"=>$this->param("related"),
            "id"=>$this->data["id"],
            "datafrom"=>$this->param("datafrom"),
            "test_id"=>$this->param("test_id")
        );
    }

    public function requiredParamsOk () {

    }

}