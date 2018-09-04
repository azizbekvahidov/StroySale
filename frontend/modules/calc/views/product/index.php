<?$measure = new \app\models\Measure(); $category = new \app\models\Category()?>
<?php
$js = <<<JS

$("#mainTable").Custom({
    Columns:[
            {"data":'productId'},
            {"data":'name'},
            {"data":'measure'},
            {"data":'category'},
            {
                "mDataProp": function (source, type, val) {
                    if (type === 'set') {

                        return;
                    }
                    else if (type === 'display') {
                        return '<a href="#" class="btn btn-default" name="deleteRecord" id="delRecord' + source.productId + '"><span class="oi oi-x"></span></a>';
                    }

                },
                "sDefaultContent": '<a href="#" class="btn btn-default" name="deleteRecord"><span class="oi oi-x"></span></a>'
            },

        ],
    tableId:"#categoryTable",
    refreshUrl:"/calc/product/refreshd",
    deleteUrl:"/calc/product/delete",
    saveUrl:"/calc/product/save",
    newUrl:"/calc/product/new"

});


JS;

$this->registerJs($js);
$product = new \app\models\Product();
?>
<h3>Продукты</h3>
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
                    <input type="text"  class="form-control" name="name" id="inputProductName" placeholder="введите наименование">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticId" class="col-sm-2 col-form-label">Ед.Изм.</label>
                <div class="col-sm-5">
                    <?=\yii\helpers\Html::dropDownList("measureId",'',\yii\helpers\ArrayHelper::map(\app\models\Measure::find()->all(),'measureId','name'),array('class'=>"form-control"))?>
                </div>
            </div>
            <div class="form-group row pull-right">
                <label for="staticProvider" class="col-sm-2 col-form-label">Категория</label>
                <div class="col-sm-5">
                    <?=\yii\helpers\Html::dropDownList("categoryId",'',\yii\helpers\ArrayHelper::map(\app\models\Category::find()->all(),'categoryId','name'),array('class'=>"form-control"))?>
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
            <th scope="col">Категория</th>
            <th></th>
        </tr>
        </thead>
        <?php if(isset($models)):?>
            <tbody>

            <?php foreach($models->all() as $model){?>
                <tr class="tableRow">
                    <td scope="row"><?= $model->productId ?></td>
                    <td><?= $model->name ?></td>
                    <td><?= $model->measure->name?></td>
                    <td><?= $model->category->name?></td>
                    <td></td>
                </tr>

            <?php } ?>
            </tbody>
        <?php endif;?>
    </table>

</div>