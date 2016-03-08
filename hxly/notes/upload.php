<?php
/**
 * 游记图片上传
 */
$res = array('code' => 400, 'msg' => '');
$path = dirname(dirname(__FILE__)) . '/uploads/notes/' . date('Ymd');
list($name, $type, $temp, $error) = array_values($_FILES['upload']);
$typeArr = array(
    'image/jpeg' => '.jpeg',
    'image/png' => '.png',
    'image/gif' => '.gif'
);
//上传错误
if($error != 0)
{
    $res['msg'] = '图片上传错误';
    exit("<textarea>" . json_encode($res) . "</textarea>");
}
//图片格式
if(!array_key_exists($type, $typeArr))
{
    $res['msg'] = '图片上传格式不正确';
    $res['type'] = $type;
    exit("<textarea>" . json_encode($res) . "</textarea>");
}
//图片移动
if(!file_exists($path))
{
    mkdir($path, 0777, true);
}
$file = $path . '/' . md5($name) . $typeArr[$type];
if(!move_uploaded_file($temp, $file))
{
    $res['msg'] = '图片上传失败';
    exit("<textarea>" . json_encode($res) . "</textarea>");
}
$res['code'] = 200;
$res['src'] = strstr($file, '/uploads');
$res['coord'] = $_GET['coord'];
exit("<textarea>" . json_encode($res) . "</textarea>");