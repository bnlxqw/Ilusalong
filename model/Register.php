<?php
class Register{

    public static function registerUser(){
        $controll = array(0=>false,1=>'error');
        if(isset($_POST['save']))
        {
            $errorString="";
            $name = $_POST['name'];
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            if(!$email){
                $errorString="Invalid email";
            }
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];
            if (!$password || !$confirm || mb_strlen($password) < 6)
            {
                 $errorString="Password must be more than 6 characters";
            }
            if($password!=$confirm){
                $errorString="Passwords do not match";
            }
            if( mb_strlen($errorString)== 0 )
            {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $date=Date("Y-m-d");

                $sql="INSERT INTO `users` (`username`, `email`, `password`, `status`,
                `registration_date`) VALUES (:name, :email, :password, 'user', :date)";
                $db = new Database();
                $item = $db->executeRun($sql, array(
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $passwordHash,
                    ':date' => $date
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