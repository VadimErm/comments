<?php


namespace App\Models;


use Exception;

class User extends Model
{

    public $name;
    public $login;
    public $password;
    public $access_token;

    /**
     * Login user, if remember_me is true, access_token saved in cookies, else access_token saved in session.
     * @param $password
     * @param bool $remember_me
     * @return bool
     * @throws Exception
     */
    public function login($password, $remember_me = false)
    {

        if (isset($_COOKIE['errors'])) {

            setcookie('errors', '', time() - 60 * 24 * 30 * 12, '/');
        }


        if ($this->password === md5(md5($password))) {

            $this->access_token = $this->generateAccessToken(10);

            if (!$this->updateAccessToken()) {

                throw new Exception("Failed to update access_token");

            }
            if ($remember_me) {

                setcookie("access_token", $this->access_token, time() + 60 * 60 * 24 * 30);
            } else {
                if (isset($_COOKIE['access_token'])) {
                    setcookie("access_token", '', time() - 60 * 24 * 30 * 12);
                }
                session_start();

                $_SESSION['access_token'] = $this->access_token;
            }


            return true;
        } else {

            setcookie("access_token", '', time() - 60 * 24 * 30 * 12);
            setcookie('errors', '1', time() + 60 * 24 * 30 * 12, '/');

            return false;

        }



    }

    /**
     * Logout user, destroy session and  delete cookies
     * @return bool
     * @throws Exception
     */
    public function logout()
    {

        $this->access_token = '';
        if(!$this->updateAccessToken()){

            throw new Exception("Failed to update access_token");

        }
        session_start();
        if(isset($_SESSION['access_token'])){
            unset($_SESSION['access_token']);
            session_destroy();
        }

        setcookie("user_id", '', time() - 60*24*30*12);
        setcookie("access_token", '', time() - 60*24*30*12);
        session_destroy();

        return true;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function label()
    {
        return 'users';
    }

    /**
     * Generate access token depending on param length
     * @param int $length
     * @return string
     */
    protected function generateAccessToken($length = 6)
    {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return md5($code);

    }

    /**
     * Update access token when user sign in or sign out
     * @return bool
     */
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