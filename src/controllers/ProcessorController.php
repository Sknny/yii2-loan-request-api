<?php

namespace app\controllers;

use app\models\LoanRequest;
use Spatie\Async\Pool;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\mutex\Mutex;

class ProcessorController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $delay = Yii::$app->request->get('delay', 5);

        // Getting all pending
        $requests = LoanRequest::find()->where(['status' => 'pending'])->all();

        // Group by user_id for async
        $requestsByUser = ArrayHelper::index($requests, null, 'user_id');

        $pool = Pool::create();

        foreach ($requestsByUser as $userId => $userRequests) {
            foreach ($userRequests as $request) {
                $pool[] = async(function () use ($request, $delay, $userId) {
                    // Locking user avoiding multiple approval
                    $mutex = Yii::$app->mutex;
                    if ($mutex->acquire("user-lock-$userId")) {
                        try {
                            sleep($delay);

                            if (!LoanRequest::find()->where(['user_id' => $userId, 'status' => 'approved'])->exists()) {
                                $request->status = rand(1, 100) <= 10 ? 'approved' : 'declined';
                            } else {
                                $request->status = 'declined';
                            }

                            if (!$request->save()) {
                                throw new \Exception('Failed to save LoanRequest: ' . json_encode($request->errors));
                            }
                        } catch (\Exception $e) {
                            Yii::error([
                                'message' => 'Error processing loan request',
                                'userId' => $userId,
                                'requestId' => $request->id,
                                'error' => $e->getMessage(),
                            ], __METHOD__);
                        } finally {
                            $mutex->release("user-lock-$userId");
                        }
                    } else {
                        Yii::warning("Failed to acquire lock for user $userId", __METHOD__);
                    }
                });
            }
        }

        try {
            $pool->wait();
        } catch (\Exception $e) {
            Yii::error([
                'message' => 'Error while waiting for pool to finish',
                'error' => $e->getMessage(),
            ], __METHOD__);
        }

        return ['result' => true];
    }
}