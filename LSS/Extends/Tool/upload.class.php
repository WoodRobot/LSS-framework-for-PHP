<?php
class upload{

    public function uploadFile($File){
        $re = array();
        if($File["file"]["error"]>0)      //传送不成功
        {
            $re["error:"] = $File["file"]["error"];//显示错误的代码。如1代表文件太大等。
        }
        else                               //传送成功显示文件的名字，类型，大小等信息。
        {
            $re["upload"] = $File["file"]["name"]."<br/>";
            $re["type"] = $File["file"]["type"]."<br/>";
            $re["size"] = ($File["file"]["size"]/1024)."KB<br/>";
            $re["storedin"] = $File["file"]["tmp_name"];
            if (file_exists(UPLOAD_PATH . $File["file"]["name"]))   //判断文件夹里有没有同名的文件
            {
                return false;
            }
            else
            {
                move_uploaded_file($File["file"]["tmp_name"],"upload/" . $File["file"]["name"]);
                $re["Storedin "] =  UPLOAD_PATH . $File["file"]["name"];
                return $re;
            }
        }
    }
}