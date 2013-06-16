$(function (){
    /* Создание новой записи */
    $("#create-submit").on("click",function(){
        if (formIsValid($("#form"))) {
            var upd, data={};
            if (upd = parseInt($(this).attr("rel"))) {
                data = collectFormData("#create",true);
                data["upd_id"] = upd;
            }
            else {
                data = collectFormData("#create",false);
            }
            $.ajax({
                url: "/admin/ajax.php",
                type: "POST",
                dataType: "json",
                data: makeJSON(data),
                success: function(msg){
                    getData($("#type").val());
                },
                error: function(msg){
                    errorAlert(1);
                },
                complete: function(msg) {
                    if (upd) switchEditMode(upd,false);
                }
            });
        }
    });
    /* Режим редактирования */
    $(document).on("click",".edit", function(){
        if (!$(this).parent().hasClass("disabled")) {
            var dataId=$(this).parent().attr("rel");
            switchEditMode(dataId,false);
        }
    });
    /* Удаление записи */
    $(document).on("click",".delete", function(){
        if (!$(this).parent().hasClass("disabled")) {
            var data={
                type: $(this).parent().attr("class"),
                i: $(this).parent().attr("rel"),
                action: "delete"
            };
            $.ajax({
                url: "/admin/ajax.php",
                type: "POST",
                dataType: "json",
                data: makeJSON(data),
                success: function(msg){
                    if (msg["status"]=="true") getData($("#type").val());
                        else alert("Удаление невозможно");
                },
                error: function(msg){
                    errorAlert(1);
                }
            });
        }
    });
    /* Переключение режима редактирования */
    function switchEditMode (id,reset) {
        var btn=$("#create-submit");
        var label=$(".create-action");
        var cl="edit-mode";
        var edited="edited";
        $("."+edited).removeClass(edited);
        btn.toggleClass(cl);
        if (btn.hasClass(cl)&&!reset) {
            $("#data li[rel!="+id+"]").addClass("disabled");
            btn.attr("rel",id);
            btn.html("Изменить");
            label.html("Изменить");
            $("#data li[rel="+id+"]").addClass(edited);
            $(".type-name").hide();
            fillForm(id,false);
        } else {
            $("#data li").removeClass("disabled");
            btn.attr("rel","");
            btn.html("Создать");
            label.html("Создать");
            $("."+edited).removeClass(edited);
            $(".type-name").show();
            if (id) fillForm(id,true);
        }
    }
    /* Получение данных для построения формы */
    function getForm (type) {
        $.ajax({
            url: "/admin/ajax.php",
            type: "POST",
            dataType: "json",
            data: makeJSON({type: type, action: "getForm"}),
            success: function(msg){
                buildForm(msg);
            },
            error: function(msg){
                errorAlert(1);
            },
            complete: function(msg) {}
        });
        $("#form").show("fast");
    }
    /* Построение формы */
    function buildForm (data) {
        var container=$("#form");
        var ar=[], related={}, rel={};
        var x, key, type, label, req, max;
        container.html("");
        ar=data['content']['parameters'];
        rel=data['content']['related'];
        if (data['status']=="true" && ar instanceof Object) {
            for (key in ar) {
                if (ar[key]!=false&&ar[key]!=undefined) {
                    type=ar[key]['type'];
                    (ar[key]['required']&&ar[key]['required']=="true")?req="required":req="";
                    (ar[key]['max']&&ar[key]['max']!="")?max=ar[key]['max']:max="";
                    if (type=="textarea") {
                        x=$('<textarea/>', {
                            id: key,
                            name: "editor",
                            value: '',
                            class: req,
                            maxLength: max
                        });
                    } else if (type=="select") {
                        x=$('<select/>', {
                            id: key,
                            class: req,
                            rel: ar[key]['related']
                        });
                        if (rel) {
                            for (var rel_key in rel) {
                                $.ajax({
                                    url: "/admin/ajax.php",
                                    type: "POST",
                                    dataType: "json",
                                    data: makeJSON({type: rel[rel_key]["table"], action: "getData", related: true}),
                                    success: function(msg){
                                        related=msg['content'];
                                        for (var i in related) {
                                            if (related[i]!=false&&related[i]!=undefined) {
                                                $("select[rel="+msg['type']+"]").append('<option value="'+related[i]['id']+'">'+related[i]['name']+'</option>');
                                                $.cookie(related[i]['id'],related[i]['name'],{expires:1,path:'/'});
                                                $.cookie($("select[rel="+msg['type']+"]").attr("id"),"",{expires:1,path:'/'});
                                            }
                                        }
                                    },
                                    error: function(msg){
                                        errorAlert(1);
                                    },
                                    complete: function(msg) {
                                    }
                                });
                            }
                        }
                    } else {
                        x=$('<input>', {
                            id: key,
                            value: '',
                            type: ar[key]['type'],
                            class: req,
                            maxLength: max
                        });
                    }
                    label='<label for="'+key+'">'+ar[key]['name']+'</label> ';
                    $(container).append(label);
                    (req!="")?$(container).append('<span class="req">*</span>'):"";
                    (type!="checkbox")?$(container).append('<br>'):"";
                    $(container).append(x);
                    $(container).children().filter("input[type='checkbox']").attr('checked', true);
                    x="";
                    if (type=="textarea") {
                        CKEDITOR.replace('editor');
                        CKEDITOR.config.bodyClass="required"
                    }
                }
            }
        } else {
            errorAlert(3);
        }
    }
    /* Обход полей формы и сбор значений */
    function collectFormData (form,update) {
        var json = {}, id = "", val = "", checked = "", str = "";
        $(
            form+" input[type='text'],"+
            form+" input[type='hidden'],"+
            form+" input[type='checkbox'],"+
            form+" select,"+
            form+" textarea"
        ).each(function(i){
            id=$(this).attr("id");
            val=$(this).val();
            checked=($(this).attr("checked"))?$(this).attr("checked"):"";
            if ($(this).attr("type")!="checkbox"&&!$(this).is("textarea")) {
                str+=(val)?val:"";
            } else if ($(this).attr("type")=="checkbox") {
                str+=(checked)?"1":"0";
            } else {
                str+=CKEDITOR.instances[id].getData();
            }
            if (id) {
                json[id]=str;
            }
            id=""; val=""; checked=""; str="";
        });
        json["action"]=(update)?"update":"create";
        return json;
    }
    /* Запрос на получение данных */
    function getData (type) {
        $.ajax({
            url: "/admin/ajax.php",
            type: "POST",
            dataType: "json",
            data: makeJSON({type: type, action: "getData"}),
            success: function(msg){
                list(msg);
            },
            error: function(msg){
                errorAlert(1);
            },
            complete: function(msg) {}
        });
    }
    /* Визуализация полученных данных */
    function list (data) {
        var container=$("#data");
        var x="", ar=[];
        var i, col, type;
        $(container).html("");
        if (data['status']=="true" && data['content'] instanceof Object) {
            type=data['type'];
            ar=data['content'];
            for (i in ar) {
                if (ar[i]!=false && ar[i]!=undefined) {
                    x+='<li class="'+type+'" rel="'+ar[i]['id']+'">';
                    x+='<div class="btn action edit">Изменить</div>';
                    x+='<div class="btn action delete">Удалить</div>';
                    if (type=="question") {
                        x+='<div class="btn action list" data-type="answer">Редактировать ответы</div>';
                    }
                    if (type=="test") {
                        x+='<div class="btn action list" data-type="question">Список вопросов</div>';
                        x+='<div class="btn action list list-group" data-type="group">Назначить</div>';
                    }
                    x+='<table';
                    x+=(ar[i]['name']!=undefined && ar[i]['name']!="") ? ' class="has-name"' : '';
                    x+=">";
                    for (col in ar[i]) {
                        if (col!="id" && col!="active" && col!="email") {
                            if (col=="name") {
                                x+='<tr><td class="name" colspan="2"><h1 class="form-data">'+stripslashes(ar[i][col])+'</h1></td></tr>';
                            } else {
                                x+=(type=="answer" && col=="text")?'':'<tr><td>';
                                if (type=="answer" && col=="text") x+=''; else x+=(compare(col))?compare(col):stripslashes(col);
                                x+=(type=="answer" && col=="text")?'':'</td>';
                                x+='<td class="'+col+'"';
                                x+=(type=="answer" && col=="text")? " colspan=2" : "";
                                x+='><span class="form-data"';
                                x+=(ar[i]["subject_id"])?" rel="+ar[i]["subject_id"]:"";
                                x+=(ar[i]["subject_id"])?" data-type='subject'":"";
                                x+='>';
                                x+=($.cookie(col)!=null)?$.cookie(ar[i][col]):ar[i][col];
                                x+='</span></td></tr>';
                            }
                        }
                    }
                    x+='</table></li>';
                    $(container).append(x);
                    x="";
                }
            }
        } else {
            x='<li class="no-data">Нет элементов для отображения</li>';
            $(container).append(x);
        }
    }
});