<?php
    class UserAction extends ParentAction{
        public function index(){
            $this->display();
        }

        public function update($uname=null, $oldPwd=null, $newPwd=null, $confirmPwd=null){
            $userMod = D("User");
            $ret = $userMod->updatePwd($uname, $oldPwd, $newPwd, $confirmPwd);

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $userMod->getError(), 0);
            }
        }

        public function adminIndex(){
            $userList = M("User")->select();
            $deviceList = M("Tag")->where("type='inner'")->select();
            foreach($userList as &$user){
                $temp = $deviceList;
                foreach($temp as $device){
                    $auth = M("Auth")->where("uid=%d and tid=%d", $user['uid'], $device['id'])->find();
                    $auth = is_array($auth)? true: false;
                    $user['deviceList'][] = array(
                        'id' => $device['id'],
                        'name' => $device['name'],
                        'auth' => $auth,
                    );
                }
            }

            $this->assign('userList', $userList);
            $this->display();
        }

        public function addUserDeviceAuth($uid, $deviceId){
            $ret = M("Auth")->add(array('uid'=>$uid, 'tid'=>$deviceId));

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, M("Auth")->getError(), 0);
            }
        }

        public function removeUserDeviceAuth($uid, $deviceId){
            $ret = M("Auth")->where("uid=%d and tid=%d", $uid, $deviceId)->delete();

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, M("Auth")->getError(), 0);
            }
        }

        public function createUser($uname){
            $ret = M("User")->add(array('uname'=>$uname, 'pwd'=>'123456'));

            U("User/adminIndex", null, null, true);
        }

        public function removeUser($uid){
            $ret = M("User")->where("uid=%d", $uid)->delete();

            if($ret !== false){
                // 清除权限表
                M("Auth")->where("uid=%d", $uid)->delete();
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, M("User")->getError(), 0);
            }
        }
    }