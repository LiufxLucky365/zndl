<?php
    class UploadAction extends ParentAction{
        private $_uploadPath = "";

        public function __construct(){
            parent::__construct();
            $this->_uploadPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/";
        }

        public function fileUpload(){
            $ret = $this->_fileUpload();
            log_upload($ret);   // 记录日志
            $this->ajaxReturn($ret);
        }

        private function _fileUpload(){
            if(!empty($_FILES) && $_FILES["file"]["error"] == 0){
                // 文件基本信息
                $fileTmpName = $_FILES['file']['tmp_name'];
                $fileSize = $_FILES['file']['size'];
                $showName = $_FILES['file']['name'];
                if($_FILES["file"]["size"] > 1024 * 1024 * 100){
                    return array("code"=>0, "status"=>"file max size limit 100M" ,"hash"=>"", "url"=>"", "fileName"=>$showName, "fileSize"=>$fileSize);
                }
                // 文件拓展信息
                $pathInfo = pathinfo($showName);
                $ext = $pathInfo['extension'];
                $filename = $pathInfo['filename'];
                $md5 = md5_file($fileTmpName);

                $fileModel = D('File');
                // 检查文件的show_name是否有重复，重复则返回失败
                if($fileModel->showNameRepeat($showName)){
                    return array("code"=>0, "status"=>"该文件已存在" ,"hash"=>"", "url"=>"", "fileName"=>$showName, "fileSize"=>$fileSize);
                }
                // 处理文件，若已经有同md5文件则不移动
                $retMove = true;
                if(!($fileModel->isExists($md5))){
                    $destFilePath = $this->_uploadPath . $md5 . '.' . $ext;
                    $retMove = move_uploaded_file($fileTmpName, $destFilePath);
                    chmod($destFilePath, 0777);
                }
                // 入库
                if($retMove !== false){
                    $tagInnerList = $this->_param('deviceIdList');
                    $tagNormalList = $this->_param('tagIdList');
                    $tagInnerList = is_null($tagInnerList)? array(): $tagInnerList;
                    $tagNormalList = is_null($tagNormalList)? array(): $tagNormalList;
                    $tagList = array_merge($tagNormalList, $tagInnerList);
                    $retInsert = $fileModel->insert($showName, $md5, $ext, $fileSize, $tagList);
                    if($retInsert === false){
                        return array("code"=>0, "status"=>"数据插入失败" ,"hash"=>"", "url"=>"", "fileName"=>$showName, "fileSize"=>$fileSize);
                    }
                }else{
                    return array("code"=>0, "status"=>"文件保存失败" ,"hash"=>"", "url"=>"", "fileName"=>$showName, "fileSize"=>$fileSize);
                }
                // 成功
                if($retMove!==false && $retInsert!==false){
                    return array("code"=>1, "status"=>"ok" ,"hash"=>"", "url"=>"", "fileName"=>$showName, "fileSize"=>$fileSize);
                }
            }else{
                return array("code"=>0, "status"=>"文件丢失" ,"hash"=>"", "url"=>"");
            }
        }
    }