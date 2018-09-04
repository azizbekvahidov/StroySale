<?php

namespace app\modules\calc\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class StuffController extends Controller
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

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $models = \app\models\Stuff::find();
        return $this->render('index', [
            'models' => $models,
        ]);
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
            $model = new \app\models\Stuff();
            if ($model->load(Yii::$app->request->post(), '')) {
                $id = Yii::$app->request->post()['stuffId'];
                $model = \app\models\Stuff::findOne(['stuffId'=>$id]);

                $model->stuffId = Yii::$app->request->post()['stuffId'];
                $model->name = Yii::$app->request->post()['name'];
                $model->measureId = Yii::$app->request->post()['measureId'];
                $model->salary = Yii::$app->request->post()['salary'];
                $model->energy = Yii::$app->request->post()['energy'];
                $model->save();
                $models = \app\models\Stuff::find()->all();
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
        $form_model =  new \app\models\Stuff();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->stuffId = Yii::$app->request->post()['stuffId'];
            $form_model->name = Yii::$app->request->post()['name'];
            $form_model->measureId = Yii::$app->request->post()['measureId'];
            $form_model->salary = Yii::$app->request->post()['salary'];
            $form_model->energy = Yii::$app->request->post()['energy'];
            $form_model->save();
            $models = \app\models\Stuff::find();
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

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {
            $rowCnt = \app\models\Stuff::deleteAll('stuffId='.$id);
            return $rowCnt;
        }  catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }
    public function actionRefreshd()
    {

        $models = \app\models\Stuff::find()->all();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
            \app\models\Stuff::class =>[
                'stuffId',
                'name',
                'salary',
                'energy',
                'measure'=>function($data){
                    return $data->measure->name;
                },
            ],

        ]);
        return ['datas' =>$arr];
    }
}
