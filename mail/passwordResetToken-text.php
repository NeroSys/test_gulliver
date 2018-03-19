<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 3/19/18
 * Time: 6:20 PM
 */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

    Hello <?= $user->username ?>,
    Follow the link below to reset your password:

<?= $resetLink ?>