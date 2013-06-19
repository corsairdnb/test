<?php

class Test extends Common {

    public $data;
    public $subjects;

    public function Test () {
        $this->data = $this->get(array(
            "table"=>"test",
            "cols"=>"*",
            "where"=>"",
            "rel"=>""
        ));
        if ($this->data) {
            $this->subjects = new Subject();
            $this->subjects = $this->subjects->data;
            $this->data = $this->splitByKeys($this->data, $this->subjects, "subject_id", "subject", array(0=>'name'));
        }
    }

    public function printOptionsList () {
        $html = "";
        $ar = array();
        foreach ($this->data as $key => $test) {
            if (!array_key_exists($test['subject_id'],$ar)) {
                $ar[$test['subject_id']] = array();
            }
        }
        foreach ($this->data as $key_1 => $test_1) {
            foreach ($ar as $key_2 => $test_2) {
                if ($test_1["subject_id"] == $key_2) {
                    $ar[$key_2][] = $test_1;
                }
            }
        }
        foreach ($ar as $subject_id => $test_list) {
            $html .= "<optgroup data-id='";
            $html .= $subject_id."' label=".$test_list[0]["subject_name"].">";
            foreach ($test_list as $test_id => $test) {
                $html .= "<option value=".$test["id"].">".$test["name"]."</option> ";
            }
            $html .= "</optgroup>";
        }
        echo $html;
    }

}

?>