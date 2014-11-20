<?php
    class TestAction extends ParentAction{
        public function test(){
           $this->display();
        }

        public function removeFile($id){
            D("File")->remove($id);
        }

        public function testValite(){
            dump($_SESSION);
        }
    }