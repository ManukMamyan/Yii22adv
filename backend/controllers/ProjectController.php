<?php

namespace backend\controllers;

use common\models\User;
use common\services\AuthItems;
use Yii;
use common\models\Project;
use common\models\search\ProjectSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                        'allow' =>true,
                        'actions' => ['index', 'view'],
                        'roles' => [AuthItems::ROLE_DEVELOPER, AuthItems::ROLE_TESTER],
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
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $query = $model->getProjectUsers();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user_id = \common\models\User::find()->select('username')->indexBy('id')->column();

        $roles = $model->getUserRoles();

        if ($this->loadModel($model) && $model->save()) {
            if($diffRoles = array_diff_assoc($model->getUserRoles(), $roles)) {
                foreach ($diffRoles as $userId => $diffRole) {
                    Yii::$app->projectService->assignRole($model, User::findOne($userId), $diffRole);
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'user_id' => $user_id
        ]);
    }

    private function loadModel(Project $model) {
        $data = Yii::$app->request->post($model->formName());
        $projectUsers = $data[Project::RELATION_PROJECT_USER] ?? null;
        if($projectUsers !== null) {
            $model->projectUsers = $projectUsers === '' ? [] : $projectUsers;
        }
        return $model->load(Yii::$app->request->post());
    }

    /**
     * Deletes an existing Project model.
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
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
