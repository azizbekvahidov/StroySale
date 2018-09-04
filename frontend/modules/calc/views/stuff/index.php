<?$measure = new \app\models\Measure(); $category = new \app\models\Category()?>
<?php
$js = <<<JS

$("#mainTable").Custom({
    Columns:[
            {"data":'stuffId'},
            {"data":'name'},
            {"data":'measure'},
            {"data":'salary'},
            {"data":'energy'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.stuffId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    tableId:"#categoryTable",
    refreshUrl:"/calc/stuff/refreshd",
    deleteUrl:"/calc/stuff/delete",
    saveUrl:"/calc/stuff/save",
    newUrl:"/calc/stuff/new"

});


JS;

$this->registerJs($js);
$product = new \app\models\Stuff();
?>
<h3>Продукция</h3>
<div id="error"></div>
<?php// print_r($models->all()[0]->name) ?>
<div class="row">
    <div class="col">
        <form class="mainForm" id="mainForm1" action="/category/save" method="POST">
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" readonly class="form-control-plaintext" name="productId" id="formID" value="0">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticProvider" class="col-sm-2 col-form-label">Наименование</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="name" id="inputStuffName" placeholder="Введите наименование">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Ед.Изм.</label>
                <div class="col-sm-5">
                    <?=\yii\helpers\Html::dropDownList("measureId",'',\yii\helpers\ArrayHelper::map(\app\models\Measure::find()->all(),'measureId','name'),array('class'=>"form-control"))?>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticProvider" class="col-sm-2 col-form-label">Зар. плата</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="salary" id="inputStuffSalary" placeholder="Зар. плата">
                </div>
            </div>
            <div class="form-group row ">
                <label for="staticProvider" class="col-sm-2 col-form-label">Энергия</label>
                <div class="col-sm-5">
                    <input type="text"  class="form-control" name="energy" id="inputStuffEnergy" placeholder="Энергия">
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
            <th scope="col">Наиенование</th>
            <th scope="col">Ед.Изм</th>
            <th scope="col">Зар.плата</th>
            <th scope="col">Энергия</th>
            <th></th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>

            <?php foreach($models->all() as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model->stuffId ?></td>
                    <td><?= $model->name ?></td>
                    <td><?= $model->measure->name?></td>
                    <td><?= $model->salary?></td>
                    <td><?= $model->energy?></td>
                    <td></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php endif;?>
    </table>

</div>