<?php
class Register{

    public static function registerUser(){
        $controll = array(0=>false,1=>'error');
        if(isset($_POST['save']))
        {
            $errorString="";
            $name = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            if ($name === '' || !preg_match('/^.{1,100}$/us', $name)) {
                $errorString="Enter your name (up to 100 characters).";
            }
            if(!$email || strlen($email) > 50){
                $errorString="Invalid email";
            }
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            if (!$password || !$confirm || strlen($password) < 6)
            {
                 $errorString="Password must be more than 6 characters";
            }
            if($password !== $confirm){
                $errorString="Passwords do not match";
            }
            if( mb_strlen($errorString)== 0 )
            {
                $db = new Database();
                $existing = $db->getOne(
                    'SELECT user_id FROM users WHERE LOWER(email) = LOWER(:email) LIMIT 1',
                    array(':email' => $email)
                );
                if ($existing) {
                    return array(0 => false, 1 => 'An account with this email already exists.');
                }

                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql="INSERT INTO `users` (`username`, `email`, `password`, `status`, `pass`)
                      VALUES (:name, :email, :password, 'user', '')";
                $item = $db->executeRun($sql, array(
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $passwordHash
                ));
                    if($item)
                        $controll=array(0=>true);
                    else
                        $controll=array(0=>false,1=>'error');
            }
            else
            {
                $controll=array(0=>false,1=>$errorString);
            }
        }
        return $controll;

    }
}
?>