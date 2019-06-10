<?php

namespace frontend\controllers;

use common\models\Project;
use common\models\ProjectUser;
use common\models\query\TaskQuery;
use common\models\User;
use common\services\AuthItems;
use Yii;
use common\models\Task;
use common\models\search\TaskSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException('У вас нет доступа к этой странице');
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [AuthItems::ROLE_MANAGER],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['take', 'complete'],
                        'roles' => [AuthItems::ROLE_DEVELOPER],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => [AuthItems::ROLE_TESTER],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $projects = Project::find()
            ->byUser(\Yii::$app->user->id)
            ->select('title')
            ->indexBy('id')
            ->column();
        $activeUsers = User::find()
            ->allUsersOfProject(\Yii::$app->user->id)
            ->onlyActive()
            ->select('username')
            ->indexBy('id')
            ->column();

        /** @var $query TaskQuery */
        $query = $dataProvider->query;
        $query->byUser(Yii::$app->user->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'projects' => $projects,
            'activeUsers' => $activeUsers
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $projects = $this->getProjectWithUserAndRole();

        return $this->render('create', [
            'model' => $model,
            'projects' => $projects
        ]);
    }

    public function actionTake($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->identity;
        $userRoles = $model->getTaskUserRoles();

        if (Yii::$app->taskService->takeTask($model, $user)) {
            Yii::$app->session->setFlash('success', 'Вы успешно взяли задачу');
            foreach ($userRoles as $userId => $role) {
                if ($role === ProjectUser::ROLE_MANAGER) {
                    Yii::$app->taskService->assignTaskEvent($model, User::findOne($userId));
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionComplete($id)
    {
        $model = $this->findModel($id);
        $userRoles = $model->getTaskUserRoles();

        if (Yii::$app->taskService->completeTask($model)) {
            Yii::$app->session->setFlash('success', 'Вы успешно завершили задачу');
            foreach ($userRoles as $userId => $role) {
                if ($role === ProjectUser::ROLE_MANAGER || $role === ProjectUser::ROLE_TESTER) {
                    Yii::$app->taskService->completeTaskEvent($model, User::findOne($userId));
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $projects = $this->getProjectWithUserAndRole();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'projects' => $projects
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getProjectWithUserAndRole()
    {
        return Project::find()
            ->byUser(Yii::$app->user->id, \common\models\ProjectUser::ROLE_MANAGER)
            ->select('title')
            ->indexBy('id')
            ->column();
    }
}
