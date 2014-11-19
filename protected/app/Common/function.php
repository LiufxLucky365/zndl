<?php
    function send_update_notify($frameType='default', &$errstr, $sort='00', $sys=null, $type='51', $dev='00', $cmd='00'){
        // frame格式(第四位)
        switch ($frameType) {
            case 'temp':
                $sys = "02";
                break;
            case 'update': 
                $sys = "03";
                break;
            case 'time':
                $sys = "04";
                break;
            case 'hm':
            case 'qx':
                if(is_null($sys)){
                    $errstr = '帧信息为空';
                    return false;
                }
		// 51->50  cmd=04 sys=01 hm/ 02 qx
		$type = '50';
		$sys = $frameType=='hm'? '01': '02';
		$cmd = '04';
                break;
            default:
                $sys = "01";
                break;
        }
        $frame = pack("H*", str_replace(' ', '', "A5 $type 06 $sys $dev $cmd 00 $sort 00 5A"));

        $ip = "127.0.0.1";
        $port = 8081;
        return send_udp($ip, $port, $frame, $errstr);
    }

    function send_udp($ip, $port, $frame, &$errstr){
        $dsn = "udp://$ip:$port";
        $socket = stream_socket_client($dsn, $errno, $errstr, 5);
        if($socket!==false && fwrite($socket, $frame)!==false){
            return true;
        }
        Log::record("socket err[$errno]: " . $errstr);
        $errstr = '文件同步失败，检查socket是否正确连接';
        return false;
    }

    function log_upload($fileInfo, $destination="log/upload_log.txt"){
        if(empty($fileInfo) || !is_array($fileInfo)){
            return false;
        }
        $upState = $fileInfo['code']==1? 'succ': 'fail';
        $level = $fileInfo['code']==1? 'INFO': 'ERR';
        $message = "$upState {$fileInfo['status']} {$fileInfo['fileName']} {$fileInfo['fileSize']}";
        return Log::write($message, $level, 3, $destination);
    }
