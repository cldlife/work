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
<p>
    <h4><a name="g1.1" id="g1.1">》1.1、游戏配置信息</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/info.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/info');?>" target="_blank">测试 》</a></span>
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
            <td class="bold">gid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏id（1：听歌曲猜歌名，4：谁是卧底，5：你画我猜 ...）</td>
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
            <td class="bold">info</td>
            <td align="center">object</td>
            <td>roomid - 游戏房间id<br>name - 游戏名称<br>quit_coins - 游戏中退出需扣的金币数<br>play_permission - 1-有权限玩，0-无权限玩<br>im_config - object，聊天组件配置<br> -- YuYin - 语音启用状态，0-关闭，1-开启</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g1.2" id="g1.2">》1.2、同步游戏房间用户信息（APP调RC加入房间成功后再调用此API）</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/syncroom.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/syncroom');?>" target="_blank">测试 》</a></span>
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
            <td class="bold">roomid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏房间id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：（=》请求失败，APP调用RC退出房间）</b></p>
<table class="paratable" width="60%">
    <tbody>
        <tr>
            <th width="80">参数名称</th>
            <th width="80">类型</th>
            <th>描述</th>
        </tr>
        <tr>
            <td class="bold">room_users</td>
            <td align="center">list</td>
            <td>当前房间在线人数：<br><br>uid - 用户id<br>nickname - 昵称<br>avatar - 头像url<br>is_readied - 是否已准备，0-否，1-是</td>
        </tr>
    </tbody>
</table>

<p>
    <h4><a name="g1.3" id="g1.3">》1.3、退出游戏房间</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/quitroom.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/quitroom');?>" target="_blank">测试 》</a></span>
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
            <td class="bold">roomid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏房间id</td>
        </tr>
        <tr>
            <td class="bold">type</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>类型：0-游戏未开始，1-游戏中（扣金币提示）</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g1.4" id="g1.4">》1.4、准备开始游戏</a></h4>
    <br>
    <strong>[api_path]：</strong>/game/readyroom.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/game/readyroom');?>" target="_blank">测试 》</a></span>
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
            <td class="bold">roomid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏房间id</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p><h4><a>＝＝＝＝＝＝＝＝＝＝＝＝ 谁是卧底 ＝＝＝＝＝＝＝＝＝＝＝＝ </a></h4></p>
<p>
    <h4><a name="g2.1" id="g2.1">》2.1、第一轮卧底死卧底猜词</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/spy/guessword.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/guessword');?>" target="_blank">测试 》</a></span>
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
            <td class="bold">roomid</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>游戏房间id</td>
        </tr>
        <tr>
            <td class="bold">word</td>
            <td align="center" class="red">必须</td>
            <td align="center">string</td>
            <td>词语</td>
        </tr>
    </tbody>
</table>
<p><b>返回参数说明：</b></p>
默认

<p>
    <h4><a name="g2.2" id="g2.2">》2.2、评价题目</a></h4>
    <br>
    <strong>[api_path]：</strong>/games/spy/votetm.json
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/votetm');?>" target="_blank">测试 》</a></span>
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
            <td class="bold">words_id</td>
            <td align="center" class="red">必须</td>
            <td align="center">int</td>
            <td>卧底词组id</td>
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
<h4>
    <a name="g2.1" id="g2.1">》融云内置消息中的extra（附加信息）约束定义（JSON格式）：</a>
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
            <td>当前API版本号，如1.2.0</td>
        </tr>
    </tbody>
</table>

<p>
    <h4>
    <a name="g2.3" id="g2.3">》2.3、已加入房间</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/joinroom');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>room</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>join</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>{}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"join","content":{},"v":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.4" id="g2.4">》2.4、已退出房间</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/quitroom');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>room</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>quit</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>{}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"quit","content":{},"v":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.5" id="g2.5">》2.5、已准备</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/readyroom');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>room</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>ready</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>{}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"ready","content":{},"v":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.6" id="g2.6">》2.6、已离线</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/offline');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>room</td>
        </tr>
        <tr>
            <td class="bold">action</td>
            <td>offline</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>{}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"room","action":"offline","content":{},"v":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.7" id="g2.7">》2.7、开始游戏（并分配词组）</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/start');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>分配的词组与用户uid对应关系：
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"user_words":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"TWZ1": "西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"TWZ2": "西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"TWZ3": "哈密瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"TWZ4": "西瓜"
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"start","content":{"user_words":{"1":"\u897f\u74dc","2":"\u897f\u74dc","3":"\u897f\u74dc","4":"\u54c8\u5bc6\u74dc"}},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.8" id="g2.8">》2.8、描述</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/desc');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server/Client -> SendGameMsg</p>
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
            <td>desc</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>描述词：
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"word": "甜甜的"
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"desc","content":{"word":"\u751c\u751c\u7684"},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.9" id="g2.9">》2.9、开始投票</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/startvote');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>startvote</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>{}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"startvote","content":{},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.10" id="g2.10">》2.10、开始PK投票</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/startpkvote');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>startpkvote</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>{}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"startpkvote","content":{},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.11" id="g2.11">》2.11、投票</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/vote');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server/Client -> SendGameMsg</p>
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
            <td>vote</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>被投票用户uid：
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uid": "TWZ1"
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"vote","content":{"uid":"TWZ1"},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.12" id="g2.12">》2.12、PK</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/pk');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>pk</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>PK的用户uids：
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uids": ["TWZ1","TWZ2"]
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"pk","content":{"uids":["TWZ1","TWZ2"]},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.13" id="g2.13">》2.13、平民/卧底死</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/die');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>die</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>死的用户user，is_spy：0-平民，1-卧底
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"user": 
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"uid":"TWZ1",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"is_spy":1
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"die","content":{"user":{"uid":"TWZ1","is_spy":1}},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.14" id="g2.14">》2.14、平局</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/dogfall');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>dogfall</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>平局用户uids
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"uids": ["TWZ1","TWZ2", "TWZ3"]
            <br>}</td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>{"roomid":1688,"type":"game","action":"dogfall","content":{"uids":["TWZ1","TWZ2", "TWZ3"]},"version":"1.2.0"}</p>

<p>
    <h4>
    <a name="g2.15" id="g2.15">》2.15、游戏结束返回结果</a>
    <span class="btn_test"><a href="<?php echo $this->getApiUrl('/games/spy/over');?>" target="_blank">测试 》</a></span>
    </h4>
    <p>Server -> SendGameMsg</p>
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
            <td>over</td>
        </tr>
        <tr>
            <td class="bold">content</td>
            <td>死的用户user，win：0-平民，1-卧底，spy：卧底信息，normal：平民信息
            <br>{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;"result":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"words_id":1,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"win":1,
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"spy":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"word":"哈密瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"uid":"TWZ3",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"coin_desc":"+20"
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"normal":
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"word":"西瓜",
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"uids":["TWZ1","TWZ2","TWZ4"],
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"coin_desc":"-20"
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>&nbsp;&nbsp;&nbsp;&nbsp;}
            <br>}
            </td>
        </tr>
        <tr>
            <td class="bold">v</td>
            <td>1.2.0</td>
        </tr>
    </tbody>
</table>
<p>示例：</p>
<p>
{"roomid":1688,"type":"game","action":"over","content":{"result":{"win":1,"spy":{"word":"\u54c8\u5bc6\u74dc","uid":"TWZ3","coin_desc":"+20"},"normal":{"word":"\u897f\u74dc","uids":["TWZ1","TWZ2","TWZ4"],"coin_desc":"-20"}}},"version":"1.2.0"}</p>