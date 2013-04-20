{* Template for File_select plugin *}

<link type="text/css" rel="stylesheet" href="/admin/file_select/style.css">
{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $('#file-selector-button').click(function(){
            $("#file-selector-ul").html("");
            $.ajax({
                url: "/admin/file_select/file_select.php",
                type: "POST",
                dataType: "json",
                success: function(msg){
                    $.each(msg, function(i,item)
                    {
                        $("#file-selector-ul").append("<li><img src='"+item+"' alt='' onclick='select(this)' /><span>"+item.replace(/(.*)\/(.*)/g, '$2')+"</span></li>");
                    });
                    $("#file-selector").show();
                },
                error: function(msg){
                    alert('Произошла ошибка. Попробуйте повторить попытку.');
                }
            })
        });
        $('#file-selector-close').click(function(){
            $("#file-selector").hide();
        });
    });
    function select(img) {
        var filename=$(img).attr("src");
        $("#file-selector").hide();
        $("#file-selected div").html("");
        $("#file-selected div").append("<span>"+filename.replace(/(.*)\/(.*)/g, '$2')+"</span><br><img src='"+filename+"' alt='' />");
        $("#file-submit").show();
        $("#file-selected").show();
    }
    function select_submit () {
        var item_id=$("input[name=item_id]").val();
        var filename=$("#file-selected div span").html();
        $.post("/admin/file_select/file_select_sql.php", { id: item_id, filename: filename },
            function(data){
                window.location.href="/admin/catalog.php?act=EditItem&id="+item_id;
                changeTab("tab_images");
            });
    }
</script>
{/literal}

<div id="file-select">
    <div id="file-selector-button">
        <span>Выбрать файл на сервере</span>
    </div>
    <div id="file-selector">
        <div id="file-selector-title">Выберите изображение</div>
        <div id="file-selector-close" title="Закрыть"></div>
        <div style="clear: both;"></div>
        <div id="file-selector-inner">
            <ul id="file-selector-ul">

            </ul>
        </div>
    </div>
    <div id="file-selected">
        Выбранное изображение:
        <div></div>
    </div>
    <div id="file-submit" onclick="select_submit();" title="Подтвердить выбор">ОК</div>
</div>