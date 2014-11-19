<?php
    class PadApiAction extends ParentAction{
        /**
         * 各TV播放各自属于themeId的列表
         */
        public function setThemeList($themeId=null, $type='temp'){
            if(!is_numeric($themeId) || !is_string($type)){
                return;
            }

            $fileMod = D("File");
            $ret = $fileMod->setThemeList($themeId, $type);

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileMod->getError(), 0);
            }
        }

        /**
         * 全息，环幕设备插播
         * @param  int $fid  文件id
         * @param  string $type 设备类型qx或hm
         * @return string json
         */
        public function insertFile($fid=null, $type=null){
            if(is_null($fid) || is_null($type) || !in_array($type, array('qx', 'hm'))){
                $this->ajaxReturn(null, '参数错误', 0);
            }
            $fileName = "config_$type.xml";
            if(!file_exists("config/$fileName")){
                $this->ajaxReturn(null, "未找到配置文件: $fileName", 0);
            }

            $fileMod = D("File");
            $file = $fileMod->find($fid);
            if($file !== false || true){
                $root = simplexml_load_file("config/$fileName");
                $i = 0;
                foreach($root as $url){
                    $temp = $root->url[$i];
                    $i++;
                    if($temp == $file['md5']){
                        $locNum = $i;
                        break;
                    }
                }
                if(is_null($locNum)){
                    $this->ajaxReturn(null, "播放列表中无 {$fid} 对应文件", 0);
                }else{
                    $sortNum = str_pad(dechex($locNum), 2, "0", STR_PAD_LEFT);
                    $ret = send_update_notify($type, $errstr, $sortNum);
                    if($ret !== false){
                        $this->ajaxReturn($locNum, 'succ', 1);
                    }else{
                        $this->ajaxReturn(null, $errstr, 0);
                    }
                }
            }else{
                $this->ajaxReturn(null, "未找到文件：$fid", 0);
            }          
        }
    }
