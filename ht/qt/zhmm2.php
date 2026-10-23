<?php
$uid = 4;
$sid = 7;
?>
<div id="ysright">
    <h1><img border="0" src="/ht/images/Tools.gif" width="32" height="32" alt="" />重置密码</h1>
    <div class="ysdb2">
        <form name="ctl00" method="post" action="?method=zhmm2" id="ctl00">
            <h3>联系客服电话</h3>
                <ul class="yslb2">
                    <li>可以通过电话联系我们：<?php echo $db -> Info('telphone'); ?></li>
                </ul>
            
            <h3>在线提交</h3>
                <ul class="yslb2">
                    <li>请输入您的用户名：<br />
                        &nbsp;&nbsp;&nbsp;&nbsp;<input name="tedlmc" type="text" id="tedlmc" />
                    </li>
                    
                    <li style="margin-top:15px;">请输入您的联系QQ：<br />
                        &nbsp;&nbsp;&nbsp;&nbsp;<input name="teqq" type="text" id="teqq" />
                    </li>
                    <li  style="margin-top:15px;">请写明您是这个空间管理员的依据：<br />
                        
                        
                        &nbsp;&nbsp;&nbsp;&nbsp;<textarea name="tenr" id="tenr" rows="2" cols="20" style="height:120px;width:380px;"></textarea>
                    </li>
                </ul>

                <p>&nbsp;&nbsp;&nbsp;&nbsp;<font color="blue">上面请填写注册时间、注册地点、注册时的邮箱、以前填写的个人资料（地址、电话、姓名、身份证号码等）、充值记录的商户订单号和交易号等内容。</font>
                    <br />
                    <span id="czts"></span>
                    <input type="submit" name="bu1" value=" 提 交 " id="bu1" style="margin-left:200px;"/>
                    <span id="tp"></span>
                </p>
            </form>
    </div>
</div>
