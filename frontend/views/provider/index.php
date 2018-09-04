
<?php
$js = <<<JS
$('#btnNew').on('click', function(){
        $('[name="providerId"]').val('0');
        $('[name="name"]').val('');
        $('[name="address"]').val('');
    });
    $('#mainForm1').on('submit', function(){
        var formId = $('#formID').val();
        var url1 = '/category/save';
        if(formId == 0)
        {
            url1 = '/category/new';
        
        }
        
        var data = $(this).serialize();
        $.ajax({
            url: url1,
            type: 'POST',
            data: data,
            success: function(res){
            
            if(formId == 0)
            {
                var str = '<tr class="tableRow"><td>'+res.providerId+'</td>'+
                '<td>'+res.name+'</td>'+
                '<td>'+res.address+'</td>'
                    '<td><a href="#" class="btn btn-default" name="deleteRecord" id="delRecord'+res.providerId+'"><span class="oi oi-x"></span></a></td></tr>';

                $('#mainTable >  tbody:last').append(str);
            }else
            {
               var tableRow = $("td").filter(function() {
                    return $(this).text() == formId;
                }).closest("tr");
                
                $(tableRow).find('td:eq(1)').text(res.name);
                $(tableRow).find('td:eq(2)').text(res.address);
                
            
            }
            
                                
        
                
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
        $('#mainTable').DataTable().ajax.reload();
        return false; 
    });    
        
    $('[name="deleteRecord"]').on('click', function() {
        
        provId = $(this).attr('id');
        provId= provId.replace('delRecord', '');
        yesFunc = function () {
                $.ajax({
                url: '/provider/delete',
                type: 'POST',
                data:{'id':provId},
                success: function(res){
                    var tableRow = $("td").filter(function() {
                        return $(this).text() == provId;
                    }).closest("tr");
                    $(tableRow).remove();
                    
                    console.log(res+ " record deleted");
                    
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
            $(this).dialog("close");
        };  
        $.ConfirmDialog('Удалить запись', yesFunc);
        
   });
       
    
        
JS;
 
$this->registerJs($js);
?>

<h3>Поставщики</h3>
<div id="error"></div>            
<?php// print_r($models->all()[0]->name) ?>
<div class="row">
                <div class="col">
                    <form class="mainForm" id="mainForm1" action="/provider/save" method="POST">
                        <div class="form-group row">
                        <label for="staticId" class="col-sm-2 col-form-label">ID</label>
                        <div class="col-sm-6">
                          <input type="text" readonly class="form-control-plaintext" name="providerId" id="formID" value="0">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="staticProvider" class="col-sm-2 col-form-label">Имя поставщика</label>
                        <div class="col-sm-6">
                          <input type="text"  class="form-control" name="name" id="inputProviderName" placeholder="введите имя поставщика">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputAdress" class="col-sm-2 col-form-label">Адрес</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" name="address" id="inputAdress" placeholder="введите адрес поставщика">
                        </div>
                          <div class="col-sm-2">
                              <button id="btnSave" type="submit" class="btn btn-primary"><span class="oi oi-check"></span> Сохранить</button>
                              
                        </div>
                          <div class="col-sm-2">
                              <a href="#" id="btnNew" class="btn btn-primary"><span class="oi oi-plus"></span> Новый</a>
                              
                        </div>
                          
                      </div>
                    </form>
                </div>
                
            </div>
            <br/>
            <br/>
            
            
                <div class="col">
                    <table class="table" id="mainTable">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Имя поставщика</th>
                          <th scope="col">Адрес</th>
                          <th></th>
                        </tr>
                      </thead>
                      <?php if(isset($models)):?>
                      <tbody>
                          
                          <?php foreach($models->all() as $model){?>
                        <tr class="tableRow">
                          <td scope="row"><?= $model->providerId ?></td>
                          <td><?= $model->name ?></td>
                          <td><?= $model->address ?></td>
                          <td></td>
                        </tr>
                       
                          <?php } ?>
                      </tbody>
                      <?php endif;?>
                    </table>
                   
                </div>
            
 
