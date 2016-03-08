<?php defined('SYSPATH') or die('No direct script access.');
class Controller_SystemParts extends Stourweb_Controller
{
    /**
     * 图片首页
     */
    public function action_index()
    {
        $this->assign('parentkey', $this->params['parentkey']);
        $this->assign('itemid', $this->params['itemid']);
        $this->parentkey = $this->params['parentkey'];
        $this->itemid = $this->params['itemid'];

        $this->assign('pcVersionList', Model_SystemParts::getSystemPart(Model_SystemParts::$pcSystemPartCode));
        $this->assign('mobileVersionList',Model_SystemParts::getSystemPart(Model_SystemParts::$mobileSystemPartCode));

        $configinfo = ORM::factory('sysconfig')->getConfig(0);
        if (!isset($configinfo['cfg_mobile_version']))
        {
            $configinfo['cfg_mobile_version'] = 0;
        }
        if (!isset($configinfo['cfg_pc_version']))
        {
            $configinfo['cfg_pc_version'] = 0;
        }
        if (!isset($configinfo['cfg_pc_upgrade']))
        {
            $configinfo['cfg_pc_upgrade'] = 0;
        }
        if (!isset($configinfo['cfg_mobile_upgrade']))
        {
            $configinfo['cfg_mobile_upgrade'] = 0;
        }
        $this->assign('config', $configinfo);

        $this->display('stourtravel/systemparts/version_manage');
    }

    public function action_ajax_further_processing()
    {
        $out = array('status'=>true);

        $sysconfiginfo = ORM::factory('sysconfig')->getConfig(0);

        //检测手机域名是否更改
        if (isset($sysconfiginfo['cfg_m_main_url']))
        {
            //写入mobile 配置
            $file = BASEPATH . '/data/mobile.php';
            $config = require($file);
            $config['domain']['mobile'] = $sysconfiginfo['cfg_m_main_url'];
            $config['domain']['main'] = 'http://' . $_SERVER['HTTP_HOST'];
            //重写伪静态
            $htFile = BASEPATH . '/.htaccess';
            $content = file_get_contents($htFile);
            if ($sysconfiginfo['cfg_mobile_version'] < 1 || $config['domain']['mobile'] == $config['domain']['main'])
            {
                $config['domain']['mobile'] = $config['domain']['main'];
                $content = preg_replace("`({$config['delimiterLeft']}).*({$config['delimiterRight']})`is", '$1' . "\r\n" . '$2', $content);
            }
            else
            {
                $replace = $config['delimiterLeft'];
                $replace .= str_replace(array('{PHP_EOL}', '{host}', '{path}'), array("\r\n", parse_url($config['domain']['mobile'], PHP_URL_HOST), rtrim($config['version'][$sysconfiginfo['cfg_mobile_version']]['path'], '/')), $config['rules']);
                $replace .= $config['delimiterRight'];
                if (preg_match("~" . $config['delimiterLeft'] . '.*' . $config['delimiterRight'] . '~is', $content))
                {
                    $content = preg_replace("~" . $config['delimiterLeft'] . '.*' . $config['delimiterRight'] . '~is',str_replace('$1', '\$1',$replace), $content);
                }
                else
                {
                    $replace='RewriteBase /'."\r\n".$replace."\r\n";
                    $content = str_replace('RewriteBase /', $replace, $content);
                }
            }
            file_put_contents($htFile, $content);
            file_put_contents($file, '<?php ' . "\r\n" . 'return ' . var_export($config, true) . ';');
        }

        echo json_encode($out);
    }
//end
}