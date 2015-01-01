<?php
class ChangePasswordForm extends CFormModel
{
    const PASSWORD_LENGTH = 7;
    public $currentPassword;
    public $newPassword;
    public $newPassword_repeat;
    private $_user;   

    public function rules()
    {
      return array(
          array(
            'currentPassword', 'compareCurrentPassword'
          ),
          array(
            'currentPassword, newPassword, newPassword_repeat', 'required',
            'message'=>'Introduzca su {attribute}.',
          ),
          array(
            'newPassword_repeat', 'compare',
            'compareAttribute'=>'newPassword',
            'message'=>'La contraseña nueva no coincide.',
          ),
        );
    }

    public function compareCurrentPassword($attribute,$params)
    {
        if( md5($this->currentPassword) !== $this->_user->password )
        {
            $this->addError($attribute,'La contraseña actual es incorrecta');
        }
    }
  
    public function init()
    {
        //$this->_user = User::model()->findByAttributes( array( 'username'=>Yii::app()->User->username ) );
    }
  
    public function attributeLabels()
    {
        return array(
          'currentPassword'=>'Contraseña actual',
          'newPassword'=>'Nueva contraseña',
          'newPassword_repeat'=>'Nueva contraseña (Repetir)',
        );
    }
  
    public function changePassword()
    {
        $this->_user->password = $this->newPassword;
        if( $this->_user->save() )
          return true;
        return false;
    }
}


    public function reset($attribute, $params)
    {
        $model = User::model()->find('email=:email', array(':email' => $this->email));
        if (!isset($model)) {
            $this->addError('email', 'Email not found!');
        }
    }

    public function resetPassword()
    {
        $model = User::model()->find('email=:email', array(':email' => $this->email));
        $password = self::generatePassword();
        $model->password = $model->hashPassword($password);


        $message = Yii::app()->mailer
            ->createMessage('Password reset', "Username: {$model->username}\n Password: {$password}")
            ->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminName']))
            ->setTo(array($model->email => $model->username));

        if (Yii::app()->mailer->send($message)) {
            $model->save(false);
            return true;
        } else {
            return false;
        }
    }

    public static function generatePassword($length = self::PASSWORD_LENGTH)
    {
        $chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($chars);
        return implode(array_slice($chars, 0, $length));
    }
}
