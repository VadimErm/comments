<?php


namespace App\Models;


use Exception;

class User extends Model
{

    public $name;
    public $login;
    public $password;
    public $access_token;

    public function login($password)
    {
        if (isset($_COOKIE['errors'])){

            setcookie('errors', '', time() - 60*24*30*12, '/');
        }

            if($this->password === md5(md5($password)))
            {

                $this->access_token = $this->genereteAccessToken(10);

                if(!$this->updateAccessToken()){

                    throw new Exception("Failed to update access_token");

                }

                setcookie("user_id", $this->id, time()+60*60*24*30);
                setcookie("access_token", $this->access_token, time()+60*60*24*30);

                return true;
            }
            else
            {
                setcookie("user_id", '', time() - 60*24*30*12);
                setcookie("access_token", '', time() - 60*24*30*12);
                setcookie('errors', '1', time() + 60*24*30*12, '/');

                return false;

            }


    }

    public function logout()
    {
        $this->access_token = '';
        if(!$this->updateAccessToken()){

            throw new Exception("Failed to update access_token");

        }

        setcookie("user_id", '', time() - 60*24*30*12);
        setcookie("access_token", '', time() - 60*24*30*12);

        return true;
    }

    public function label()
    {
        return 'users';
    }

    protected function genereteAccessToken($length = 6)
    {

            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
            $code = "";
            $clen = strlen($chars) - 1;
            while (strlen($code) < $length) {
                $code .= $chars[mt_rand(0,$clen)];
            }
            return md5($code);

    }

    protected function updateAccessToken()
    {
        try{
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_db->beginTransaction();

            $query = $this->_db->prepare('UPDATE users SET  access_token = :access_token WHERE id = :id LIMIT 1');
            $query->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $query->bindValue(':access_token', $this->access_token);

            $query->execute();

            $this->_db->commit();

            return true;

        } catch (Exception $e) {

            $this->_db->rollBack();

            return false;
        }


    }





}