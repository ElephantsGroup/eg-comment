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
class AjaxController extends EGController
{
    private $message = [];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $comment_module = Yii::$app->getModule('comment');
        $response = [
            'status' => 500,
            'message' => $comment_module::t('Server problem')
        ];

        try
        {
            $this->validate($_POST);

            if(count($this->message) > 0)
                $response = [
                    'status' => 400,
                    'message' => implode("<br />",$this->message)
                ];
            else
            {
                $model = new Comment();
                if(isset($_POST['name'])) $model->name = $_POST['name'];
                if(isset($_POST['email'])) $model->email = $_POST['email'];
                if(isset($_POST['subject'])) $model->subject = $_POST['subject'];
                if(isset($_POST['description'])) $model->description = $_POST['description'];
                if(isset($_POST['item_id'])) $model->item_id = $_POST['item_id'];
                if(isset($_POST['item_version'])) $model->item_version = $_POST['item_version'];
                if(isset($_POST['service_id'])) $model->service_id = $_POST['service_id'];
                $model->user_id = (int) Yii::$app->user->id;
                $model->ip = Yii::$app->request->userIP;

                if($model->save())
                    $response = [
                        'status' => 200,
                        'message' => $comment_module::t('Thank you, Your message was sent')
                    ];
                else
                    $response = [
                        'status' => 400,
                        'message' => $comment_module::t('There is a problem on server, please come back later.')
                    ];
            }
        }
        catch(Exception $exp)
        {
            $response = [
                'status' => 500,
                'message' => $comment_module::t('Server problem')
            ];
        }
        return json_encode($response);
    }

    public function actionReply()
    {
        $comment_module = Yii::$app->getModule('comment');
        $response = [
            'status' => 500,
            'message' => $comment_module::t('Server problem')
        ];

        try
        {
            $this->validate($_POST);

            if(count($this->message) > 0)
                $response = [
                    'status' => 400,
                    'message' => implode("<br />",$this->message)
                ];
            else
            {
                $model = new Comment();
                if(isset($_POST['name'])) $model->name = $_POST['name'];
                if(isset($_POST['email'])) $model->email = $_POST['email'];
                if(isset($_POST['subject'])) $model->subject = $_POST['subject'];
                if(isset($_POST['description'])) $model->description = $_POST['description'];
                if(isset($_POST['item_id'])) $model->item_id = $_POST['item_id'];
                if(isset($_POST['service_id'])) $model->service_id = $_POST['service_id'];
                if(isset($_POST['comment_id']))
                {
                    $model->comment_id = $_POST['comment_id'];
                    $parent = Comment::findOne($_POST['comment_id']);
                    $model->level = $parent->level+1;
                }
                $model->user_id = (int) Yii::$app->user->id;
                $model->ip = Yii::$app->request->userIP;

                if($model->save())
                    $response = [
                        'status' => 200,
                        'message' => $comment_module::t('Thank you, Your message was sent'),
                        'comment_id' => $_POST['comment_id']
                    ];
                else
                    $response = [
                        'status' => 400,
                        'message' => $comment_module::t('There is a problem on server, please come back later.')
                    ];
            }
        }
        catch(Exception $exp)
        {
            $response = [
                'status' => 500,
                'message' => $comment_module::t('Server problem')
            ];
        }
        return json_encode($response);
    }

    private function validate($data)
    {
        $module = \Yii::$app->getModule('comment');

        if( $module->_required_name &&(!isset($_POST['name']) || empty($data['name'])))
            $this->message[]= $module::t('Please enter name');
        if( $module->_required_subject &&(!isset($_POST['subject']) || empty($data['subject'])))
            $this->message[]= $module::t('Please enter subject');
        if( $module->_required_email &&(!isset($_POST['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)))
            $this->message[]= $module::t('Please enter correct email');
        if( $module->_required_message &&(!isset($_POST['description']) || empty($data['description'])))
            $this->message[]= $module::t('Please enter message');
    }
}
