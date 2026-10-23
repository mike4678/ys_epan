<?php
include "includes/Init.php";
include PHPDISK_ROOT.'includes/top.php';
?>
<title>产品列表</title>
<link rel="stylesheet" href="<?php echo PHPDISK_ROOT_WEB; ?>css/pricing.css">
<div class="pricing-page">
    <div class="pricing-head">
        <h1 class="pricing-title">产品列表</h1>
        <p class="pricing-sub">选择适合您的存储空间，可随时自助升级扩容</p>
    </div>

    <div class="pricing-table-wrap">
        <?php $db->Product('1');?>
        </table>
    </div>

    <div class="pricing-note">
        <h3 class="pricing-note-title">说明</h3>
        <p><b>单个文件最大</b>：是指上传时单个文件的大小不能超过此大小限制。</p>
        <p><b>两个小时最大下载量</b>：即两个小时最多从空间内下载文件的数据量，当超过这个容量限制时，用户将不能再从空间内下载文件。用户提交下载请求时，系统会计算从用户提交时到2小时前的下载量。如果没有超过流量限制就可以下载。</p>
        <p class="pricing-note-muted">付费型空间的价格与开通详情，请以 <a href="/ht/">账户充值</a> 与 <a href="/ht/index.php?method=kjsj">空间升级</a> 页面为准。</p>
    </div>

    <h3 class="pricing-sec-title">付费、免费的区别</h3>
    <div class="pricing-compare">
        <table>
            <tr>
                <td align="right" class="tdbt">　</td>
                <td align="center"><b><font color="#003399">免费空间</font></b></td>
                <td align="center" width="150"><b><font color="#003399">付费空间</font></b></td>
            </tr>
            <tr>
                <td align="right" class="tdbt">单目录中记录数限定</td>
                <td align="center">60个</td>
                <td align="center" width="150">300个</td>
            </tr>
            <tr>
                <td align="right" class="tdbt">留言本记录总数限定</td>
                <td align="center">1000条</td>
                <td align="center" width="150">5000条<br />（企业型用户限制30000条）</td>
            </tr>
            <tr>
                <td align="right" class="tdbt">显示广告</td>
                <td align="center">√</td>
                <td align="center" width="150">×</td>
            </tr>
        </table>
    </div>
    <p class="pricing-compare-note">
        右表列出了免费空间和付费空间的主要区别。
        <br />
        另外付费空间和免费空间的服务器完全分离，付费空间用户使用的是独享带宽，免费用户使用的是共享带宽，在网络高峰期时付费用户的网速更有保障。
        <br />
        空间升级、续费皆可全程自助完成。
    </p>

    <div class="pricing-cta">
        <a href="/register.php" class="btn btn-primary">注册空间</a>
        <a href="/ht/index.php?method=kjsj" class="btn btn-secondary">立即升级空间</a>
    </div>
</div>

<?php

include PHPDISK_ROOT."./includes/footer.php";

?>
