<?php

namespace elephantsGroup\comment\controllers;

use Yii;
use elephantsGroup\comment\models\Comment;
use elephantsGroup\comment\models\CommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use elephantsGroup\base\EGController;

/**
 * AdminController implements the CRUD actions for Comment model.
 */
class AdminController extends EGController
{
    private $message = [];
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [];
        // $behaviors['verbs'] = [
        //     'class' => VerbFilter::className(),
        //     'actions' => [
        //         'delete' => ['post'],
        //     ],
        // ];
        return $behaviors;
    }

    /**
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else
                var_dump($model->errors); die;
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionConfirm($id, $redirectUrl)
    {
       $comment_module = Yii::$app->getModule('comment');
       $response = [
               'status' => 500,
               'message' => $comment_module::t('comment', 'Server problem')
           ];
           try
           {
               $comment = $this->findModel($id);
               if (!$comment)
               {
                   $response = [
                       'status' => 500,
                       'message' => $comment_module::t('comment', 'Comment Not Found.')
                   ];
               }

               if ($comment->confirm())
               {
                   $response = [
                       'status' => 200,
                       'message' => $comment_module::t('comment', 'Successful')
                   ];
               }
               else
               {
                   $response = [
                       'status' => 500,
                       'message' => $comment_module::t('comment', 'cant set to confirm')
                   ];
               }
           }
           catch (Exception $exp)
           {
               $response = [
                   'status' => 500,
                   'message' => $comment_module::t('comment', $exp)
               ];
           }
       return $this->redirect($redirectUrl);
     }

     public function actionDeny($id, $redirectUrl)
     {
       $comment_module = Yii::$app->getModule('comment');
       $response = [
               'status' => 500,
               'message' => $comment_module::t('comment', 'Server problem')
           ];
           try
           {
               $comment = $this->findModel($id);
               if (!$comment)
               {
                   $response = [
                       'status' => 500,
                       'message' => $comment_module::t('comment', 'Comment Not Found.')
                   ];
               }

               if ($comment->deny())
               {
                   //var_dump($id); die;
                   $response = [
                       'status' => 200,
                       'message' => $comment_module::t('comment', 'Successful')
                   ];
               }
               else
               {
                   $response = [
                       'status' => 500,
                       'message' => $comment_module::t('comment', 'cant set to deny')
                   ];
               }
           }
           catch (Exception $exp)
           {
               $response = [
                   'status' => 500,
                   'message' => $comment_module::t('comment', $exp)
               ];
           }

           //return json_encode($response);
           return $this->redirect($redirectUrl);
     }

       public function actionArchive($id, $redirectUrl)
       {
           $comment_module = Yii::$app->getModule('comment');
           $response = [
               'status' => 500,
               'message' => $comment_module::t('comment', 'Server problem')
           ];
           try
           {
               $comment = $this->findModel($id);
               if (!$comment)
               {
                   $response = [
                       'status' => 500,
                       'message' => $comment_module::t('comment', 'Comment Not Found.')
                   ];
               }

               if ($comment->archive())
               {
                   //var_dump($id); die;
                   $response = [
                       'status' => 200,
                       'message' => $comment_module::t('comment', 'Successful')
                   ];
               }
               else
               {
                   $response = [
                       'status' => 500,
                       'message' => $comment_module::t('comment', 'cant set to archive')
                   ];
               }
           }
           catch (Exception $exp)
           {
               $response = [
                   'status' => 500,
                   'message' => $comment_module::t('comment', $exp)
               ];
           }

           //return json_encode($response);
           return $this->redirect($redirectUrl);
       }
    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
