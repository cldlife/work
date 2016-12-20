<h2 class="fixmt">玩主API接口说明_<?php echo $this->version?></h2>

<h2>公共参数</h2>
<p>
    <h4>API请求地址：</h4>
    <?php echo WEB_QW_APP_API_DOMAIN;?>/<strong>[api_version][api_path]</strong>
</p>
<p><h4>HTTP请求方式：</h4>POST （测试期间支持GET）</p>
<p><h4>返回数据格式：</h4>JSON</p>
<p><h4>[api_version]：</h4><?php echo $this->version?></p>
<p><h4>密匙id [client_id]：</h4>1</p>
<p><h4>静态密匙key [client_secret]：</h4>API_QW_WanZhu_client_secret</p>
<p><h4>动态密匙签名key：</h4>API_QW_WanZhu_Sig_Key_t201609101734</p>
<p><h4>动态密匙签名算法：</h4>md5(签名key + client_id + api_version + t)</p>
<p><h4>上传token（用作file name）：</h4>API_QW_WanZhu_upload_key</p>
<!-- <p><h4>测试参数 s_uuid：</h4>MXwxfDE0Yjc4ZTUzNGJhOTBlOTFhOGI3YTYzNGIzMjc4NjBlXzQzMjI3ZGEzYmNmNzZlZjZlYmZmYTUzYWNmNTMzNDRm==</p> -->

<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
          <td class="bold">version</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>当前APP版本号, 如 <?php echo $this->version?></td>
        </tr>
        <tr>
            <td class="bold">client_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密匙id</td>
        </tr>
        <tr>
            <td class="bold">client_secret</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>密匙key（动态密匙）</td>
        </tr>
        <tr>
          <td class="bold">t</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>内建时钟时间戳（用于动态密匙安全时间验证）</td>
        </tr>
        <tr>
          <td class="bold">network</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>当前手机网络状态，如：wifi，china_mobile_3G</td>
        </tr>
        <tr>
          <td class="bold">system</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>当前手机系统类型，如：iPhone 4S 7.1.0</td>
        </tr>
    </tbody>
</table>

<h2>返回码</h2>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>

        <tr>
            <td class="bold">code</td>
            <td align="center">int</td>
            <td>返回码数值:<br><br>等于0 - 成功<br>大于0 - 失败；等于403 - 则app切到登录界面</td>
        </tr>
        <tr>
            <td class="bold">description</td>
            <td align="center">string</td>
            <td>返回码描述信息</td>
        </tr>
        <tr>
            <td class="bold">apptip</td>
            <td align="center">string</td>
            <td>应用提示文案:<br><br>不为空 - 则弹框提示description<br>为空 - 则不提示</td>
        </tr>

    </tbody>
</table>

<h2>游戏接口</h2>

<p><h4><a><span style="color:red;">＝＝＝＝＝＝＝＝＝＝＝＝ 接口同v1.2.0 ＝＝＝＝＝＝＝＝＝＝＝＝ </span></a></h4></p>
<p>
    <h4><a name="g1.1" id="g1.1">》1.1、游戏配置信息</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/info.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/info');?>" target="_blank">测试 》</a></span>
    <br>
    <span>游戏id（1：听歌曲猜歌名，4：谁是卧底，5：你画我猜 ...）</span>
</p>
<p>
    <h4><a name="g1.2" id="g1.2">》1.2、同步游戏房间用户信息（APP调RC加入房间成功后再调用此API）</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/syncroom.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/syncroom');?>" target="_blank">测试 》</a></span>
    <br>
</p>

<p>
    <h4><a name="g1.3" id="g1.3">》1.3、退出游戏房间</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/quitroom.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/quitroom');?>" target="_blank">测试 》</a></span>
    <br>
</p>

<p>
    <h4><a name="g1.4" id="g1.4">》1.4、准备开始游戏</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/readyroom.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/readyroom');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<br/> <br/> <br/>

<p><h4><a>＝＝＝＝＝＝＝＝＝＝＝＝ 你画我猜 ＝＝＝＝＝＝＝＝＝＝＝＝ </a></h4></p>
<p>
    <h4><a name="g2.1" id="g2.1">》2.1、评价题目</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/draw/votetm.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/votetm');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">word_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>画的词id</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>评价：1-好题，2-差题</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g2.2" id="g2.2">》2.2、换词</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/draw/change.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/change');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">words</td>
            <td align="center">object</td>
            <td>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"1": "西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"2": "冬瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"3": "南瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"4": "哈密瓜"
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            </td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g2.3" id="g2.3">》2.3、选好词</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/draw/choose.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/choose');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">word_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>选好的词id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g2.4" id="g2.4">》2.4、分享图片</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/draw/share.json<font color="red">（需验证上传token）</font>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/share');?>" target="_blank">测试 》</a></span>
    <br>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">是否必须</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">s_uuid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>用户登录成功后生成的会话id</td>
        </tr>
        <tr>
            <td class="bold">game_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>游戏的id(游戏开始的时候传入)</td>
        </tr>
        <tr>
            <td class="bold">uid</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>作画者的uid</td>
        </tr>
        <tr>
            <td class="bold">t_key</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>t_key为时间戳（秒，10位）</font></td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">share_url</td>
            <td align="center">string</td>
            <td>分享的链接<span style="color:red;">(code == 0 时)</span></td>
        </tr>
    </tbody>
</table>
<br/> <br/> <br/>

<p>
<h4>
    <a name="g2.5" id="g2.5">》融云内置消息中的extra（附加信息）约束定义（JSON格式）：</a>
</h4>
</p>
<p><b>发送/接收消息：</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td align="center">int</td>
            <td>游戏房间id</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td align="center">string</td>
            <td>消息类型，room - 房间消息，game - 游戏消息</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td align="center">string</td>
            <td>执行动作，join - 加入房间</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td align="center">json</td>
            <td>详细信息</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td align="center">string</td>
            <td>当前API版本号，如2.0.0</td>
        </tr>
    </tbody>
</table>
<br/> <br/> <br/>

<p><h4><a><span style="color:red;">＝＝＝＝＝＝＝＝＝＝＝ 与v1.2.0相同 ＝＝＝＝＝＝＝＝＝＝＝</span></a></h4></p>
<p>
    <h4>
    <a name="g2.6" id="g2.6">》2.6、已加入房间</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/joinroom');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"join","content":{},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.7" id="g2.7">》2.7、已退出房间</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/quitroom');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"quit","content":{},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.8" id="g2.8">》2.8、已准备</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/readyroom');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"ready","content":{},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.9" id="g2.9">》2.9、已离线</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/offline');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"offline","content":{},"v":"2.0.0"}</p>
<br/> <br/> <br/>

<p><h4><a><span style="color:red;">＝＝＝＝＝＝＝＝＝＝＝ 与v1.2.0不同 ＝＝＝＝＝＝＝＝＝＝＝</span></a></h4></p>
<p>
    <h4>
    <a name="g2.10" id="g2.10">》2.10、开始游戏（并分配选词）</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/start');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>start</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>game_id:分享图片时的游戏id,&nbsp;&nbsp;uid:开始选词的uid
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"game_id":1,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uid":"TWZ1",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"words":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"1": "西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"2": "冬瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"3": "南瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"4": "哈密瓜"
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"start","content":{"game_id":1,"uid":"TWZ1","words":{"1":"\u897f\u74dc","2":"\u51ac\u74dc","3":"\u5357\u74dc","4":"\u54c8\u5bc6\u74dc"}},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.11" id="g2.11">》2.11、开始选词<span style="color:red;">(下一轮开始时)</span></a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/startchoose');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>choose</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>uid:开始选词的uid
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uid":"TWZ1",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"words":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"1": "西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"2": "冬瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"3": "南瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"4": "哈密瓜"
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"choose","content":{"uid":"TWZ1","words":{"1":"\u897f\u74dc","2":"\u51ac\u74dc","3":"\u5357\u74dc","4":"\u54c8\u5bc6\u74dc"}},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.12" id="g2.12">》2.12、用户猜词</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/guess');?>" target="_blank">测试 》</a></span>
    </h4>
    <p style="color:red;">Client -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>guess</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"word":"西瓜",
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"guess","content":{"word":"\u897f\u74dc"},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.13" id="g2.13">》2.13、猜词结果</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/guessresult');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>guess_result</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td> uid:猜词的uid,&nbsp;&nbsp;right:0-猜错;1-猜对,&nbsp;&nbsp;word:猜的词,&nbsp;&nbsp;no:第几个猜的,猜对时使用
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uid":"TWZ1",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"right":1,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"word":"西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"points":10,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"no":1
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"guess_result","content":{"uid":"TWZ1","word":"\u897f\u74dc","points":10,"no"1},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.14" id="g2.14">》2.14、提示</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/hint');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>hint</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"type":1,&nbsp;&nbsp;&nbsp;&nbsp;(1,字数提示;2,提示词)
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"clue":"两个字",
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"hint","content":{"type":1,"clue":"\u897f\u74dc"},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.15" id="g2.15">》2.15、画图结束</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/drawover');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>draw_over</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>作画者uid,&nbsp;&nbsp;获得的积分
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uid":"TWZ1",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"points":20
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"draw_over","content":{"uid":"TWZ1","points":20},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.16" id="g2.16">》2.16、一局结束</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/roundover');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>round_over</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"word":"\u897f\u74dc"
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"round_over","content":{"word":"\u897f\u74dc"},"v":"2.0.0"}</p>

<p>
    <h4>
    <a name="g2.17" id="g2.17">》2.17、游戏结束返回结果</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/draw/gameover');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -&gt; SendGameMsg</p>
</p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th>参数值</th>
        </tr>
        <tr>
            <td class="bold">roomid</td>
            <td>1688</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td>game</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>game_over</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>"uid":{win,coin_desc} <span style="color:red;">(win:-1,输;0,不输不赢;1,赢)</span>
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"TWZ1":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"win":1,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"coin_desc":"+20金币",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;},
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"TWZ2":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"win":0,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"coin_desc":"+10金币",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;},
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"TWZ3":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"win":0,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"coin_desc":"+10金币",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;},
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"TWZ4":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"win":-1,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"coin_desc":"-20金币",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>2.0.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>
{"roomid":1688,"type":"game","action":"over","content":{"TWZ1":{"win":1,"coin_desc":"+20\u91d1\u5e01"},"TWZ2":{"win":0,"coin_desc":"+10\u91d1\u5e01"},"TWZ3":{"win":0,"coin_desc":"+10\u91d1\u5e01"},"TWZ4":{"win":-1,"coin_desc":"-20\u91d1\u5e01"}},"version":"2.0.0"}</p>

