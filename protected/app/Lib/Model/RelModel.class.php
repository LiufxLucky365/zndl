<?php
    class RelModel extends Model{
        public function updateRel($fid, $tagList){
            $this->startTrans();

            // 删除rel中文件fid的normal类型标签
            $sql = "DELETE
                        r.*
                    from 
                        rel as r, file as f, tag as t
                    where
                        f.id=%d and
                        r.file_id=f.id and
                        r.tag_id=t.id and
                        t.type='normal'
                    ";
            $retRm = $this->query($sql, $fid);
            if($retRm !== false){
                foreach($tagList as $tid){
                    $data['file_id'] = $fid;
                    $data['tag_id'] = $tid;
                    $ret = $this->add($data);
                    if($ret === false){
                        $this->rollback();
                        return false;
                    }
                }
                $this->commit();
                return true;
            }else{
                $this->rollback();
                $this->error = $this->getLastSql();
                return false;
            }
        }
    }