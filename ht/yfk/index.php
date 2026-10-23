<?php
include_once dirname(__FILE__).'/../../includes/Init.php';
include_once PHPDISK_ROOT.'includes/top.php';
include_once dirname(__FILE__).'/pay_common.php';
if (!isset($top)) { $top = ''; }
$uid = 2;
$sid = 1;
// ===== 充值方式配置读取：pay_cfg() 在 pay_common.php（batch12 抽出，cashier/notify/yhzz 共享）=====
$pay_cfg = array(
	'alipay' => array('on'=>pay_cfg($db,'pay_alipay'),'key'=>pay_cfg($db,'pay_alipay_key')),
	'tenpay' => array('on'=>pay_cfg($db,'pay_tenpay'),'key'=>pay_cfg($db,'pay_tenpay_key')),
	'wx'     => array('on'=>pay_cfg($db,'pay_wx'),'key'=>pay_cfg($db,'pay_wx_key')),
	'zx'     => array('on'=>pay_cfg($db,'pay_zx'),'key'=>pay_cfg($db,'pay_zx_key')),
	'yhzz'   => array('on'=>pay_cfg($db,'pay_yhzz'),'bank'=>pay_cfg($db,'pay_yhzz_bank'),'account'=>pay_cfg($db,'pay_yhzz_account'),'name'=>pay_cfg($db,'pay_yhzz_name')),
);
$pay_show = array(
	'alipay' => ($pay_cfg['alipay']['on']=='1' && $pay_cfg['alipay']['key']!=''),
	'tenpay' => ($pay_cfg['tenpay']['on']=='1' && $pay_cfg['tenpay']['key']!=''),
	'wx'     => ($pay_cfg['wx']['on']=='1'     && $pay_cfg['wx']['key']!=''),
	'zx'     => ($pay_cfg['zx']['on']=='1'     && $pay_cfg['zx']['key']!=''),
	'yhzz'   => ($pay_cfg['yhzz']['on']=='1'   && $pay_cfg['yhzz']['account']!=''),
);
?>
<style type="text/css">
	/*ul.mllb			{margin:0;width:100%;}
	ul.mllb li		{float:left;list-style-type:none;margin:3px 6px 0 0;font-size:9pt;line-height:120%;}
	ul.mllb a		{font-weight:bold;font-size:9pt;color:#000;padding:5px 3px 3px 3px;height:20px;background-color:#E1E1FF;border:1px solid #000;text-align:center;TEXT-DECORATION: none;}
	ul.mllb a:hover	{color:#490685;background-color:#FFEEE1;border:1px solid #1706FF;}
	ul.mllb a:visited {color:#000;}*/
        .kk img {border:0;}
        .kk {display:block;   }
        .kk a {TEXT-DECORATION: none; color:#000;       }
        .kk1:hover {border:1px solid #1706FF;}
        /* 充值方式 div 卡片布局 */
        .pay-grid{display:flex;flex-wrap:wrap;gap:16px;margin:6px 0 0}
        .pay-item{display:flex;flex-direction:column;align-items:center;text-decoration:none;color:#374151;border:1px solid #e5e7eb;border-radius:10px;padding:14px 18px;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.05);transition:all .15s;min-width:140px;font-size:14px;line-height:1.5}
        .pay-item:hover{border-color:#2563eb;color:#2563eb;box-shadow:0 2px 8px rgba(37,99,235,.12);transform:translateY(-2px)}
        .pay-item span{font-weight:600;margin-top:6px}
        .pay-item small{color:#6b7280;font-size:12px;margin-top:4px;line-height:1.6}
</style>
<div id="ysright">
            <form name="ctl00" method="post" action="" id="ctl00">
            <h1>
            <span id="yhztxs"><?php echo $db -> LoginBar(); if ($_SESSION['username_epan'] != NULL) { echo $top ;}?></span>
            <img border="0" src="/ht/images/money.gif" width="32" height="32" alt="" />账户充值</h1>
            <h3>账户余额</h3>
            <div class="ysdb2">
                <table border="1" width="100%">
	                <tr>
		                <td class="tdbt" width="107">您的账户余额</td>
		                <td>
		                    <b><u><font color="#0000FF" size="4"><span id="idyfk"></span></font></u></b><font size="3">&nbsp;元</font>
		                    <input type="submit" name="bucx" value="刷新" id="bucx" disabled="disabled" />
		    　          </td>
	                </tr>
                </table>
            </div>
            <ul class="yslb2">
                <li>账户余额是为方便您在<?php echo Website_title; ?>里消费而设立的。</li>
                <li>您可以通过以下几种方式向您的账户充值。冲值和消费的记录都可以查看。</li>
            </ul>
            <h3>充值方式</h3>

             <!-- 无标注和可疑空间充值提示 -->
            <div style='border:2px solid red;padding:5px; width:420px;background-color:#E4F3FC;'><font color='blue' size='2pt'>网盘不允许存储传播任何有关反动、色情、游戏外挂、辅助、私服、黑客软件、木马、病毒、软件破解或者网站破解等违反中国法律法规的内容。如果发现或被举报并查实，将关闭违规空间，充值费用不予退回。</font></div>



                <div class="pay-grid">
                    <?php if($pay_show['alipay']){ ?>
                    <a class="pay-item" href="/ht/yfk/cashier.php?pm=alipay" title="使用支付宝进行支付,系统自动充值">
                        <img alt="" src="/ht/images/cz1.gif" width="120" height="34" />
                        <span>支付宝支付</span>
                    </a>
                    <?php } ?>
                    <?php if($pay_show['tenpay']){ ?>
                    <a class="pay-item" href="/ht/yfk/cashier.php?pm=tenpay" title="使用财付通进行支付,系统自动充值">
                        <img alt="" src="/ht/images/cz2.gif" width="120" height="34" />
                        <span>财付通支付</span>
                    </a>
                    <?php } ?>
                    <?php if($pay_show['wx']){ ?>
                    <a class="pay-item" href="/ht/yfk/cashier.php?pm=wx" title="使用微信扫码进行支付,系统自动充值">
                        <img alt="" src="/ht/images/cz5.gif" width="120" height="34" />
                        <span>微信支付</span>
                    </a>
                    <?php } ?>
                    <?php if($pay_show['zx']){ ?>
                    <a class="pay-item" href="/ht/yfk/cashier.php?pm=zx" title="使用银行卡在线支付,系统自动充值">
                        <img alt="" src="/ht/images/cz3.gif" width="120" height="34" />
                        <span>网银在线支付</span>
                    </a>
                    <?php } ?>
                    <?php if($pay_show['yhzz']){ ?>
                    <a class="pay-item pay-item-bank" href="index.php?method=yhzz" title="通过银行卡账号转账，需经客服确认才能完成充值">
                        <img alt="" src="/ht/images/cz4.gif" width="120" height="34" />
                        <span>银行汇款或转账</span>
                        <small>开户行：<?php echo htmlspecialchars($pay_cfg['yhzz']['bank']); ?><br/>账号：<?php echo htmlspecialchars($pay_cfg['yhzz']['account']); ?><br/>户名：<?php echo htmlspecialchars($pay_cfg['yhzz']['name']); ?></small>
                    </a>
                    <?php } ?>
                    <?php if(!array_filter($pay_show)){ ?>
                    <p style="color:#888;font-size:13px;width:100%;">暂未开通在线充值方式，如有需要请联系管理员。</p>
                    <?php } ?>
                </div>
                                        

            <br /> 
            <p>
                <img src="/images/go.GIF" width="16" height="16" alt="" /><a href="index.php?method=jlcz">充值记录</a>
                <img src="/images/go.GIF" width="16" height="16" alt="" /><a href="index.php?method=jlxf">消费记录</a>
            </p>
            <p>
                <b>账户余额可用于：</b>
                <a href="index.php?method=kjsj">空间升级</a>
                <a href="index.php?method=kjyq">空间延期</a>
            </p>
            </form>
        </div>
