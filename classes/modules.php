<?php

global $modules;

$modules = array(
    'test' => array(
        'name'=>'Тесты',
        'parameters'=>array(
            'name'=>array(
                'name'=>'Название',
                'required' => 'true',
                'type' => 'text',
                'max' => '100'
            ),
            'subject_id'=>array(
                'name'=>'Предмет',
                'required' => 'true',
                'type' => 'select',
                'related' => 'subject'
            ),
            'duration'=>array(
                'name'=>'Длительность',
                'required' => 'true',
                'type' => 'text',
                'max' => '3'
            ),
            'num_questions'=>array(
                'name'=>'Количество вопросов',
                'required' => 'true',
                'type' => 'text',
                'max' => '3'
            ),
            'active'=>array(
                'name'=>'Активность',
                'type' => 'checkbox'
            )
        ),
        'related'=>array(
            'subject_id' => array(
                'table'=>'subject'
            )
        )
    ),
    'subject' => array(
        'name'=>'Дисциплины',
        'parameters'=>array(
            'name'=>array(
                'name'=>'Название',
                'required' => 'true',
                'type' => 'text',
                'max' => '100'
            ),
            'description'=>array(
                'name'=>'Описание',
                'required' => 'false',
                'type' => 'textarea',
                'max' => '1000'
            ),
            'active'=>array(
                'name'=>'Активность',
                'type' => 'checkbox'
            )
        )
    ),
    'question' => array(
        'name'=>'Вопросы',
        'parameters'=>array(
            'text'=>array(
                'name'=>'Текст',
                'required' => 'true',
                'type' => 'textarea',
                'max' => '1000'
            ),
            'subject_id'=>array(
                'name'=>'Предмет',
                'required' => 'true',
                'type' => 'select',
                'related' => 'subject'
            ),
            /*'level'=>array(
                'name'=>'Сложность',
                'required' => 'true',
                'type' => 'select'
            ),*/
            /*'type'=>array(
                'name'=>'Тип',
                'required' => 'true',
                'type' => 'select'
            ),*/
            'active'=>array(
                'name'=>'Активность',
                'type' => 'checkbox'
            )
        )
    ),
    'answer' => array(
        'name'=>'Ответы',
        'parameters'=>array(
            'text'=>array(
                'name'=>'Текст',
                'required' => 'true',
                'type' => 'textarea'
            )
        )
    ),
    'user' => array(
        'name'=>'Пользователи',
        'parameters'=>array(
            'name'=>array(
                'name'=>'Имя',
                'required' => 'true',
                'type' => 'text'
            ),
            'group'=>array(
                'name'=>'Группа',
                'required' => 'false',
                'type' => 'select',
                'related' => 'group'
            )
        )
    ),
    'group' => array(
        'name'=>'Группы',
        'parameters'=>array(
            'name'=>array(
                'name'=> 'Название',
                'required' => 'true',
                'type' => 'text'
            ),
            'description'=>array(
                'name'=> 'Описание',
                'required' => 'false',
                'type' => 'textarea',
                'max' => '1000'
            )
        )
    )
);

?>