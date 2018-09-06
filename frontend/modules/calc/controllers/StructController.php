<?php

namespace app\modules\calc\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class StructController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSave()
    {
        $isAjax = false;
        try{
            if(\Yii::$app->request->isAjax){
                $isAjax = TRUE;

// return 'Запрос принят!';
            }
            //$form_model->load(\Yii::$app->request->post());
            $model = new \app\models\Struct();
            if ($model->load(Yii::$app->request->post(), '')) {
                $id = Yii::$app->request->post()['structId'];
                $model = \app\models\Struct::findOne(['structId'=>$id]);

                $model->structId = Yii::$app->request->post()['structId'];
                $model->stuffId = Yii::$app->request->post()['stuffId'];
                $model->stuffProdId = Yii::$app->request->post()['stuffProdId'];
                $model->cnt = Yii::$app->request->post()['cnt'];
                $model->idType = Yii::$app->request->post()['idType'];
                $model->save();
                $models = \app\models\Struct::find()->all();
                // var_dump($model);
                if($isAjax)
                {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return $model->toArray();

                }else
                    return $this->render('index', ['models'=> $models]);

            }
        }catch(\yii\db\Exception $ex)
        {
            echo $ex->getMessage();
        }
        //var_dump($form_model);
        //return $this->render('index', ['model'=> $model]);
    }

    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new \app\models\Struct();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->structId = Yii::$app->request->post()['structId'];
            $form_model->stuffId = Yii::$app->request->post()['stuffId'];
            $form_model->stuffProdId = Yii::$app->request->post()['stuffProdId'];
            $form_model->cnt = Yii::$app->request->post()['cnt'];
            $form_model->idType = Yii::$app->request->post()['idType'];
            $form_model->save();
            $models = \app\models\Struct::find();
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->toArray();

            }else
                return $this->render('index', ['models'=> $models]);

        }
        //var_dump($form_model);
        return $this->render('index', ['model'=> $form_model]);
    }

    public function actionRefresh()
    {

        $models = \app\models\Struct::find()->where(['stuffId'=> 12])->all();
        try {
            \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
            $arr=ArrayHelper::toArray($models, [
                \app\models\Struct::class=>[
                    'structId',
                    'stuffProdId',
                    'prodName'=>function ($data) {
                        return ($data->idType == 0) ? $data->stuffProd->name : $data->stuffStuff->name;
                    },
                    'measure'=>function ($data) {
                        return ($data->idType == 0) ? $data->stuffProd->measure->name : $data->stuffStuff->measure->name;
                    },
                    'cnt',
                    'idType',
                ],
            ]);
        }
        catch (Exception $ex){
            echo $ex->getMessage();
        }
        return $this->asJson($arr);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $rowCnt = \app\models\Struct::deleteAll('structId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function actionRefreshProdType(){
        $list = array();
        try {
            if (Yii::$app->request->post("val") == "1") {
                $list=\yii\helpers\ArrayHelper::map(\app\models\Stuff::find()->all(), 'stuffId', 'name');
            } else {
                $list=\yii\helpers\ArrayHelper::map(\app\models\Product::find()->all(), 'productId', 'name');
            }
        }
        catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }
        return $this->asJson($list);
    }
}
