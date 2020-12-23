<?php

namespace app\controllers;
use app\models\UploadImage;
use app\models\User;
use app\models\Article;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;


class AdminController extends Controller
{
    /**
     * @var null|User
     */
    private $_user = null;

    /**
     * @inheritdoc
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action): bool
    {

        if (parent::beforeAction($action)) {
            if (!\Yii::$app->user->can($action->id)) {
                throw new ForbiddenHttpException('Access denied');
            }
            return true;
        } else {
            return false;
        }
    }
    /**
     * Просмотр конкретной статьи
     * @return mixed
     */
    public function actionUpload(){
        $model = new UploadImage();
        if(Yii::$app->request->isPost){
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();
            return $this->render('upload', ['model' => $model]);
        }
        return $this->render('upload', ['model' => $model]);
    }


    /**
     * Просмотр конкретной статьи
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
     * Выбор статьи
     * @return mixed
     */

    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }

    /**
     * Создание новой статьм.
     * Если удаление будет успешным, то пользователя перекинет на страницу (view).
     * @return mixed
     */


    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionArticle()
    {
        $searchModel = new Article();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('article', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                Yii::info("Статья ID: {$model->id} была изменена {$this->_user->username}");
            } else {
                Yii::error("Статья ID: {$model->id} НЕ была изменена {$this->_user->username}. Возможные ошибки модели: " . json_encode($model->errors));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Удалить сстатью.
     * Если удаление будет успешным, то пользователя перекинет на главную страницу (article).
     * @param integer $id ID статьи.
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $modelId = $model->id;

        if ($model->delete()) {
            Yii::info("Статья ID: {$modelId} была удалена {$this->_user->username}");
        } else {
            Yii::error("Статья ID: {$modelId} НЕ была удалена {$this->_user->username}. Возможные ошибки модели: " . json_encode($model->errors));
        }

        return $this->redirect(['article']);
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');

    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordReset', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
            ]);
      }
}

