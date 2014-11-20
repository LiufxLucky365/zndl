<?php
    class TagAction extends ParentAction{
        public function index(){
            $tagModel = D("Tag");
            $tagList = $tagModel->getTagList('normal');

            $this->assign('tagList', $tagList);
            $this->display();
        }

        public function create($tagName=null, $fetch=true){
            $tagModel = D("Tag");
            $tagId = $tagModel->insert($tagName);

            if($tagId !== false){
                if($fetch === true){
                    $this->assign('tagId', $tagId);
                    $this->assign('tagName', $tagName);
                    $trContent = $this->fetch('Tag/tagTr');
                    $this->ajaxReturn($trContent, 'succ', 1);
                }else{
                    $this->ajaxReturn($tagId, 'succ', 1);
                }
            }else{
                $this->ajaxReturn(null, $tagModel->getError(), 0);
            }
        }

        public function remove($tagId=null){
            $tagModel = D("Tag");
            $ret = $tagModel->remove($tagId);

            if($ret !== false){
                $this->ajaxReturn($ret, 'succ', 1);
            }else{
                $this->ajaxReturn(null, 'error', 0);
            }
        }
    }