<?php
    class IndexAction extends ParentAction{
        public function index(){
            $tagModel = D("Tag");

            $tagInnerList = $tagModel->getTagList('inner');
            $tagNormalList = $tagModel->getTagList('normal');

            $this->assign('tagInnerList', $tagInnerList);
            $this->assign('tagNormalList', $tagNormalList);
            $this->display();
        }
    }