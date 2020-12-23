<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\User;
class UserGroupRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {

        if (!\Yii::$app->user->isGuest) {
            $role = \Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $role == 'admin';
            } elseif ($item->name === 'BRAND') {
                return $role == 'admin' || $role == 'BRAND';
            } elseif ($item->name === 'TALENT') {
                return $role == 'admin' || $role == 'TALENT';
            }
        }else{
            print('true');
//            exit();
        }

        return true;
    }
}