<?php
    class ParentAction extends Action{
        public function _initialize(){  
            // auth
            $this->auth();

            // 声明页面编码
            header("Content-type: text/html; charset=utf-8");
            // 载入公共函数库
            load('@.function');
            load('@.Helper');

            // 设置站点导航active
            $this->setActive();
        }

        /**
         * 通过绑定menu_active
         */
        private function setActive(){
            $modAction = MODULE_NAME . "/" .ACTION_NAME;
            $this->assign('menu_active', $modAction);
        }

        private function auth(){
            // 检查token
            $token = $this->_param('token');
            if($token == 'WVdOMGJIcHVaR3clM0Q'){
                return true;
            }

            $login = $_SESSION['login'];
            if($login !== true){
                $uname = $this->_server('PHP_AUTH_USER');
                $pwd = $this->_server('PHP_AUTH_PW');
                if(is_null($uname) || is_null($pwd)){
                    header('WWW-Authenticate: Basic realm=""');
                    header('HTTP/1.0 401 Unauthorized');
                    die();
                }else{
                    $user = M("User")->where("uname='%s' and pwd='%s'", $uname, $pwd)->find();
                    if(count($user) > 0){
                        $_SESSION['login'] = true;
                        $_SESSION['uid'] = $user['uid'];
                        $_SESSION['uname'] = $user['uname'];
                        $_SESSION['pwd'] = $user['pwd'];
                        $_SESSION['role'] = $user['role'];
                    }else{
                        header('WWW-Authenticate: Basic realm="wrong uname or pwd"');
                        header('HTTP/1.0 401 Unauthorized');
                        die();
                    }
                }
            }
            return true;
        }
    }