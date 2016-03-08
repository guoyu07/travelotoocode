<?php
header ( 'Content-type:text/html;charset=utf-8' );
require  dirname(dirname(dirname(__FILE__))).'/include/common.inc.php';

include_once $_SERVER ['DOCUMENT_ROOT'] . '/thirdpay/yinlian/func/common.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/thirdpay/yinlian/func/SDKConfig.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/thirdpay/yinlian/func/secureUtil.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/thirdpay/yinlian/func/log.class.php';
/**
 * ���ѽ���-ǰ̨ 
 */


/**
 *	���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���Ҫ�����ռ����ĵ���д���ô�������ο�
 */
// ��ʼ����־
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "============����ǰ̨����ʼ===============" );
// ��ʼ����־
$params = array(
    'version' => '5.0.0',				//�汾��
    'encoding' => 'utf-8',				//���뷽ʽ
    'certId' => getSignCertId (),			//֤��ID
    'txnType' => '01',				//��������
    'txnSubType' => '01',				//��������
    'bizType' => '000201',				//ҵ������
    'frontUrl' => empty($_POST['showurl'])?$GLOBALS['cfg_basehost']:$_POST['showurl'],  		//ǰ̨֪ͨ��ַ
    'backUrl' => $GLOBALS['cfg_basehost'].'/thirdpay/yinlian/back_notify.php',		//��̨֪ͨ��ַ
    'signMethod' => '01',		//ǩ������
    'channelType' => '07',		//�������ͣ�07-PC��08-�ֻ�
    'accessType' => '0',		//��������
    'merId' => $GLOBALS['cfg_yinlian_new_name'],		        //�̻����룬����Լ��Ĳ����̻���
    'orderId' =>  $_POST['ordersn'],	//�̻�������
    'txnTime' => date('YmdHis'),	//��������ʱ��
    'txnAmt' => $_POST['price']*100,		//���׽���λ��
    'currencyCode' => '156',	//���ױ���
    'defaultPayType' => '0001',	//Ĭ��֧����ʽ
    //'orderDesc' => '��������',  //��������������֧����wap֧����ʱ��������
    'reqReserved' =>'yes', //���󷽱�����͸���ֶΣ���ѯ��֪ͨ�������ļ��о���ԭ������
);


// ǩ��
sign ( $params );


// ǰ̨�����ַ
$front_uri = SDK_FRONT_TRANS_URL;
$log->LogInfo ( "ǰ̨�����ַΪ>" . $front_uri );
// ���� �Զ��ύ�ı�
$html_form = create_html ( $params, $front_uri );

$log->LogInfo ( "-------ǰ̨�����Զ��ύ��>--begin----" );
$log->LogInfo ( $html_form );
$log->LogInfo ( "-------ǰ̨�����Զ��ύ��>--end-------" );
$log->LogInfo ( "============����ǰ̨���� ����===========" );
echo $html_form;
