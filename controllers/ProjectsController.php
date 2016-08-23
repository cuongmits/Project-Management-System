<?php

namespace app\controllers;

use Yii;
use app\models\Projects;
use app\models\ProjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ProjectUser;
use yii\helpers\ArrayHelper;
use app\models\Users;
use app\models\Roles;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends Controller
{
    /**
     * {@inheritdoc}
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

    /**
     * Lists all Projects models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projects model.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Get keys of duplicated items in array.
     *
     * @param type $arr
     */
    private function get_dup_keys($arr)
    {
        $keys = [];
        $vals = [];
        foreach ($arr as $key => $val) {
            unset($arr[$key]);
            if (in_array($val, $arr) || in_array($val, $vals)) {
                $keys[] = $key;
                $vals[] = $val;
            }
        }

        return $keys;
    }

    /**
     * Stardardize array so index will run from 0...
     *
     * @param type $arr
     */
    private function standardize($arr)
    {
        $new_arr = [];
        foreach ($arr as $item) {
            $new_arr[] = $item;
        }

        return $new_arr;
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projects();

        if ($model->load(Yii::$app->request->post())) {
            $is_project_valid = $model->validate();
            $is_users_valid = true;
            $has_no_user_duplicated = true;
            $pur_models = [];

            $pur_objects = Yii::$app->request->post('ProjectUser');
            if (isset($pur_objects)) {
                $pur_objects = $this->standardize($pur_objects);
                foreach ($pur_objects as $pur_object) {
                    $pur_model = new ProjectUser();
                    $pur_model->setScenario('create_project');
                    $pur_model->attributes = $pur_object;
                    $pur_models[] = $pur_model;
                    $is_users_valid = $is_users_valid && $pur_model->validate();
                }
                //check if duplicated
                $has_no_user_duplicated = (count($pur_objects) === count(array_unique($pur_objects, SORT_REGULAR)));
                $msgs['dup_keys'] = $this->get_dup_keys($pur_objects);
            }
            if ($is_project_valid && $is_users_valid && $has_no_user_duplicated) {
                $db_trans = Yii::$app->db->beginTransaction();
                $is_project_saved = $model->save();
                if ($is_project_saved) {
                    $is_pur_models_saved = true;
                    foreach ($pur_models as $pur_model) {
                        $pur_model->project_id = $model->id;
                        $is_pur_models_saved = $is_pur_models_saved && $pur_model->save();
                    }
                    if ($is_pur_models_saved) {
                        $db_trans->commit();

                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $db_trans->rollBack();
                        $msgs['info'] = 'Cannot save User(s).';
                    }
                } else {
                    $db_trans->rollBack();
                    $msgs['info'] = 'Cannot save Project.';
                }
            } else {
                $msgs['info'] = 'Input data is not valid. '; // It maybe because of duplicate User and his/her role or input data is not valid.";
                if (!$is_project_valid) {
                    $msgs['info'] .= 'Project data is not valid. ';
                }
                if (!$is_users_valid) {
                    $msgs['info'] .= 'User data is not valid. ';
                }
                if (!$has_no_user_duplicated) {
                    $msgs['info'] .= 'Duplicate User & his Role. ';
                }
            }

            return $this->render('create', [
                'model' => $model,
                'pur_models' => $pur_models,
                'msgs' => $msgs,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Projects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $is_project_valid = $model->validate();
            $is_users_valid = true;
            $has_no_user_duplicated = true;
            $pur_models = [];

            $pur_objects = Yii::$app->request->post('ProjectUser');
            $pur_object_indexes = Yii::$app->request->post('ProjectUserIndex');
            if (isset($pur_objects)) {
                $pur_objects = $this->standardize($pur_objects);
                $pur_object_indexes = $this->standardize($pur_object_indexes);
                foreach ($pur_object_indexes as $key => $pur_object_index) {
                    if ($pur_object_index !== '') {
                        $pur_model = ProjectUser::findOne($pur_object_index);
                    } else {
                        $pur_model = new ProjectUser();
                        $pur_model->setScenario('create_project');
                        $pur_model->project_id = $id;
                    }
                    $pur_model->attributes = $pur_objects[$key];
                    $pur_models[] = $pur_model;
                    $is_users_valid = $is_users_valid && $pur_model->validate();
                }
                //check if duplicated
                $has_no_user_duplicated = true && (count($pur_objects) === count(array_unique($pur_objects, SORT_REGULAR)));
                $msgs['dup_keys'] = $this->get_dup_keys($pur_objects);
            }
            if ($is_project_valid && $is_users_valid && $has_no_user_duplicated) {
                $db_trans = Yii::$app->db->beginTransaction();
                $is_project_saved = $model->save();
                if ($is_project_saved) {
                    $is_pur_models_saved = true;
                    foreach ($pur_models as $pur_model) {
                        $is_pur_models_saved = $is_pur_models_saved && $pur_model->save();
                    }
                    if ($is_pur_models_saved) {
                        $db_trans->commit();

                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $db_trans->rollBack();
                        $msgs['info'] = 'Cannot save User(s).';
                    }
                } else {
                    $db_trans->rollBack();
                    $msgs['info'] = 'Cannot save Project.';
                }
            } else {
                $msgs['info'] = 'Input data is not valid. '; // It maybe because of duplicate User and his/her role or input data is not valid.";
                if (!$is_project_valid) {
                    $msgs['info'] .= 'Project data is not valid. ';
                }
                if (!$is_users_valid) {
                    $msgs['info'] .= 'User data is not valid. ';
                }
                if (!$has_no_user_duplicated) {
                    $msgs['info'] .= 'Duplicate User & his Role. ';
                }
            }

            return $this->render('update', [
                'model' => $model,
                'pur_models' => $pur_models,
                'msgs' => $msgs,
            ]);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $pur_models = ProjectUser::findAll(['project_id' => $id]);

            return $this->render('update', [
                'model' => $model,
                'pur_models' => $pur_models,
            ]);
        }
    }

    /**
     * Deletes an existing Projects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (ProjectUser::find()->where(['project_id' => $id])->exists()) {
            return $this->redirect(['index', 'success' => -1, 'id' => $id]);
        } else {
            $this->findModel($id)->delete();

            return $this->redirect(['index', 'success' => 1]);
        }
    }

    /**
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     *
     * @return Projects the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Add User(s) to new Project.
     */
    public function actionAjaxAddUserToNewProject($index)
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\BadRequestHttpException();
        }

        $model = new ProjectUser();
        $form = ActiveForm::begin();

        $output = "<div class='add_user_item'>";
        $output .= "<h4>User { $index }</h4>";

        $output .= $form->field($model, "[$index]user_id")->dropDownList(
            ArrayHelper::map(Users::find()->all(), 'id', 'fullname'),
            ['prompt' => 'Choose User', 'name' => 'ProjectUser['.$index.'][user_id]']
            )->label(false);

        $output .= $form->field($model, "[$index]role_id")->dropDownList(
            ArrayHelper::map(Roles::find()->all(), 'id', 'role'),
            ['prompt' => 'Choose Role', 'name' => 'ProjectUser['.$index.'][role_id]']
            )->label(false);

        $output .= "<a class='btn btn-danger' val='js_block_$index' href='#'>Cancel</a></div>";

        $js_script = '<div id="js_block_'.$index.'">
            <script>
                jQuery(document).ready(function () {
                    $("#w0").yiiActiveForm("add", {
                        "id":"projectuser-'.$index.'-user_id",
                        "name":"['.$index.']user_id",
                        "container":".field-projectuser-'.$index.'-user_id",
                        "input":"#projectuser-'.$index.'-user_id",
                        "validate":function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/, "message":"User ID must be an integer.", "skipOnEmpty":1});
                                yii.validation.required(value, messages, {"message":"User ID cannot be blank."});
                        }
                    });
                    $("#w0").yiiActiveForm("add", {
                        "id":"projectuser-'.$index.'-role_id",
                        "name":"['.$index.']role_id",
                        "container":".field-projectuser-'.$index.'-role_id",
                        "input":"#projectuser-'.$index.'-role_id",
                        "validate":function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/, "message":"Role ID must be an integer.", "skipOnEmpty":1});
                                yii.validation.required(value, messages, {"message":"Role ID cannot be blank."});
                        }
                    });
                });
            </script></div>';

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return ['html_script' => $output, 'js_script' => $js_script];
    }

    /**
     * Add/Edit User(s) to Updating Project.
     */
    public function actionAjaxAddUserToUpdateProject($index)
    {
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\BadRequestHttpException();
        }

        $model = new ProjectUser();
        $form = ActiveForm::begin();

        $output = "<div class='add_user_item'>";
        $output .= "<h4>User { $index }</h4>";

        $output .= $form->field($model, "[$index]user_id")->dropDownList(
            ArrayHelper::map(Users::find()->all(), 'id', 'fullname'),
            ['prompt' => 'Choose User', 'name' => 'ProjectUser['.$index.'][user_id]']
            )->label(false);

        $output .= $form->field($model, "[$index]role_id")->dropDownList(
            ArrayHelper::map(Roles::find()->all(), 'id', 'role'),
            ['prompt' => 'Choose Role', 'name' => 'ProjectUser['.$index.'][role_id]']
            )->label(false);

        $output .= $form->field($model, "[$index]id")->input('hidden', ['name' => 'ProjectUserIndex[]'])->label(false);

        $output .= "<a class='btn btn-danger' val='js_block_$index' href='#'>Cancel</a></div>";

        $js_script = '<div id="js_block_'.$index.'">
            <script>
                jQuery(document).ready(function () {
                    $("#w0").yiiActiveForm("add", {
                        "id":"projectuser-'.$index.'-user_id",
                        "name":"['.$index.']user_id",
                        "container":".field-projectuser-'.$index.'-user_id",
                        "input":"#projectuser-'.$index.'-user_id",
                        "validate":function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/, "message":"User ID must be an integer.", "skipOnEmpty":1});
                                yii.validation.required(value, messages, {"message":"User ID cannot be blank."});
                        }
                    });
                    $("#w0").yiiActiveForm("add", {
                        "id":"projectuser-'.$index.'-role_id",
                        "name":"['.$index.']role_id",
                        "container":".field-projectuser-'.$index.'-role_id",
                        "input":"#projectuser-'.$index.'-role_id",
                        "validate":function (attribute, value, messages, deferred, $form) {
                        yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/, "message":"Role ID must be an integer.", "skipOnEmpty":1});
                                yii.validation.required(value, messages, {"message":"Role ID cannot be blank."});
                        }
                    });
                });
            </script></div>';

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return ['html_script' => $output, 'js_script' => $js_script];
    }

    /**
     * On the Project Update screen:
     * Delete PUR Relationship by Ajax.
     *
     * @param type $id
     */
    public function actionAjaxDeleteUserFromProject()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if (ProjectUser::deleteAll(['id' => $id]) > 0) {
                return 'success';
            } else {
                return 'fail';
            }
        } else {
            throw new \yii\web\BadRequestHttpException();
        }
    }
}
