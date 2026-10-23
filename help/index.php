<?php
include "../includes/Init.php";
include PHPDISK_ROOT.'includes/top.php';
// ===== help/ 单页化（诉求1）：通过 ?method=diy|bigfile 分发内容，菜单选中态随当前页动态高亮 =====
$method = isset($_GET['method']) ? trim($_GET['method']) : '';
if($method != '' && !in_array($method, array('diy','bigfile'))){ $method=''; }
// 页面标题（top.php 输出 <head> 但无 <title>，此处按 method 补中文标题，UTF-8）
$help_titles = array(
	''        => '使用说明',
	'diy'     => '如何设置自定义区',
	'bigfile' => '采用分压缩方式上传大文件',
);
$help_title = isset($help_titles[$method]) ? $help_titles[$method] : '使用说明';
echo '<title>'.htmlspecialchars($help_title, ENT_QUOTES, 'UTF-8').' - '.htmlspecialchars(Website_title, ENT_QUOTES, 'UTF-8').'操作指南</title>';
?>
<style type="text/css">
/* ===== help/ 页面样式统一（与 ht 后台一致：div/flex + 设计 Token #2563eb）===== */
*{box-sizing:border-box}
#helpwrap{max-width:1080px;margin:0 auto;padding:0 20px}
.help-layout{display:flex;align-items:flex-start;gap:20px}
#ysleft{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.06);width:208px;flex:0 0 208px;box-sizing:border-box;padding:18px 14px;margin:0}
#ysleft h2{font-size:15px;font-weight:700;color:#1d4ed8;border-bottom:1px solid #dbeafe;padding-bottom:20px;margin:0 0 10px}
#ysleft ul{list-style:none;margin:0;padding:0;height:auto !important;overflow:visible !important}
#ysleft li{height:auto !important;line-height:1.6 !important;margin:5px 0 !important;padding:0 !important}
#ysleft a{display:block;color:#374151;text-decoration:none;padding:9px 12px !important;border-radius:8px;font-size:13px;line-height:1.6;overflow:visible;white-space:normal;width:auto !important;box-sizing:border-box}
#ysleft a:hover{background:#eff6ff;color:#2563eb}
#ysleft a.xz,#ysleft a#xz{background:#2563eb !important;color:#fff !important;font-weight:600}
#ysright{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 14px rgba(0,0,0,.06);padding:24px 28px;box-sizing:border-box;flex:1 1 auto;min-width:0;overflow:auto;width:auto !important;margin:0}
#ysright h1{font-size:20px;font-weight:700;color:#1f2937;border-bottom:1px solid #e5e7eb;padding-bottom:12px;margin:0 0 6px;display:flex;align-items:center;gap:8px}
#ysright h3{font-size:15px;font-weight:700;color:#1d4ed8;margin:22px 0 10px;padding-left:10px;border-left:3px solid #2563eb}
#ysright ul{list-style:none;margin:0;padding:0}
#ysright li{font-size:14px;color:#374151;line-height:1.8;padding:7px 0 7px 20px;position:relative;border-bottom:1px solid #f3f4f6}
#ysright li::before{content:"•";position:absolute;left:4px;color:#2563eb;font-weight:bold}
#ysright a{color:#2563eb;text-decoration:none}
#ysright a:hover{text-decoration:underline}
.zdy{font-size:14px;color:#374151;line-height:1.8;margin-bottom:6px}
.xbt{font-weight:bold; font-size:12pt;}
@media(max-width:900px){.help-layout{flex-direction:column}#ysleft{width:100%;flex:1 1 auto}}
</style>
<script language="JavaScript" type="text/javascript" src="ajax.js"></script>
<script language="javascript" type="text/javascript">
    function cxdlmc() {
        if (document.getElementById("tedlmc").value == "") {document.getElementById("sm_dlmc").innerHTML = "<br /><font color=red>请输入您的用户名</font>";return ""; }
        if (document.getElementById("tedlmc").value.length > 15) { document.getElementById("sm_dlmc").innerHTML = "<br /><font color=red>抱歉，您输入的用户名有误</font>"; return ""; }
        if (checkzf(document.getElementById("tedlmc").value, "1234567890abcdefghijklmnopqrstuvwxyz-") == "F") { document.getElementById("sm_dlmc").innerHTML = "<br /><font color='red'>用户名只支持数字、字母和-</font>"; return ""; }
        sendRequest('index_dlmc.php', 'dlmc=' + document.getElementById("tedlmc").value, xmljs, '');
    }
    function xmljs(s1) { document.getElementById("sm_dlmc").innerHTML = s1; }
    function checkzf(dqqzf, zffw) {
        for (i = 0; i <= dqqzf.length - 1; i++) {
            if (zffw.indexOf(dqqzf.substr(i, 1).toLowerCase()) < 0) { return "F" }
        }
        return "T"
    }
    function writedlmc() { if (event.keyCode == 13) { cxdlmc(); } }
</script>

<div id="helpwrap">
    <div class="help-layout">
        <aside id="ysleft">
        <h2>帮助菜单</h2>
        <ul class="yslb3">
	        <li><a href="/help/"<?php echo $method=='' ? ' id="xz"' : ''; ?>>使用说明</a></li>
	        <li><a href="/help/?method=diy"<?php echo $method=='diy' ? ' id="xz"' : ''; ?>>如何设置自定义区</a></li>
	        <li><a href="/help/?method=bigfile"<?php echo $method=='bigfile' ? ' id="xz"' : ''; ?>>采用分压缩方式上传大文件</a></li>
        </ul>
        </aside>
        <main id="ysright">
        <?php switch($method){
            case 'diy': ?>
            <h1><img border="0" src="/ht/images/js.gif" width="30" height="27" alt="" />如何设置空间自定义区</h1>
            <font size="2"><label class="kt">空</label>间自定义区放置于空间目录列表的右边。用于显示图片、音频、视频、链接、文字等，会使空间更加非富。</font>

            <h3>开启自定义区</h3>
            <div class="zdy">
                开启自定义区很简单，只要<span class="zd">空间里有一个署名为&nbsp;<font color="#0000FF">*</font>&nbsp;的目录</span>即可，可以通过编辑现有目录或新建一个目录实现(以下称此目录为自定义目录)。如下图：
                <br />
                <img  src="images/zdy01.gif"  style="border-width: 0px;width:268px; height:156px;" alt="增加自定义目录" />
                <br />
                <ul class="yslb2">
                    签写署名里填写的&nbsp;*&nbsp;前后都不要增加空格等其他字符。
                    如果空间内有多个署名为&nbsp;*&nbsp;的目录，那么系统会默认符合条件中最上面的目录为自定义目录。
                </ul>
            </div>
            <h3>设置栏目</h3>
            <div class="zdy">
                自定义区的栏目和自定义目录的子目录一一对应，设置栏目只需要<span class="zd">在自定义目录内增加子目录</span>即可，显示的栏目名称和与其对应的子目录名一致。自定义区内最多支持10个栏目。
                <br />建立子目录如下图：
                <br />
                <img src="images/zdy02.gif" style="width:268px; height:156px;border-width: 0px;" alt="建立子目录" />
            </div>
            <h3>添加内容</h3>
            <div class="zdy">
                <span class="zd">在上一步建立的子目录内增加内容</span>即可完成自定义区的设置。<font color="green">设置完毕后点此目录内的[刷新]按钮即可看到效果。</font><br />
                栏目显示的内容取决于此子目录内的第一项。例如：子目录内的第一项是图片文件，那么对应的栏目内就会显示此图片。
            </div>
            <h3>栏目分类</h3>
            <div class="zdy">
                <ul class="yslb2">
                    <span class="fl">图片</span>设置方法：子目录中的第一项是图片文件。
                        <br />
                        如果子目录中有多张图片，则与其对应的栏目会对这些图片进行轮流播放。
                        <br />
                        自定义区显示的图片大小宽度固定为162像素，高度按照第一张图片的高宽比确定。大于800KB的图片文件不予显示。
                    
                    <span class="fl">视频、音频</span>设置方法：子目录中的第一项是视频、音频文件。
                        <br />
                        视频、音频文件支持：mid、mp4、swf、mpg、mp3、wma、asf、wmv、avi等格式，如果要在播放窗口中显示视频，则需修改此文件的备注为 <font color="#0000FF" size="4">*</font>，否则，将只播放音频。如下图
                        <br />
                        <img src="images/zdy04.gif" style="width:268px; height:156px;border-width: 0px;" alt="显示视频文件" />
                    
                    <span class="fl">链接、文字</span>设置方法：子目录中的第一项是链接、文字。
                        <br />
                        链接和文字是在上传文件的窗口内选择“链接网址”设置的，如果链接网址一栏内不是“http”开头的，就会按照文字显示。如下图
                        <br />
                        <div style=" float:right;"><img src="images/zdy06.gif" style="width:190px; height:156px;border-width: 0px;" alt="增加文字项" /></div>
                        <img src="images/zdy03.gif" style="width:268px; height:156px;border-width: 0px;" alt="增加文字项" />
                    
                </ul>
            </div>
            <h3>注意事项</h3>
            <div class="zdy">
                <ul class="yslb2">
                    自定义区内只显示一个图片轮播栏目。其他类型栏目不限。
                    如果视频、音频栏目内有多个文件，那么只播放第一项的文件。
                    子目录的排列顺序是按照子目录名正排，您可以在子目录名前增加数字方便子目录的排列。
                    如果您的空间流量超过限制，则自定义区内有关文件的内容将不能显示。
                    自定义区内的文件显示或播放将占用空间下载流量，请尽量把图片缩小后上传，宽度为162像素为最佳。视频、音频文件推荐使用rm、swf格式。
                    图片轮播窗口内的图片，请尽量选用高宽比相近的。
                </ul>
            </div>
            <?php break;
            case 'bigfile': ?>
            <h1><img border="0" src="/ht/images/js.gif" width="30" height="27" alt="" />采用分压缩方式上传大文件</h1>
            <p style="text-indent: 2em;">为了安全稳妥的上传大文件（>50M）,建议您把文件分压缩后（每个包<=30M）再逐个上传。操作方法如下: </p>
            <ul class="yslb2">
                右键点击您要上传的文件，在右键菜单里选择"添加到压缩文件"<img src="/help/images/big0.gif" width="309" height="188" alt="" /><br />
                在弹出的界面里设置每个包的大小，一般为"30M",然后点确定即进行分压缩<br /><img src="/help/images/big1.gif" width="413" height="358" alt="" /><br />
                文件所在目录将出现若干个分压缩包，把这些分压缩包逐个上传即可<br /><img src="/help/images/big2.gif" width="484" height="74" alt="" /><br />
                解压方法：只要把这些分压缩包下载并存放于同一目录，点任意一个包解压即可还原成原来文件。
            </ul>
            <?php break;
            default: ?>
            <h1><img border="0" src="/ht/images/js.gif" width="30" height="27" alt="" />使用说明</h1>
            <h3>如何进入空间</h3>
            <ul class="yslb2">
                用户注册成功后即可通过页面提示的空间地址登录，在浏览器地址栏内输入此地址即可。
                <br />
                请输入您的用户名：<input type="text"  id="tedlmc"  style="width:150px;" onkeydown="writedlmc();" />
                <input type="button" onclick="cxdlmc();" value="查询空间地址" /><span id="sm_dlmc"></span>
                
                
                <br >如果您要限制别人访问您的空间，您可以在<a href="/ht/index.php?method=dlmm">设置登录密码</a>页面设置登录密码。
            </ul>
            <h3>如何添加内容</h3>
            <ul class="yslb2">
                <span class="xbt">建立目录</span>&nbsp;初次使用的时候，目录列表是空的，需要在左上角的功能区内点“增加目录”来建立您的目录<br /><img border="0" src="images/h1.gif" width="260" height="154" alt="增加目录" /><br /><br />
                <span class="xbt">上传文件</span>&nbsp;点击目录名打开目录，功能区会转到“上传文件”栏。点“浏览”选择您需要上传的文件,，按“开始上传”按钮即可进行上传。此栏目内容比较多，除了上传文件外，还集成了添加网址链接、增加子目录等。如右图<br /><img border="0" src="images/h1_2.gif" width="260" height="154" alt="增加目录"  />
            </ul>
            <h3>如何编辑、删除数据</h3>
            <ul class="yslb2">
                点击数据项前面的<font color="blue">小图标</font>即可在弹出小窗口内进行相关的编辑、删除操作。小图标的位置如下图所示。<br />
                <img border="0" src="images/h2.gif"  alt="" width="462" height="326" />
                <br >
                如果您没有在空间管理区登录，系统会提示您先在空间管理区登录后再进行编辑。
                点击图标后，就可以在弹出的窗口内进行编辑、删除操作
                访客新增的数据（目录、文件、留言等），将会在数项后面显示（编辑）字样，以便进行即时修改，当其再次进入空间时，将不再显示（编辑）字样，请点击小图标进行编辑。
            </ul>
            <h3>如何进行空间升级</h3>
            <ul class="yslb2">
                空间升级所需费用从账户余额中扣除。首先到<a href="/ht/index/kjsj">空间升级</a>处计算所需费用
                然后把所需费用充值到<a href="/ht/index/yfk">账户余额</a>
                充值完成后再到<a href="/ht/index/kjsj">空间升级</a>处完成空间升级操作。
                付费空间未到期也可以进行升级操作，升级时空间剩余的期限按照天数比例换算成金额，增加在您的空间账户内。
            </ul>
            <h3>如何进行空间延期</h3>
            <ul class="yslb2">
                空间延期所需费用从账户余额中扣除。首先到<a href="/ht/index/kjyq">空间延期</a>处计算所需费用
                然后把所需费用充值到<a href="/ht/index/yfk">账户余额</a>
                充值完成后再到<a href="/ht/index/kjyq">空间延期</a>处完成空间延期操作。
                付费空间未到期也可以进行延期操作，延期后使用期限到原来的日期加上延期的时间。
            </ul>
            <h3>账户充值</h3>
            <ul class="yslb2">
                <a href="/ht/index/yfk">账户充值</a>支持银行卡在线支付、银行卡转账、支付宝支付等方式
            </ul>

            <h3>其它</h3>
            <ul class="yslb2">
                空间支持自定义功能，详情见<a href="/help/?method=diy">如何设置自定义区</a>
                我们有严格的下载流量限制，具体请查看<a href="../list_cp.php">产品介绍</a>。
                免费空间两个月内至少要管理（在管理区或者在空间后台登录）一次，否则空间会被系统自动锁定，如果超过半年没有进行管理，系统会删除此空间。
                免费空间的数据无备份服务，并不对免费空间的数据安全负责，请免费用户不要在空间上传重要的数据。
                如果您对我们的产品满意，建议<a href="/ht/index.php?method=kjsj">升级</a>您的网盘以获得更好的服务。
                请不要传播违法的内容，否则我们会配合国家执法部门追究违规人员的责任。
                为了避免您的空间被不法份子利用，建议限制访客的<a href="/ht/index.php?method=qx">上传权限</a>，或给空间<a href="/ht/index.php?method=dlmm">设置登录密码</a>
                如果还有其它问题，请到<a href="/kf.php">客服中心</a>提问或和我们联系
            </ul>
            <?php break;
        } ?>
        </main>
    </div>
</div>

<?php
include PHPDISK_ROOT."./includes/footer.php";
?>
