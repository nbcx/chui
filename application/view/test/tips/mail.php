<style>
    .mail {
        background-color:#ECECEC;
        padding: 35px;
    }
    table {
        width: 600px;
        margin: 0px auto;
        text-align: left;
        position: relative;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
        font-size: 14px;
        font-family:微软雅黑, 黑体;
        line-height: 1.5;
        box-shadow: rgb(153, 153, 153) 0px 0px 5px;
        border-collapse: collapse;
        background:#fff;
    }
    table th {
        height: 25px;
        line-height: 25px;
        padding: 15px 35px;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: #C46200;
        background-color: #404040;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 0px;
        border-bottom-left-radius: 0px;
        color: rgb(255, 255, 255);
    }
    table div {
        padding:25px 35px 40px;
        background-color:#fff;
    }
    table div h2 span {
        color:#333333;
        line-height: 22px;
    }
    table div .code {
        color: #FF0000;
        font-size: 18px;
    }
</style>
<div class="mail">
    <table cellpadding="0" align="center">
        <tbody>
        <tr>
            <th valign="middle">
                <?=$name?>
            </th>
        </tr>
        <tr>
            <td>
                <div>
                    <h3>亲爱的<span><?=$username?></span>:</h3>
                    <p>您的验证码为:<b class="code"><?=$code?></b></p>
                    <p>感谢您使用<?=$name?>！请您在发表言论时，遵守当地法律法规。<br/>
                        <?=$name?>，给您一个尽情BB的地方。<br/>
                        如果您有什么疑问可以进入我们的QQ群进行反馈，QQ群: <?=$qqgroup?>。</p>
                    </p>
                    <p align="right"><?=$name?>官方团队</p>
                    <p align="right"><?=date('Y-m-d H:i:s')?></p>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>