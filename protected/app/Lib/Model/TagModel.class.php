<?php
    class TagModel extends Model{
        public function getTagList($type='all'){
            $condition = array();
            if($type != 'all'){
                $condition['type'] = $type;
            }
            return $this->where($condition)->select();
        }

        public function insert($tagName){
            if(empty($tagName)){
                return false;
            }
            // 检查name是否重复
            $num = $this->where("name='%s'", $tagName)->count();
            if($num > 0){
                $this->error = '标签名称不能重复';
                return false;
            }

            $data['name'] = $tagName;
            return $this->add($data);
        }

        public function remove($tagId){
            $this->startTrans();
            // inner类型的tag不能被删除
            $retRmTag = $this->where("id=%d AND type<>'inner'", $tagId)->delete();
            $retRmRel = M("Rel")->where("tag_id=%d", $tagId)->delete();            
            $ret = ($retRmRel!==false && $retRmTag!==false);

            if($ret == true){
                $this->commit();
            }else{
                $this->rollback();
            }
            return $ret;
        }
    }