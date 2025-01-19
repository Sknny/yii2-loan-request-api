<?php
namespace app\controllers;

use app\models\LoanRequest;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;

class RequestController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $data = $request->post();

        $model = new LoanRequest();
        $model->user_id = $data['user_id'] ?? null;
        $model->amount = $data['amount'] ?? null;
        $model->term = $data['term'] ?? null;

        if ($model->validate() && !$this->hasApprovedRequest($model->user_id)) {
            if ($model->save()) {
                Yii::$app->response->statusCode = 201;
                return ['result' => true, 'id' => $model->id];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['result' => false];
    }

    private function hasApprovedRequest($userId): bool
    {
        return LoanRequest::find()->where(['user_id' => $userId, 'status' => 'approved'])->exists();
    }
}