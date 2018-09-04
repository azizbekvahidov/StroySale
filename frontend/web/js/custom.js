var tabpertable;

$.fn.Custom = function ( opts ) {
    var maintable = $(this);
    tabpertable = maintable.DataTable({
        columns: opts.Columns,

        language: {
            decimal: "",
            emptyTable: "Нет данных в таблицы",
            info: "Показать _START_ до _END_ из _TOTAL_ записей",
            infoEmpty: "Показать от 0 до 0 из 0 записей",
            infoFiltered: "(фильтровать по _MAX_)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Показать _MENU_ ",
            loadingRecords: "Загрузка...",
            processing: "Процесс...",
            search: "Поиск:",
            zeroRecords: "Нет соответствующих данных",
            paginate: {
                first: "Первый",
                last: "Конец",
                next: "След.",
                previous: "Пред."
            },
            aria: {
                sortAscending: ": Задать по нарастающему",
                sortDescending: ": Задать по убывающему"
            }
        },

        ajax: {
            url: opts.refreshUrl,
            dataSrc: 'datas'
        },

        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass('tableRow');

        },
        initComplete: function (settings, json) {


        },
        drawCallback: function (settings) {

            $('[name="deleteRecord"]').on('click', function () {
                $.DeleteRecord(this);

            });
            $("tr.tableRow").on("click", function () {
                $.rowClick(this);
            });

        }


    });

    $.emptyValues = function(){
        $("#mainForm1")[0].reset();
        $("#formID").val(0);
    };
    var yesFunc = function () {$(this).dialog("close");};
    var noFunc = function () {$(this).dialog("close");};

    $.ConfirmDialog =  function (message, yesFunction){
        $('<div></div>').appendTo('body')
            .html('<div><h6>'+message+'?</h6></div>')
            .dialog({
                modal: true, title: 'Сообщение', zIndex: 10000, autoOpen: true,
                width: '250px', resizable: false,
                buttons: {
                    ДА: yesFunction,
                    Нет: function () {
                        //$('body').append('<h1>Confirm Dialog Result: <i>No</i></h1>');

                        $(this).dialog("close");
                    }
                },
                close: function (event, ui) {
                    $(this).remove();
                }
            });
    };


    $.DeleteRecord = function(elem){
        provId = $(elem).attr('id');
        provId= provId.replace('delRecord', '');
        yesFunc = function () {
            $.ajax({
                url: opts.deleteUrl,
                type: 'POST',
                data:{'id':provId},
                success: function(res){
                    var tableRow = $("td").filter(function() {
                        return $(this).text() == provId;
                    }).closest("tr");
                    $(tableRow).remove();
                    maintable.DataTable().ajax.reload();
                    $.emptyValues();
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
            $(this).dialog("close");
        };
        $.ConfirmDialog('Удалить запись', yesFunc);

    };
    $.rowClick = function(elem) {
        var tableData = $(elem).children("td").map(function() {
            return $(this).text();
        }).get();

        var $inputs = $(".mainForm :input, .mainForm select");

        var values = {};
        var i=0;
        $inputs.each(function() {
            if($(this).is("select")){
                console.log($(this));
                console.log(tableData[i]);
                $(this).children("option").filter(function () {
                    return this.text == tableData[i];
                }).attr("selected",true);
            }
            else if($(this).is("input")){
                $(this).val(tableData[i]);
            }
            else if($(this).is("textarea")){
                $(this).val(tableData[i]);
            }
            i++;
        });
    };

    $('#btnNew').on('click', function(){
        $.emptyValues();
    });

    $('#mainForm1').on('submit', function(){
        var formId = $('#formID').val();
        var url1 = opts.saveUrl;
        if(formId == 0)
        {
            url1 = opts.newUrl;
        }

        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data,
            success: function(res){
                maintable.DataTable().ajax.reload();
                $.emptyValues();
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });

        return false;
    });
    return $(this);
};
