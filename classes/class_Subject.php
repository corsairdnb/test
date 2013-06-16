<?php

class Subject extends Common {

    public $data;

    public function Subject () {
        $this->data = $this->get(array(
            "table"=>"subject",
            "cols"=>"*",
            "where"=>"",
            "rel"=>""
        ));
    }

    public function printList () {
        $html = "";
        foreach ($this->data as $key => $subject) {
            $html .= "<div data-id='";
            $html .= $subject["id"]."'>";
            $html .= "<div>".$subject["name"]."</div> ";
            $html .= "<div>".$subject["description"]."</div>";
            $html .= "</div>";
        }
        echo $html;
    }

}

?>