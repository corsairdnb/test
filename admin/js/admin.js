$(function (){

    /*******************************************************************************/
    /************************************ LOGIC ************************************/
    /*******************************************************************************/

    var type="";

    //Определяем текущий раздел
    if (urlType()) {
        type=urlType();
        setType(type);
    } else {
        setDefaultType();
        type=$("#type").val();
    }

    //Create new
    $(".create").on("keypress",function (e) {
        if (e.which == 13) {
            if (!$(this).find("textarea").is(":focus")) {
                $("#create-submit").click();
                return false;
            }
        }
    });
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

    //EDIT
    $(document).on("click",".edit", function(){
        if (!$(this).parent().hasClass("disabled")) {
            var dataId=$(this).parent().attr("rel");
            switchEditMode(dataId,false);
        }
    });

    //DELETE
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
                    getData($("#type").val());
                },
                error: function(msg){
                    errorAlert(1);
                },
                complete: function(msg) {
                    //$("#results").html(JSON.stringify(msg));
                }
            });
        }
    });

    //LIST
    $(document).on("click",".list", function(){
        var parent = $(this).parent();
        if (!parent.hasClass("disabled")) {
            var data={
                type: $(this).attr("data-type"),
                where: {
                    type: parent.find(".form-data").eq(1).attr("data-type"),
                    id: parent.find(".form-data").eq(1).attr("rel")
                },
                action: "getData"
            };
            $("#data").attr("data-edited", parent.attr("rel"));
            $.ajax({
                url: "/admin/ajax.php",
                type: "POST",
                dataType: "json",
                data: makeJSON(data),
                success: function(msg){
                    switchListMode(msg);
                },
                error: function(msg){
                    errorAlert(1);
                },
                complete: function(msg) {
                    //$("#results").html(JSON.stringify(msg));
                }
            });
        }
    });

    //Change type of editor
    $(document).on("click","#top-menu li span", function(event){
        setType($(this).attr("id"));
    });

    //VALIDATOR
    $(document).on("keyup",".form input, .form textarea, .form select", function(event){
        if ($(this).hasClass("required")&&$(this).val()=="") {
            $(this).css("border-color","#db0000");
        } else {
            if ($(this).is(":focus")) {
                $(this).css("border-color","#2FBDFF");
            } else {
                $(this).css("border-color","#CCCCCC");
            }
        }
    });
    $(document).on("focusout",".form input, .form textarea, .form select", function(event){
        if ($(this).hasClass("required")&&$(this).val()=="") {
            $(this).css("border-color","#db0000");
        } else {
            $(this).css("border-color","#CCCCCC");
        }
    });
    $(document).on("focus",".form input, .form textarea, .form select", function(event){
        if ($(this).hasClass("required")&&$(this).val()=="") {
            //$(this).css("border-color","#db0000");
        } else {
            $(this).css("border-color","#2FBDFF");
        }
    });

    /********/
    /* LIST */
    /********/

    $("#shadow, #list-editor-close").on("click",function(){
        $("#list-editor").removeClass("opened").removeClass(urlType());
        $("#shadow").hide();
        $("#data").attr("data-edited","");
    });
    $(document).on("click","#list-editor ul li",function(){
        var id = $(this).attr("data-id");
        var html = $(this).html();
        var list = $("#answer-selected");
        if (!$(this).hasClass("selected")) {
            $(this).addClass("selected");
            var content = '<div class="answer-text">'+html+'</div><div class="answer-actions">'
            content+= ($("#list-editor").hasClass("question")) ? '<span class="answer-true" title="Отметить как верный"></span>' : "";
            content += '<span class="answer-delete" title="Удалить"></span></div>';
            $("<li/>",{
                "data-id": id,
                html: content
            }).appendTo(list);
        }
    });
    $(document).on("click",".answer-delete",function(){
        var parent = $(this).parents("li");
        $("#list-editor ul li[data-id="+parent.attr("data-id")+"]").removeClass("selected");
        parent.remove();
    });
    $(document).on("click",".answer-true",function(){
        var parent = $(this).parents("li");
        parent.siblings().removeClass("true");
        parent.addClass("true");
    });
    $(document).on("click","#list-editor-submit",function(){
        var items = $("#answer-selected li");
        var data = {};
        if ($("#list-editor").hasClass("question")) {
            if (items.length>1 && items.filter(".true").length>0) {
                var answers="";
                $("#answer-selected li").each(function(i,e){
                    answers+=$(e).attr("data-id")+".";
                });
                data = {
                    id: $("#data").attr("data-edited"),
                    type: "question_answer",
                    action: "create",
                    answers: answers,
                    true: $("#answer-selected li.true").attr("data-id")
                }
                $.ajax({
                    url: "/admin/ajax.php",
                    type: "POST",
                    dataType: "json",
                    data: makeJSON(data),
                    success: function(msg){
                        $("#list-editor-close").click();
                    },
                    error: function(msg){
                        errorAlert(1);
                    },
                    complete: function(msg) {}
                });

            }
        }
        else {
            if (items.length>=1) {
                var questions="";
                $("#answer-selected li").each(function(i,e){
                    questions+=$(e).attr("data-id")+".";
                });
                data = {
                    id: $("#data").attr("data-edited"),
                    type: "test_question",
                    action: "create",
                    questions: questions
                }
                $.ajax({
                    url: "/admin/ajax.php",
                    type: "POST",
                    dataType: "json",
                    data: makeJSON(data),
                    success: function(msg){
                        $("#list-editor-close").click();
                    },
                    error: function(msg){
                        errorAlert(1);
                    },
                    complete: function(msg) {}
                });

            }
        }
    });

    /** END LIST **/

    /*********************************************************************************/
    /********************************** FUNCTIONS ************************************/
    /*********************************************************************************/

    function makeJSON (obj) {
        if (obj instanceof Object) {
            return 'json='+JSON.stringify(obj);
        } else {
            return 'json='+obj;
        }
    }

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

    function switchListMode (data) {
        var editor = $("#list-editor");
        var list = $("#list-editor ul");
        var list_selected = $("#answer-selected");
        var question = $("#list-editor-question-text");
        var question_id = $("#data").attr("data-edited");
        var q = $("#data > li[rel="+question_id+"] .form-data").html();
        var ar = data["content"];
        $("#shadow").show();
        list.html(""); list_selected.html("");
        question.html(q);
        editor.addClass("opened");
        editor.addClass(urlType());
        for (var i in ar) {
            if (ar[i]!=false && ar[i] instanceof Object) {
                $("<li/>",{
                    "data-id": ar[i]["id"],
                    html: ar[i]["text"]
                }).appendTo(list);
            }
        }
    }

    function formIsValid (form) {
        var selector = form.children().filter(".required:not(textarea)");
        var flag;
        selector.each(function(i,el){
            if (/["'/\\\$%&\(\)\^]/.test($(el).val())) {
                flag = true;
                return;
            }
        });
        if (flag) {
            alert("Введены недопустимые символы");
            return false;
        }
        if (selector.val()=="") {
            alert("Заполнены не все поля");
            return false;
        }
        return true;
    }

    function setType (type) {
        $("#type").val(type);
        $(".type-name").html(compare(type));
        history.pushState('a', 'Title', '?type='+type+'');
        getForm(type);
        getData(type);
        switchEditMode(false,true);
    }

    function setDefaultType () {
        $("#type").val($("#top-menu li span:first").attr("id"));
        setType($("#type").val());
        getData($("#type").val());
    }

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

    function buildForm (data) {
        var container=$("#form");
        var ar=[], related={}, rel={};
        var x, key, type, label, req, max;
        container.html("");
        ar=data['content']['parameters'];
        rel=data['content']['related'];
        if (data['status']=="true" && ar instanceof Object) {
            //related=(emptyObject(getRelatedData(data['type'])))?getRelatedData(data['type']):false;
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

    function emptyObject (obj) {
        for (var i in obj) {
            return false;
        }
        return true;
    }

    //Returns parameter 'type' from URL
    function urlType () {
        var QueryString = function () {
            var query_string = {};
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                if (typeof query_string[pair[0]] === "undefined") {
                    query_string[pair[0]] = pair[1];
                } else if (typeof query_string[pair[0]] === "string") {
                    var arr = [ query_string[pair[0]], pair[1] ];
                    query_string[pair[0]] = arr;
                } else {
                    query_string[pair[0]].push(pair[1]);
                }
            }
            return query_string;
        } ();
        if (QueryString['type']!="")
            return QueryString['type'];
        else return false;
    }

    //Collecting entered data from the current module
    function collectFormData (form,update) {
        var json = {}, id = "", val = "", checked = "", str = "";
        //TODO: include radio
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

    //Send AJAX query for selected type of data
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
            complete: function(msg) {
                //$("#results").html(JSON.stringify(msg));
            }
        });
    }

    //Generate HTML list of items
    function list (data) {
        var container=$("#data");
        var x="", ar=[];
        var i, col, type;
        $(container).html("");
        if (data['status']=="true" && data['content'] instanceof Object) {
            type=data['type'];
            ar=data['content'];
            for (i in ar) {
                if (ar[i]!=false&&ar[i]!=undefined) {
                    x+='<li class="'+type+'" rel="'+ar[i]['id']+'">';
                    x+='<div class="btn action edit">Изменить</div>';
                    x+='<div class="btn action delete">Удалить</div>';
                    if (type=="question") {
                        x+='<div class="btn action list" data-type="answer">Редактировать ответы</div>';
                    }
                    if (type=="test") {
                        x+='<div class="btn action list" data-type="question">Список вопросов</div>';
                    }
                    x+='<table';
                    x+=(ar[i]['name']!=undefined&&ar[i]['name']!="")?' class="has-name"':'';
                    x+=">";
                    for (col in ar[i]) {
                        if (col!="id"&&col!="active") {
                            if (col=="name") {
                                x+='<tr><td class="name" colspan="2"><h1 class="form-data">'+stripslashes(ar[i][col])+'</h1></td></tr>';
                            } else {
                                x+='<tr><td>';
                                x+=(compare(col))?compare(col):stripslashes(col);
                                x+='</td><td class="'+col+'"><span class="form-data"';
                                x+=(ar[i]["subject_id"])?" rel="+ar[i]["subject_id"]:"";
                                x+=(ar[i]["subject_id"])?" data-type='subject'":"";
                                x+='>';
                                x+=($.cookie(col)!=null)?$.cookie(ar[i][col]):ar[i][col];
                                x+='</span></td></tr>';
                            }
                        }
                        if (col=="active") {
                            if (ar[i][col]=="1") {
                                x+='<div class="btn action activity on" title="Переключить">ON / OFF</div>';
                            } else {
                                x+='<div class="btn action activity off" title="Переключить">ON / OFF</div>';
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

    function stripslashes(str) {
        return str.replace('/\0/g', '0').replace('/\(.)/g', '$1');
    }

    function fillForm (id,clear) {
        var form="#form";
        var fieldName;
        if (clear==true) {
            $("#data li[rel="+id+"] .form-data").each(function(){
                fieldName=$(this).parent().attr("class");
                $(form+" #"+fieldName).each(function(){
                    if ($(this).is(":text")) {
                        $(this).val("");
                    } else if ($(this).is("textarea")) {
                        CKEDITOR.instances[$(this).attr("id")].setData("");
                    }
                });
            });
        } else {
            var text, rel, active;
            $("#data li[rel="+id+"] .form-data").each(function(){
                active=$(this).parents("li").find(".activity").hasClass("on")?true:false;
                fieldName=$(this).parent().attr("class");
                text=$(this).html();
                rel=$(this).attr("rel");
                $(form+" #"+fieldName).each(function(){
                    if ($(this).is(":text")) {
                        $(this).val(text);
                    } else if ($(this).is("textarea")) {
                        CKEDITOR.instances[$(this).attr("id")].setData(text);
                    } else if ($(this).is("select")) {
                        $(this).val(rel);
                    }
                });
                $(form+" #active").attr("checked",(active==true)?true:false);
            });
        }
    }

    function errorAlert (code) {
        switch (code) {
            case 1:
                alert("Произошла ошибка! Попробуйте снова.");
                break;
            case 2:
                alert("Произошла ошибка! Страница будет перезагружена!");
                //window.location.replace(window.location);
                break;
            case 3:
                alert("Ошибка связи с сервером! Страница будет перезагружена!");
                //window.location.replace(window.location);
                break;
            default:
                alert("Произошла ошибка!");
        }
    }

    // returns name of data parameter
    function compare (field) {
        var fields={
            subject: "Дисциплина",
            test: "Тест",
            question: "Вопрос",
            answer: "Ответ",
            user: "Пользователь",
            group: "Группа",
            name: "Название",
            description: "Описание",
            active: "Активность",
            subject_id: "Дисциплина",
            duration: "Длительность",
            num_questions: "Количество вопросов",
            level: "Сложность",
            text: "Текст",
            type: "Тип",
            group_id: "Группа"
        };
        for (var i in fields) {
            if (fields[i]&&i==field) {
                return fields[i];
            }
        }
        return false;
    }

});