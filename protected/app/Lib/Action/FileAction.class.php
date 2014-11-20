<?php
    class FileAction extends ParentAction{
        public function index($fetch=0){
            $model = D("File");
            $deviceList = D("Tag")->getTagList('inner');
            $tagList = D("Tag")->getTagList('normal');

            if($fetch == 1){
                // 需要渲染
                $this->assign('tagInnerList', $deviceList);
                $this->assign('tagNormalList', $tagList);
                $this->display();
            }else{
                // 不需要渲染，返回json
                if($deviceList!==false && $tagList!==false){
                    $ret['deviceList'] = $deviceList;
                    $ret['tagList'] = $tagList;
                    $this->ajaxReturn($ret, 'succ', 1);
                }else{
                    $this->ajaxReturn(null, $model->getError(), 0);
                }
            }
        }

        /**
         * ajax方式获取文件列表
         */
        public function getFileList($deviceIdList=array(), $tagIdList=array(), $fetch=0){
            // ios端传来为字符串
            if(is_string($deviceIdList)){
                $deviceIdList = explode('-', $deviceIdList);
            }
            if(is_string($tagIdList)){
                $tagIdList = explode('-', $tagIdList);
            }

            $model = D("File");
            $fileList = $model->getFileList($deviceIdList, $tagIdList);

            if($fileList !== false){
                if($fetch != 0){
                    $this->assign('fileList', $fileList);
                    $content = $this->fetch('File/fileList');
                    $this->ajaxReturn($content, 'succ', 1);
                }else{
                    $this->ajaxReturn($fileList, 'succ', 1);
                }
            }else{
                $this->ajaxReturn(null, $model->getError(), 0);
            }
        }

        /**
         * 更新file_map
         */
        public function updateConfig(){
            $fileModel = D("File");

            $ret = $fileModel->updateFileMap();
            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileModel->getError(), 0);
            }
        }

        public function setPlayList($fidList=null, $deviceIdList=null, $type='temp'){
            if(is_null($deviceIdList) || is_null($type)){
                return;
            }
            // ios端传来为字符串
            if(is_string($fidList)){
                $fidList = explode('-', $fidList);
            }
            if(is_string($deviceIdList)){
                $deviceIdList = explode('-', $deviceIdList);
            }

            $fileModel = D("File");
            $ret = $fileModel->genConfigXml($fidList, $deviceIdList, $type);

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileModel->getError(), 0);
            }
        }

        public function remove($fid=null){
            if(is_null($fid)){
                return;
            }
            $fileModel = D("File");
            $ret = $fileModel->remove($fid);

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileModel->getError(), 0);
            }
        }

        public function setTvPlayTime($deviceIdList=null, $autoPlayTime=null, $manuToAutoTime=null){
            if(is_null($deviceIdList) || is_null($autoPlayTime) || is_null($manuToAutoTime)){
                return;
            }
            // ios端传来为字符串
            if(is_string($deviceIdList)){
                $deviceIdList = explode('-', $deviceIdList);
            }
            
            $fileModel = D("File");
            $ret = $fileModel->setTvPlayTime($deviceIdList, $autoPlayTime, $manuToAutoTime);
            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileModel->getError(), 0);
            }
        }

        public function tagForm($fid=null){
            if(is_null($fid)){
                return;
            }
            $tagModel = D("Tag");

            $tagInnerList = $tagModel->getTagList('inner');
            $tagNormalList = $tagModel->getTagList('normal');
            // 设置默认选择的tag
            $fileTagList = M("Rel")->where("file_id=%d", $fid)->select();
            $tagList = array();
            foreach($fileTagList as $file){
                $tagList[] = $file['tag_id'];
            }
            foreach($tagInnerList as &$t){
                if(in_array($t['id'], $tagList)){
                    $t['selected'] = true;
                }
            }
            foreach($tagNormalList as &$t){
                if(in_array($t['id'], $tagList)){
                    $t['selected'] = true;
                }
            }

            $this->assign('file_id', $fid);
            $this->assign('tagInnerList', $tagInnerList);
            $this->assign('tagNormalList', $tagNormalList);
            $this->display();
        }

        public function updateFileRel($fid=null, $tagIdList=array()){
            if(!is_array($tagIdList) || is_null($fid)){
                $this->error('请检查参数');
                return;
            }
            $relModel = D("Rel");
            $ret = $relModel->updateRel($fid, $tagIdList);
            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $relModel->getError(), 0);
            }
        }

        public function updateFile($fid=null, $fileName=null){
            if(is_null($fid) || is_null($fileName)){
                return;
            }

            $fileModel = D("File");
            $ret = $fileModel->updateFile($fid, $fileName);
            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileModel->getError(), 0);
            }
        }

        public function sortIndex(){
            $fileModel = D("File");
            $fileList = $fileModel->order('sort, show_name')->select();

            if($fileList !== false){
                $this->assign('fileList', $fileList);
                $this->display();
            }else{
                $this->error($fileModel->getLastError());
            }
        }

        public function sort($fidList=null){
            $fileModel = D("File");
            $ret = $fileModel->sort($fidList);

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, $fileModel->getError(), 0);
            }
        }
    }