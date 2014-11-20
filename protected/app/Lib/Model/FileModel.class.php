<?php
    class FileModel extends Model{
        public function isExists($md5){
            $condition['md5'] = $md5;
            $ret = $this->where($condition)->count();
            return $ret==0? false: true;
        }

        public function showNameRepeat($showName){
            $condition['show_name'] = $showName;
            $ret = $this->where($condition)->count();
            return $ret==0? false: true;
        }

        public function insert($showName, $md5, $ext, $fileSize, $tagList){
            $data['show_name'] = $showName;
            $data['md5'] = "$md5.$ext";
            $data['ext'] = $ext;
            $data['size'] = $fileSize;
            $data['insert_date'] = date("Y-m-d H:i:s");
            // file sort值为当前最大+1，若没有记录则为0
            $sortMax = $this->max('sort');
            $data['sort'] = is_null($sortMax)? 0: $sortMax+1;

            $this->startTrans();
            $fid = $this->add($data);
            // 保存文件的分类
            if($fid !== false){
                $relModel = M("Rel");
                $rel['file_id'] = $fid;
                foreach($tagList as $tagId){
                    $rel['tag_id'] = $tagId;
                    if($relModel->add($rel) === false){
                        $this->rollback();
                        return false;
                    }
                }
            }
            $this->commit();

            return $fid;
        }

        public function getFileList($deviceIdList, $tagIdList){
            if(!is_array($deviceIdList)||empty($deviceIdList)){
                $this->error = '未选择设备';
                return null;
            }

            $sql = "SELECT 
                        f.id as file_id, 
                        f.show_name as show_name,
                        f.md5 as md5,
                        f.ext as ext,
                        f.insert_date as insert_date,
                        group_concat(t.id) as tag_all_id,
                        group_concat(t.name) as tag_all_name
                    from 
                        rel as r, tag as t, file as f
                    where 
                        r.file_id=f.id and 
                        r.tag_id=t.id and
                        f.id in
                            (select f.id from file as f,rel as r where f.id=r.file_id and r.tag_id in (%s))
                    group by f.id
                    order by f.sort
                    ";
            $fileList = $this->query($sql, join(',', $deviceIdList));
            if($fileList !== false){
                // 整理tag的id-name对
                foreach($fileList as $k=>$f){
                    $tagAllIdList = explode(',', $f['tag_all_id']);
                    $tagAllNameList = explode(',', $f['tag_all_name']);
                    // 过滤标签：在过滤标签(tagIdList)中，但不在目标所具有的标签的数组若不为空则过滤掉
                    $diffArr = array_diff($tagIdList, $tagAllIdList);
                    if(!empty($diffArr)){
                        unset($fileList[$k]);
                        continue;
                    }
                    $tagList = array_combine($tagAllIdList, $tagAllNameList);
                    asort($tagList);
                    $fileList[$k]['tag_list'] = $tagList;
                }
            }
            return $fileList;
        }

        public function remove($id){
            $file = $this->find($id);
            $ret = $this->where("id=%d", $id)->delete();

            // 检查是否需要删除源文件
            if($ret !== false){
                $num = $this->where("md5='%s'", $file['md5'])->count();
                if($num == 0){
                    unlink("upload/".$file['md5']);
                }
                $ret = $this->updateFileMap();
            }
            return $ret;
        }

        public function genConfigXml($fidList, $deviceIdList, $type){
            if(empty($deviceIdList) || empty($type)){
                $this->error = "未选择设备或者参数出错";
                return false;
            }
            if(!$this->checkDeviceAuth($deviceIdList)){
                return false;
            }

            if(empty($fidList)){
                // 意为清空对应设备列表
                $fileList = array();
            }else{
                $sql = "SELECT
                            distinct f.id,
                            f.md5
                        from 
                            file as f, tag as t, rel as r
                        where
                            r.file_id=f.id and
                            r.tag_id=t.id and
                            f.id in (%s) and
                            t.type='inner'
                        order by 
                            find_in_set(f.id, '%s') 
                        ";
                $fileList = $this->query($sql, join(',', $fidList), join(',', $fidList));
            }

            if($fileList !== false){
                $deviceList = array();
                foreach($deviceIdList as $deviceId){
                    $tag = M("Tag")->find($deviceId);
                    if($tag['class']!='tv' && in_array($type, array('welcome', 'default', 'temp'))){
                        $this->error = '警告：非TV类设备无法设置config_TV_{$type}.xml';
                        return false;
                    }
                    $deviceList[] = $tag;
                }
                // type==temp时重新生成config_TV_temp.xml
                if($type=='temp' && file_exists("config/config_TV_temp.xml")){
                    unlink("config/config_TV_temp.xml");
                }

                foreach($deviceList as $tag){
                    switch($tag['class']){
                        case 'qx':
                        case 'hm':
                            // 更新xml
                            $string = "<?xml version='1.0' encoding='utf-8'?><config></config>";
                            $root = simplexml_load_string($string);
                            foreach($fileList as $f){
                                $root->addChild('url', $f['md5']);
                            }
                            $ret = $root->saveXML("config/config_{$tag['class']}.xml");
                            $type = ''; // qx hm时静默更新
                            break;
                        case 'tv':
                            /**
                             * 处理逻辑
                             * 1. update, default, temp的区别只是保存的文件不同
                             * 2. 先读取config_TV_update/default/temp.xml文件
                             * 3. 存在则修改对应节点值
                             * 4. 不存在则重新创建
                             */
                            $fileName = "config_TV_{$type}.xml";
                            if(file_exists("config/$fileName")){
                                $root = simplexml_load_file("config/$fileName");
                                // 删除原TV_x节点
                                $i = 0;
                                foreach($root as $tmp){
                                    $attr = $tmp->attributes();
                                    if($attr['name'] == $tag['name']){  
                                        unset($root->TV[$i]);
                                    }  
                                    $i++;  
                                }
                            }else{
                                $string = "<?xml version='1.0' encoding='utf-8'?><root></root>";
                                $root = simplexml_load_string($string);
                            }
                            // 创建TV节点
                            $tv = $root->addChild('TV');
                            $tv->addAttribute('name', $tag['name']);
                            $config = $tv->addChild('config');
                            foreach($fileList as $f){
                                $config->addChild('url', $f['md5']);
                            }
                            $ret = $root->saveXML("config/$fileName");
                            break;
                    }
                }
            }else{
                $this->error = "查询结果出错: {$this->getLastSql()}";
                $ret = false;
            }
            // 发送UDP
            $retSocket = send_update_notify($type, $errstr);
            if($retSocket === false){
                $this->error = $errstr;
                $ret = false;
            }
            return $ret;
        }

        public function updateFileMap(){
            $fileList = M("File")->field('id, show_name, md5 as true_name, ext')->order('sort')->select();
            if($fileList !== false){
                $string = "<?xml version='1.0' encoding='utf-8'?><root></root>";
                $root = simplexml_load_string($string);

                foreach($fileList as $file){
                    $fileNode = $root->addChild('file');
                    $fileNode->addChild('id', $file['id']);
                    $fileNode->addChild('ext', $file['ext']);
                    $fileNode->addChild('show_name', $file['show_name']);
                    $fileNode->addChild('true_name', $file['true_name']);
                }

                $ret = $root->saveXML("config/file_map.xml");
            }else{
                $ret = false;
            }
            // 发送UDP
            send_update_notify();
            $retSocket = send_update_notify('default', $errstr);
            if($retSocket === false){
                $this->error = $errstr;
                return false;
            }
            return $ret;
        }

        public function updateFile($fid, $fileName){
            // 检查文件名是否重复
            if($this->where("show_name='%s'", $fileName)->count() > 0){
                $this->error = '文件名已存在';
                return false;
            }
            $this->where("id=%d", $fid)->setField('show_name', $fileName);
            return $this->updateFileMap();;
        }

        public function setTvPlayTime($deviceIdList, $autoPlayTime, $manuToAutoTime){
            if(!$this->checkDeviceAuth($deviceIdList)){
                return false;
            }

            $tagNameList = array();
            foreach($deviceIdList as $devId){
                $tag = M("Tag")->find($devId);
                if($tag['class'] != 'tv'){
                    $this->error = '只有TV类设备可以设置播放时间，请重新选择设备';
                    return false;
                }
                $tagNameList[] = $tag['name'];
            }
            /**
             * 处理逻辑
             * 1. 先读取config_play_time.xml文件
             * 3. 存在则修改对应节点值
             * 4. 不存在则重新创建
             */
            $fileName = "config_play_time.xml";
            if(file_exists("config/$fileName")){
                $root = simplexml_load_file("config/$fileName");
                // 删除原TV_x节点
                $childNum = $root->count();
                for($i=$childNum-1; $i>=0; $i--){
                    $attr = $root->TV[$i]->attributes();
                    if(in_array($attr['name'], $tagNameList)){
                        unset($root->TV[$i]);
                    }
                }
            }else{
                $string = "<?xml version='1.0' encoding='utf-8'?><config></config>";
                $root = simplexml_load_string($string);
            }
            // 创建TV节点
            foreach($tagNameList as $tagName){
                $tv = $root->addChild('TV');
                $tv->addAttribute('name', $tagName);
                $tv->addChild('AutoPlayTime', $autoPlayTime);
                $tv->addChild('ManuToAutoTime', $manuToAutoTime);
            }
            $ret = $root->saveXML("config/$fileName");
            if($ret === false){
                $this->error = "xml文件保存失败";
                return false;
            }
            // 发送UDP
            $retSocket = send_update_notify('time', $errstr);
            if($retSocket === false){
                $this->error = $errstr;
                return false;
            }
        }

        public function sort($fidList){
            if(!is_array($fidList) || empty($fidList)){
                return false;
            }
            // 检查fidList与数据库中file数目是否吻合
            $fileCount = $this->count();
            if($fileCount != count($fidList)){
                $this->error = "尚有文件未选择";
                return false;
            }
            // insert into file (id, sort) values (1, 2), (4,2) on duplicate key update sort=values(sort);
            $valList = array();
            foreach($fidList as $sort => $fid){
                $valList[] = "($fid, $sort)";
            }
            $valList = join(',', $valList);
            $sql = "INSERT into file (id, sort) values $valList on duplicate key update sort=values(sort)";
            return $this->query($sql);
        }

        /* 所有TV播放自己某主题(标签的一种)下的文件 */
        public function setThemeList($themeId, $type='temp'){
            if(!is_numeric($themeId)){
                $this->error = "wrong arg type";
                return false;
            }
            if(!$this->checkDeviceAuth()){
                return false;
            }

            $fileName = "config/config_TV_{$type}.xml";
            $string = "<?xml version='1.0' encoding='utf-8'?><root></root>";
            $root = simplexml_load_string($string);

            $tvTagList = M('Tag')->where("class='tv'")->select();
            foreach($tvTagList as $tag){
                $sql = "SELECT
                            f.md5
                        from 
                            rel as r, file as f
                        where 
                            r.file_id=f.id and
                            file_id in (select file_id from rel where tag_id=%d) and
                            r.tag_id=%d
                        ";
                $themeFileList = $this->query($sql, $tag['id'], $themeId);
                if(!empty($themeFileList)){
                    $tv = $root->addChild('TV');
                    $tv->addAttribute('name', $tag['name']);
                    $config = $tv->addChild('config');
                    foreach($themeFileList as $f){
                        $config->addChild('url', $f['md5']);
                    }
                }
            }
            $ret =  $root->asXml($fileName);
            if($ret === false){
                $this->error = "xml文件保存失败";
                return false;
            }
            // 发送UDP
            $retSocket = send_update_notify($type, $errstr);
            if($retSocket === false){
                $this->error = $errstr;
                return false;
            }
        }

        public function checkDeviceAuth($deviceIdList=null){
            // Pad端 不限制权限
            $token = I('token');
            if($token == 'WVdOMGJIcHVaR3clM0Q'){
                return true;
            }

            $uid = $_SESSION['uid'];
            if(empty($uid)){
                $this->error = 'uid获取失败';
                return false;
            }

            if(is_null($deviceIdList)){
                // 检查全部TV权限
                $deviceList = M("Tag")->where("class='tv'")->select();
                foreach($deviceList as $device){
                    $deviceIdList[] = $device['id'];
                }
            }

            $deviceIdAuthList = array();
            $deviceAuthList = M("Auth")->where("uid=%d", $uid)->select();
            foreach($deviceAuthList as $device){
                $deviceIdAuthList[] = $device['tid'];
            }

            foreach($deviceIdList as $id){
                if(!in_array($id, $deviceIdAuthList)){
                    $this->error = '权限不足';
                    return false;
                }
            }
            return true;
        }
    }