<?php
    class UserModel extends Model{
        public function updatePwd($uname, $oldPwd, $newPwd, $confirmPwd){
            $retCheck = $this->where("uname='%s' and pwd='%s'", $uname, $oldPwd)->count();
            if($retCheck > 0){
                if($newPwd == $confirmPwd){
                    $ret = $this->where("uname='%s' and pwd='%s'", $uname, $oldPwd)->setField('pwd', $newPwd);
                    if($ret !== false){
                        return true;
                    }else{
                        $this->error = "密码修改失败，请稍后再试";
                        return false;
                    }
                }else{
                    $this->error = "两次密码不同";
                    return false;
                }
            }else{
                $this->error = "用户名或密码错误";
                return false;
            }
        }
    }