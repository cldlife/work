use `dev_wanzhu_game`;

INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"伴娘","normal":"伴郎"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '婚礼上才有'),
(@last_id, '0', '对性别有要求'),
(@last_id, '0', '必须是未婚'),
(@last_id, '0', '很累'),
(@last_id, '0', '需要喝酒'),
(@last_id, '0', '有着装要求'),
(@last_id, '0', '不能做超过三次'),
(@last_id, '0', '不能太丑'),
(@last_id, '0', '非常重要'),
(@last_id, '0', '无法单独完成任务');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"诺贝尔","normal":"爱迪生"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有名的人'),
(@last_id, '0', '外国人'),
(@last_id, '0', '电灯'),
(@last_id, '0', '科学家'),
(@last_id, '0', '男人'),
(@last_id, '0', '已经去世'),
(@last_id, '0', '以他名字命名了奖项'),
(@last_id, '0', '对世界有杰出贡献'),
(@last_id, '0', '小学语文课本里有'),
(@last_id, '0', '生于18世纪末期');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"赵云","normal":"关羽"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '三国里的人物'),
(@last_id, '0', '追随刘备'),
(@last_id, '0', '男的'),
(@last_id, '0', '忠诚'),
(@last_id, '0', '被敌人称作英雄'),
(@last_id, '0', '大意失锦州'),
(@last_id, '0', '英俊潇洒'),
(@last_id, '0', '身长八尺'),
(@last_id, '0', '赤壁之战'),
(@last_id, '0', '曾被曹操生擒');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"猫咪","normal":"小狗"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种宠物'),
(@last_id, '0', '最常见的家养宠物'),
(@last_id, '0', '小动物'),
(@last_id, '0', '很可爱'),
(@last_id, '0', '很粘人'),
(@last_id, '0', '会叫的'),
(@last_id, '0', '有很多的品种'),
(@last_id, '0', '温顺'),
(@last_id, '0', '有灵性'),
(@last_id, '0', '毛茸茸的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"万能充","normal":"充电器"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '充电用的'),
(@last_id, '0', '带插头'),
(@last_id, '0', '电子设备离不开它'),
(@last_id, '0', '旅游的时候一定要带'),
(@last_id, '0', '电子产品配件'),
(@last_id, '0', '可循环使用'),
(@last_id, '0', '有两头和三头两种'),
(@last_id, '0', '携带方便'),
(@last_id, '0', '几乎人人都有'),
(@last_id, '0', '价格不贵');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"汰渍","normal":"奥妙"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '洗衣粉'),
(@last_id, '0', '洗衣液'),
(@last_id, '0', '宝洁'),
(@last_id, '0', '老品牌'),
(@last_id, '0', '女明星做代言'),
(@last_id, '0', '99种污渍'),
(@last_id, '0', '可手洗可机洗'),
(@last_id, '0', '橙色'),
(@last_id, '0', '蓝色'),
(@last_id, '0', '妈妈爱用');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"花生","normal":"毛豆"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '下酒菜'),
(@last_id, '0', '一节一节的'),
(@last_id, '0', '常见的凉菜'),
(@last_id, '0', '一粒一粒的'),
(@last_id, '0', '可水煮可油炸'),
(@last_id, '0', '有壳'),
(@last_id, '0', '通常不吃外壳'),
(@last_id, '0', '全国都有种植'),
(@last_id, '0', '价格不贵'),
(@last_id, '0', '和啤酒是绝配');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"过山车","normal":"跳楼机"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '刺激'),
(@last_id, '0', '不敢往下看'),
(@last_id, '0', '游乐场经典项目'),
(@last_id, '0', '很多人一起玩'),
(@last_id, '0', '尖叫'),
(@last_id, '0', '心脏受不了'),
(@last_id, '0', '垂直'),
(@last_id, '0', '各种弯道'),
(@last_id, '0', '玩的'),
(@last_id, '0', '一次不过瘾');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"赵雷","normal":"宋冬野"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '民谣歌手'),
(@last_id, '0', '男的'),
(@last_id, '0', '北京人'),
(@last_id, '0', '八五后'),
(@last_id, '0', '南方姑娘'),
(@last_id, '0', '董小姐'),
(@last_id, '0', '吸毒'),
(@last_id, '0', '吉他弹唱'),
(@last_id, '0', '家喻户晓'),
(@last_id, '0', '安和桥');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"录音笔","normal":"录音机"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电子设备'),
(@last_id, '0', '需要充电'),
(@last_id, '0', '录音用的'),
(@last_id, '0', '可录可放'),
(@last_id, '0', '可存储音频'),
(@last_id, '0', '可随身携带'),
(@last_id, '0', '轻巧'),
(@last_id, '0', '把声音记录下来以便重放的设备'),
(@last_id, '0', '有很多日本知名品牌'),
(@last_id, '0', '这几年外形越来越小巧');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小米手机","normal":"魅族手机"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '手机品牌'),
(@last_id, '0', '国产的'),
(@last_id, '0', '雷军'),
(@last_id, '0', '很受年轻人追捧'),
(@last_id, '0', '品牌是两个字'),
(@last_id, '0', '价格亲民'),
(@last_id, '0', '广告投放多'),
(@last_id, '0', '智能手机'),
(@last_id, '0', '阿里巴巴入股'),
(@last_id, '0', '老总爱打赌');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"汉堡包","normal":"肉夹馍"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以当主食'),
(@last_id, '0', '面粉制品加肉'),
(@last_id, '0', '把肉夹起来吃'),
(@last_id, '0', '味道很浓郁'),
(@last_id, '0', '地方特色食品'),
(@last_id, '0', '价格不贵'),
(@last_id, '0', '麦当劳'),
(@last_id, '0', '全国各地都吃的到'),
(@last_id, '0', '个头不大'),
(@last_id, '0', '圆形的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"周迅","normal":"赵薇"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女演员'),
(@last_id, '0', '非常有名'),
(@last_id, '0', '当导演'),
(@last_id, '0', '出过专辑'),
(@last_id, '0', '四小花旦'),
(@last_id, '0', '声音很有辨识度'),
(@last_id, '0', '已经结婚'),
(@last_id, '0', '曾经很多绯闻'),
(@last_id, '0', '电视和电影都演'),
(@last_id, '0', '和陈坤常有合作');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鱿鱼丝","normal":"生鱼片"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '鱼制品'),
(@last_id, '0', '第二字是鱼'),
(@last_id, '0', '味道鲜美'),
(@last_id, '0', '下酒'),
(@last_id, '0', '分量很轻'),
(@last_id, '0', '价格不便宜'),
(@last_id, '0', '不需要加热便可食用'),
(@last_id, '0', '沿海地区吃的比较多'),
(@last_id, '0', '基本上不太有鱼刺');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"运动裤","normal":"牛仔裤"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种裤子'),
(@last_id, '0', '服装店里可以买'),
(@last_id, '0', '几乎每个人都有'),
(@last_id, '0', '男女都能穿'),
(@last_id, '0', '没有年龄限制'),
(@last_id, '0', '牢固度很好'),
(@last_id, '0', '可以穿很久'),
(@last_id, '0', '有人专门收藏'),
(@last_id, '0', '外出休闲的时候常穿'),
(@last_id, '0', '有很多知名品牌');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"警察","normal":"法官"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '维护正义'),
(@last_id, '0', '一种职业'),
(@last_id, '0', '男女都有'),
(@last_id, '0', '男性居多'),
(@last_id, '0', '越老越权威'),
(@last_id, '0', '高危职业'),
(@last_id, '0', '不苟言笑'),
(@last_id, '0', '公务员'),
(@last_id, '0', '很严肃'),
(@last_id, '0', '有制服');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"伏羲","normal":"神农"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '神话人物'),
(@last_id, '0', '教人医疗与农耕'),
(@last_id, '0', '汉族'),
(@last_id, '0', '华夏民族人文先始'),
(@last_id, '0', '创世神'),
(@last_id, '0', '并非真实存在'),
(@last_id, '0', '创造占卜八卦'),
(@last_id, '0', '创造文字'),
(@last_id, '0', '尝百草'),
(@last_id, '0', '上古时期');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"篮球鞋","normal":"跑步鞋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种有专门用途的鞋'),
(@last_id, '0', '耐克、阿迪、乔丹'),
(@last_id, '0', '运动鞋的一种'),
(@last_id, '0', '各大运动品牌都有'),
(@last_id, '0', '很专业'),
(@last_id, '0', '不同的脚型有不同的款式'),
(@last_id, '0', '很贵'),
(@last_id, '0', '特定运动项目才能穿'),
(@last_id, '0', '有使用寿命'),
(@last_id, '0', '有缓冲、减震等不同功能');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"杂技","normal":"魔术"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种表演'),
(@last_id, '0', '可单人完成，也可多人完成'),
(@last_id, '0', '需要常年的练习'),
(@last_id, '0', '很容易失误'),
(@last_id, '0', '现场表演'),
(@last_id, '0', '有专门的学校'),
(@last_id, '0', '有危险性'),
(@last_id, '0', '小孩子很喜欢看'),
(@last_id, '0', '总让人有神奇的感觉'),
(@last_id, '0', '让人吃惊');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"白菜","normal":"包菜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种蔬菜'),
(@last_id, '0', '非常常见'),
(@last_id, '0', '一年四季都有'),
(@last_id, '0', '廉价'),
(@last_id, '0', '有很多品种'),
(@last_id, '0', '有很多吃法'),
(@last_id, '0', '绿色的'),
(@last_id, '0', '个头并不特别大'),
(@last_id, '0', '冬天吃的特别多'),
(@last_id, '0', '过去的冬储菜');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"锅包肉","normal":"溜肉段"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '东北传统名菜'),
(@last_id, '0', '菜名'),
(@last_id, '0', '分量足'),
(@last_id, '0', '猪肉是主料'),
(@last_id, '0', '味道很浓郁'),
(@last_id, '0', '猪里脊肉'),
(@last_id, '0', '要经过油炸'),
(@last_id, '0', '外酥里嫩'),
(@last_id, '0', '口味酸甜'),
(@last_id, '0', '口味鲜辣');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"游戏机","normal":"跳舞毯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '玩游戏用的'),
(@last_id, '0', '室内进行'),
(@last_id, '0', '不受天气和时间的限制'),
(@last_id, '0', '有音乐'),
(@last_id, '0', '是一种需要脑子的活动'),
(@last_id, '0', '主要用于娱乐'),
(@last_id, '0', '需要一块显示屏'),
(@last_id, '0', '用电的'),
(@last_id, '0', '和动漫产业相关'),
(@last_id, '0', '伴随80后的成长');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"杨桃","normal":"樱桃"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种水果'),
(@last_id, '0', '有点贵'),
(@last_id, '0', '季节性很强'),
(@last_id, '0', '颜色鲜艳'),
(@last_id, '0', '汁水丰富'),
(@last_id, '0', '进口的更好吃'),
(@last_id, '0', '名字第二个字是桃'),
(@last_id, '0', '鲜甜'),
(@last_id, '0', '形状很可爱'),
(@last_id, '0', '五角星');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"潘婷","normal":"清扬"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '洗发水'),
(@last_id, '0', '品牌'),
(@last_id, '0', '香味很好闻'),
(@last_id, '0', '很多广告'),
(@last_id, '0', '多位明星代言'),
(@last_id, '0', '宝洁'),
(@last_id, '0', '联合利华'),
(@last_id, '0', '无屑可击'),
(@last_id, '0', '有多个系列'),
(@last_id, '0', '有专门的男士和女士产品');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"CT扫描","normal":"X光透视"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种检查名称'),
(@last_id, '0', '高科技'),
(@last_id, '0', '有辐射'),
(@last_id, '0', '不能多做'),
(@last_id, '0', '有副作用'),
(@last_id, '0', '能看到肉眼看不到的东西'),
(@last_id, '0', '挺贵的'),
(@last_id, '0', '不能戴金属'),
(@last_id, '0', '通常都躺着'),
(@last_id, '0', '在医院里才有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"香菜","normal":"韭菜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '蔬菜'),
(@last_id, '0', '绿色'),
(@last_id, '0', '细长条'),
(@last_id, '0', '包饺子会用到'),
(@last_id, '0', '名字第二个字是菜'),
(@last_id, '0', '味道很独特'),
(@last_id, '0', '味道很重'),
(@last_id, '0', '不是所有人都吃得惯'),
(@last_id, '0', '可以涮火锅'),
(@last_id, '0', '一年四季都有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"水果","normal":"蔬菜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以吃的'),
(@last_id, '0', '可生吃也可加热食用'),
(@last_id, '0', '一个食物种类的统称'),
(@last_id, '0', '很常见'),
(@last_id, '0', '几乎每天都要吃'),
(@last_id, '0', '素的'),
(@last_id, '0', '可种植的'),
(@last_id, '0', '大多都长在土里'),
(@last_id, '0', '通常富含维生素和纤维'),
(@last_id, '0', '减肥的人吃的比较多'),
(@last_id, '0', '女生大多爱吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"匡威","normal":"回力"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '运动品牌'),
(@last_id, '0', '老牌'),
(@last_id, '0', '知名度极高'),
(@last_id, '0', '全球知名'),
(@last_id, '0', '鞋子'),
(@last_id, '0', '80后都穿过'),
(@last_id, '0', '价格适中'),
(@last_id, '0', '白色'),
(@last_id, '0', '时尚'),
(@last_id, '0', '运动鞋');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"香瓜","normal":"甜瓜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种水果'),
(@last_id, '0', '糖分很高'),
(@last_id, '0', '一种瓜'),
(@last_id, '0', '有季节性'),
(@last_id, '0', '口感香甜'),
(@last_id, '0', '新疆产的特别好吃'),
(@last_id, '0', '表皮光滑'),
(@last_id, '0', '圆的'),
(@last_id, '0', '个头不大'),
(@last_id, '0', '吃瓤');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奥黛丽赫本","normal":"玛丽莲梦露"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '演员的名字'),
(@last_id, '0', '非常有名的女演员'),
(@last_id, '0', '已经过世'),
(@last_id, '0', '优雅'),
(@last_id, '0', '性感'),
(@last_id, '0', '好莱坞'),
(@last_id, '0', '全世界影迷无数'),
(@last_id, '0', '罗马假日'),
(@last_id, '0', '气质女神'),
(@last_id, '0', '黑色');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"微博","normal":"人人"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '社交平台'),
(@last_id, '0', '自媒体'),
(@last_id, '0', '需要实名认证'),
(@last_id, '0', '需要账号'),
(@last_id, '0', '每个人都可以用'),
(@last_id, '0', '大家都在玩'),
(@last_id, '0', '可以聊天'),
(@last_id, '0', '可以发状态'),
(@last_id, '0', '可以发图片'),
(@last_id, '0', '可以交朋友');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"夸父逐日","normal":"愚公移山"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '神话传说'),
(@last_id, '0', '寓言故事'),
(@last_id, '0', '小学语文课本里有'),
(@last_id, '0', '主人公是男的'),
(@last_id, '0', '山海经'),
(@last_id, '0', '一个男人做一件特别不可能的事情'),
(@last_id, '0', '坚持和毅力'),
(@last_id, '0', '日复一日'),
(@last_id, '0', '一种精神'),
(@last_id, '0', '中国古代的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"垃圾桶","normal":"垃圾袋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '装垃圾用的'),
(@last_id, '0', '搞卫生的时候会用到'),
(@last_id, '0', '每户家庭里都有'),
(@last_id, '0', '每天都要用'),
(@last_id, '0', '办公室里都有'),
(@last_id, '0', '前两个字是垃圾'),
(@last_id, '0', '一种容器'),
(@last_id, '0', '价格通常不贵'),
(@last_id, '0', '有利于环保'),
(@last_id, '0', '塑料做的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"书桌","normal":"书柜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '每个人读书的时候都用过'),
(@last_id, '0', '放书的'),
(@last_id, '0', '书房里会有'),
(@last_id, '0', '木头材质的居多'),
(@last_id, '0', '每个人几乎都有'),
(@last_id, '0', '是一种家具'),
(@last_id, '0', '从古到今都有'),
(@last_id, '0', '有木头的也有竹子的'),
(@last_id, '0', '是有文化的象征'),
(@last_id, '0', '有很多隔断');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"生孩子","normal":"做手术"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '在医院里进行'),
(@last_id, '0', '必须医生在场才能完成'),
(@last_id, '0', '只有女人能做'),
(@last_id, '0', '会有生命危险'),
(@last_id, '0', '在手术室里进行'),
(@last_id, '0', '要很长时间'),
(@last_id, '0', '很疼'),
(@last_id, '0', '不一定都打麻药'),
(@last_id, '0', '有风险'),
(@last_id, '0', '当事人需要提前签字');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"最强大脑","normal":"一站到底"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '综艺节目'),
(@last_id, '0', '是一档电视节目'),
(@last_id, '0', '益智攻擂节目'),
(@last_id, '0', '科学竞技真人秀'),
(@last_id, '0', '江苏卫视'),
(@last_id, '0', '节目引进自德国'),
(@last_id, '0', '节目创意来自美国'),
(@last_id, '0', '从科学的角度来推进和展开'),
(@last_id, '0', '让科学流行起来'),
(@last_id, '0', '有悬念');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"炸酱面","normal":"打卤面"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国传统特色面食'),
(@last_id, '0', '山东鲁菜'),
(@last_id, '0', '中国十大面条之一'),
(@last_id, '0', '地方特色'),
(@last_id, '0', '特色小吃'),
(@last_id, '0', '可以做主食'),
(@last_id, '0', '流行于北方'),
(@last_id, '0', '有多个品种'),
(@last_id, '0', '是一种面条'),
(@last_id, '0', '味香浓厚');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"闽南话","normal":"广东话"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种地方方言'),
(@last_id, '0', '南方的一种方言'),
(@last_id, '0', '新加坡人也会说'),
(@last_id, '0', '马来西亚华人都会说'),
(@last_id, '0', '这种方言能唱歌'),
(@last_id, '0', '不太容易听得懂'),
(@last_id, '0', '前两个字是地名'),
(@last_id, '0', '第三个字是话'),
(@last_id, '0', '听起来软软的'),
(@last_id, '0', '曾经红遍大江南北');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"耳机","normal":"耳麦"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '戴在耳朵上使用的'),
(@last_id, '0', '听声音用的'),
(@last_id, '0', '专业级别的非常贵'),
(@last_id, '0', '第一个字是耳'),
(@last_id, '0', '有一个麦克风'),
(@last_id, '0', '不仅可以听还可以说'),
(@last_id, '0', '通常比较小巧'),
(@last_id, '0', '可随身携带'),
(@last_id, '0', '有有线和无线之分'),
(@last_id, '0', '有挂耳和头戴之分');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"氢气","normal":"氮气"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种稀有气体'),
(@last_id, '0', '可以冲气球'),
(@last_id, '0', '无色无味'),
(@last_id, '0', '极易燃烧'),
(@last_id, '0', '非常轻'),
(@last_id, '0', '密度最小'),
(@last_id, '0', '可用作防腐剂'),
(@last_id, '0', '主要用作还原剂'),
(@last_id, '0', '安全性不高'),
(@last_id, '0', '可用来治疗部分疾病');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"法官","normal":"法院"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '司法体系的重要组成部分'),
(@last_id, '0', '威严'),
(@last_id, '0', '一种职业'),
(@last_id, '0', '庄严肃穆'),
(@last_id, '0', '神圣'),
(@last_id, '0', '犯罪分子闻风丧胆'),
(@last_id, '0', '正义的象征'),
(@last_id, '0', '司法权的执行者'),
(@last_id, '0', '每个国家都有'),
(@last_id, '0', '每个城市都有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"安全套","normal":"安全帽"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '特定场合下使用'),
(@last_id, '0', '安全的保障'),
(@last_id, '0', '很容易买到'),
(@last_id, '0', '使用方便'),
(@last_id, '0', '男人用的比较多'),
(@last_id, '0', '黄色居多'),
(@last_id, '0', '被严格要求使用'),
(@last_id, '0', '前两个字是安全'),
(@last_id, '0', '塑料做的'),
(@last_id, '0', '圆形的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"索尼","normal":"松下"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '日本的企业'),
(@last_id, '0', '家电产品品牌'),
(@last_id, '0', '老牌企业'),
(@last_id, '0', '非常有名'),
(@last_id, '0', '生产电视机'),
(@last_id, '0', '生产游戏机的'),
(@last_id, '0', '生成相机的'),
(@last_id, '0', '民营企业'),
(@last_id, '0', '生成洗衣机的'),
(@last_id, '0', '之前生产影碟机');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"杂粮煎饼","normal":"鸡蛋灌饼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种饼'),
(@last_id, '0', '一种街边小吃'),
(@last_id, '0', '学校门口都会有'),
(@last_id, '0', '很便宜'),
(@last_id, '0', '要放鸡蛋'),
(@last_id, '0', '要趁热吃'),
(@last_id, '0', '早饭常常吃'),
(@last_id, '0', '地方特色小吃'),
(@last_id, '0', '用很少的油'),
(@last_id, '0', '饼皮包裹内馅儿');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"蔡依林","normal":"梁静茹"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女歌手'),
(@last_id, '0', '红了十几年了'),
(@last_id, '0', '实力唱将'),
(@last_id, '0', '研究很大'),
(@last_id, '0', '脸肉肉的'),
(@last_id, '0', '拼命三郎'),
(@last_id, '0', '体操女皇'),
(@last_id, '0', '舞娘'),
(@last_id, '0', '马拉西亚'),
(@last_id, '0', '宅男女神');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"土豆","normal":"洋芋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一般拳头大小'),
(@last_id, '0', '一种植物'),
(@last_id, '0', '可以吃的'),
(@last_id, '0', '黄色的'),
(@last_id, '0', '要削皮'),
(@last_id, '0', '可以和牛肉一起烧'),
(@last_id, '0', '产量很大'),
(@last_id, '0', '外国引进'),
(@last_id, '0', '能磨成粉'),
(@last_id, '0', '可以度过饥荒');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"天妇罗","normal":"炸虾"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食品'),
(@last_id, '0', '要用面'),
(@last_id, '0', '热量高'),
(@last_id, '0', '一道菜'),
(@last_id, '0', '油炸的'),
(@last_id, '0', '有肉'),
(@last_id, '0', '有蔬菜'),
(@last_id, '0', '有海鲜'),
(@last_id, '0', '在日本很流行'),
(@last_id, '0', '我从来没吃过');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"虚拟城市","normal":"虚拟人生"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一款游戏'),
(@last_id, '0', '你在游戏里起主导作用'),
(@last_id, '0', '模拟'),
(@last_id, '0', '需要很厉害的头脑才可以玩好'),
(@last_id, '0', '需要时间'),
(@last_id, '0', '是一种经营游戏'),
(@last_id, '0', '主要考察玩家的排列组合能力'),
(@last_id, '0', '我玩过'),
(@last_id, '0', '我没玩过'),
(@last_id, '0', '最早是在电脑上开始玩');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"天上人间","normal":"海天盛筵"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '对一般人来说很神秘'),
(@last_id, '0', '男生很喜欢'),
(@last_id, '0', '有很多漂亮的女人'),
(@last_id, '0', '被警察抓过'),
(@last_id, '0', '很多名人都会去的地方'),
(@last_id, '0', '是一个场所'),
(@last_id, '0', '一般人消费不起'),
(@last_id, '0', '在北京'),
(@last_id, '0', '在海南'),
(@last_id, '0', '黄赌毒');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"老师","normal":"师傅"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有一技之长'),
(@last_id, '0', '对人的称呼'),
(@last_id, '0', '是前辈'),
(@last_id, '0', '会教给我们东西'),
(@last_id, '0', '也指很有经验的人'),
(@last_id, '0', '学校里很多'),
(@last_id, '0', '会布置作业'),
(@last_id, '0', '教你一门手艺'),
(@last_id, '0', '你一辈子会遇到很多这样的人'),
(@last_id, '0', '大学毕业后就不常见了');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大叔","normal":"大伯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '长辈'),
(@last_id, '0', '一种称呼'),
(@last_id, '0', '跟爸爸有关系'),
(@last_id, '0', '跟爸爸可以没关系'),
(@last_id, '0', '在街上碰到陌生人也可以这么叫'),
(@last_id, '0', '比我年纪大'),
(@last_id, '0', '比爸爸年纪大'),
(@last_id, '0', '比爸爸年纪小'),
(@last_id, '0', '跟妈妈没关系');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"排球","normal":"棒球"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种器械'),
(@last_id, '0', '一种体育运动'),
(@last_id, '0', '团队合作'),
(@last_id, '0', '跟球有关'),
(@last_id, '0', '需要用手'),
(@last_id, '0', '日本拿过冠军'),
(@last_id, '0', '美国拿过冠军'),
(@last_id, '0', '男女都可以玩'),
(@last_id, '0', '有电视剧讲这个'),
(@last_id, '0', '有动画片讲这个');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"狼人杀","normal":"杀人游戏"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种游戏'),
(@last_id, '0', '有心机'),
(@last_id, '0', '看逻辑'),
(@last_id, '0', '人越多越好玩'),
(@last_id, '0', '一般要面对面玩'),
(@last_id, '0', '手机玩也可以'),
(@last_id, '0', '一方需要把另一方杀光'),
(@last_id, '0', '有法官'),
(@last_id, '0', '有遗言'),
(@last_id, '0', '可以验人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"美术","normal":"素描"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有才华'),
(@last_id, '0', '一种艺术'),
(@last_id, '0', '需要用笔'),
(@last_id, '0', '我就是干这行的'),
(@last_id, '0', '用到铅笔'),
(@last_id, '0', '用到橡皮'),
(@last_id, '0', '用到纸'),
(@last_id, '0', '街头有人靠这个谋生'),
(@last_id, '0', '一般人死后卖比较贵'),
(@last_id, '0', '需要用小刀');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"暖男","normal":"渣男"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '一种类型'),
(@last_id, '0', '女生喜欢'),
(@last_id, '0', '女生纯洁才会看上'),
(@last_id, '0', '两个字'),
(@last_id, '0', '这种男的会让人爱的死去活来'),
(@last_id, '0', '往往不能结婚'),
(@last_id, '0', '丈母娘都喜欢'),
(@last_id, '0', '跟有没有钱没关系'),
(@last_id, '0', '主要跟性格有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"烧饼","normal":"锅贴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '和豆浆一起吃最好了'),
(@last_id, '0', '一种小吃'),
(@last_id, '0', '用面做的'),
(@last_id, '0', '一般都是圆形'),
(@last_id, '0', '有馅儿'),
(@last_id, '0', '没有馅儿'),
(@last_id, '0', '要用炉子'),
(@last_id, '0', '贴起来的'),
(@last_id, '0', '烤着吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"铅笔","normal":"蜡笔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种文具'),
(@last_id, '0', '外国人发明的'),
(@last_id, '0', '有很多种型号'),
(@last_id, '0', '笔'),
(@last_id, '0', '越用越短'),
(@last_id, '0', '美术生经常用'),
(@last_id, '0', '可以用橡皮'),
(@last_id, '0', '不是很贵，买得起'),
(@last_id, '0', '现在一般人不太用了'),
(@last_id, '0', '有颜色');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"乡村爱情故事","normal":"北京爱情故事"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一部电视剧'),
(@last_id, '0', '有很多集'),
(@last_id, '0', '非常火'),
(@last_id, '0', '导演也是演员'),
(@last_id, '0', '讲的是北方的故事'),
(@last_id, '0', '是一部爱情片'),
(@last_id, '0', '非常贴近生活'),
(@last_id, '0', '很多演员都从里面火了'),
(@last_id, '0', '剧情很复杂'),
(@last_id, '0', '同时有很多男主和女主');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"玉米","normal":"高粱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '植物'),
(@last_id, '0', '可以生吃'),
(@last_id, '0', '主食'),
(@last_id, '0', '五谷杂粮'),
(@last_id, '0', '长的比较高，1米左右'),
(@last_id, '0', '需要收割'),
(@last_id, '0', '产量很大'),
(@last_id, '0', '能填饱肚子'),
(@last_id, '0', '可以磨成粉');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"作家","normal":"写手"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有才华'),
(@last_id, '0', '一种职业'),
(@last_id, '0', '郭敬明'),
(@last_id, '0', '于正'),
(@last_id, '0', '金庸'),
(@last_id, '0', '古龙'),
(@last_id, '0', '琼瑶'),
(@last_id, '0', '韩寒'),
(@last_id, '0', '网络上很多'),
(@last_id, '0', '抄袭很严重');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"雪糕","normal":"甜筒"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '甜的'),
(@last_id, '0', '夏天吃比较多'),
(@last_id, '0', '一般几块钱就可以买到'),
(@last_id, '0', '解暑'),
(@last_id, '0', '咬着吃'),
(@last_id, '0', '舔着吃'),
(@last_id, '0', '外面有包裹一层脆脆的'),
(@last_id, '0', '可以全部吃完'),
(@last_id, '0', '吃完后剩个棍子');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"游泳衣","normal":"沙滩裤"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种衣服'),
(@last_id, '0', '彩色'),
(@last_id, '0', '夏天穿的比较多'),
(@last_id, '0', '穿起来很清凉'),
(@last_id, '0', '一般海边、泳池边经常看到'),
(@last_id, '0', '可以看出别人的身材好不好'),
(@last_id, '0', '面料比较少'),
(@last_id, '0', '男生女生都可以穿'),
(@last_id, '0', '颜色比较多'),
(@last_id, '0', '我买得起');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"包子","normal":"烧卖"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '热的最好吃'),
(@last_id, '0', '一般早饭吃'),
(@last_id, '0', '面粉做的'),
(@last_id, '0', '有馅儿'),
(@last_id, '0', '我喜欢吃荤的'),
(@last_id, '0', '我喜欢吃素的'),
(@last_id, '0', '蒸着吃'),
(@last_id, '0', '一笼一笼卖'),
(@last_id, '0', '我今早刚吃过');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"陌陌","normal":"微信"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', 'app'),
(@last_id, '0', '一种社交工具'),
(@last_id, '0', '摇一摇'),
(@last_id, '0', '约炮工具'),
(@last_id, '0', '可以交到很多朋友'),
(@last_id, '0', '一种陌生人社交'),
(@last_id, '0', '有很多代购'),
(@last_id, '0', '头像都是假的'),
(@last_id, '0', '每个人手机里都有'),
(@last_id, '0', '可以转账');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"白细胞","normal":"红细胞"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '很小的'),
(@last_id, '0', '有生命的'),
(@last_id, '0', '在人和动物身上都有'),
(@last_id, '0', '是个好东西'),
(@last_id, '0', '在身体里'),
(@last_id, '0', '制造血液的'),
(@last_id, '0', '输送血液的'),
(@last_id, '0', '死亡后可以再生的'),
(@last_id, '0', '可以人造的'),
(@last_id, '0', '可以杀死细菌的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"犀牛","normal":"水牛"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种动物'),
(@last_id, '0', '喜欢在水里'),
(@last_id, '0', '吃草的'),
(@last_id, '0', '鸟会经常停在它背上'),
(@last_id, '0', '四条腿'),
(@last_id, '0', '蚊虫很喜欢'),
(@last_id, '0', '有角的'),
(@last_id, '0', '毛很细'),
(@last_id, '0', '一般不吃'),
(@last_id, '0', '可以吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"白金","normal":"白银"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '亮闪闪'),
(@last_id, '0', '一种物品'),
(@last_id, '0', '比较值钱'),
(@last_id, '0', '妈妈喜欢'),
(@last_id, '0', '可以用来骗人'),
(@last_id, '0', '身上有很多的话要被抢'),
(@last_id, '0', '白色的'),
(@last_id, '0', '购买要按克计算'),
(@last_id, '0', '期货里可以炒作'),
(@last_id, '0', '会贬值');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"黑头","normal":"毛孔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有洞洞'),
(@last_id, '0', '女生不喜欢'),
(@last_id, '0', '都希望变小一点'),
(@last_id, '0', '需要经常保养才不会变大'),
(@last_id, '0', '如果里面有东西出来，会很恶心'),
(@last_id, '0', '人体身上有'),
(@last_id, '0', '动物身上有'),
(@last_id, '0', '植物身上没有'),
(@last_id, '0', '需要工具'),
(@last_id, '0', '永远也弄不掉');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"猪肉脯","normal":"牛肉干"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '撕着吃'),
(@last_id, '0', '一种食物'),
(@last_id, '0', '零食'),
(@last_id, '0', '荤的'),
(@last_id, '0', '需要风干'),
(@last_id, '0', '需要用手拿着吃'),
(@last_id, '0', '有咸的有甜的也有辣的'),
(@last_id, '0', '一包几十块'),
(@last_id, '0', '有小包装的'),
(@last_id, '0', '切的很薄');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"红豆","normal":"绿豆"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '一种植物'),
(@last_id, '0', '小小的，称斤卖'),
(@last_id, '0', '烧熟了才好吃'),
(@last_id, '0', '可以煮粥'),
(@last_id, '0', '可以烧汤'),
(@last_id, '0', '一般都做甜的'),
(@last_id, '0', '可以跟饭一起煮'),
(@last_id, '0', '绿色的'),
(@last_id, '0', '红色的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"新华字典","normal":"英汉字典"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种工具'),
(@last_id, '0', '有实体版的'),
(@last_id, '0', '有电子版的'),
(@last_id, '0', '查询用的'),
(@last_id, '0', '学习的好帮手'),
(@last_id, '0', '有汉字'),
(@last_id, '0', '解释说明'),
(@last_id, '0', '很厚'),
(@last_id, '0', '很少有人从头到尾看一遍'),
(@last_id, '0', '考试用');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"三明治","normal":"白馒头"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '一般早餐吃'),
(@last_id, '0', '单独吃的话很干'),
(@last_id, '0', '面粉做的'),
(@last_id, '0', '要发酵'),
(@last_id, '0', '可以和酱料一起吃'),
(@last_id, '0', '可以夹东西吃'),
(@last_id, '0', '甜的咸的都好吃'),
(@last_id, '0', '中国的'),
(@last_id, '0', '外国的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"公交","normal":"地铁"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以容纳很多人'),
(@last_id, '0', '一种交通工具'),
(@last_id, '0', '网络很发达'),
(@last_id, '0', '可以刷卡'),
(@last_id, '0', '可以投币'),
(@last_id, '0', '有一个司机'),
(@last_id, '0', '需要买票'),
(@last_id, '0', '普通人都坐得起'),
(@last_id, '0', '速度很快'),
(@last_id, '0', '在城市里跑');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"HelloKitty","normal":"狮子王"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '动画片'),
(@last_id, '0', '跟动物有关'),
(@last_id, '0', '美国的'),
(@last_id, '0', '日本的'),
(@last_id, '0', '萌萌哒'),
(@last_id, '0', '很励志'),
(@last_id, '0', '是上个世纪的人物形象'),
(@last_id, '0', '现在还有很多人喜欢'),
(@last_id, '0', '狮子'),
(@last_id, '0', '猫');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"老干妈","normal":"豆瓣酱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '红色的'),
(@last_id, '0', '吃的'),
(@last_id, '0', '植物'),
(@last_id, '0', '一般不单独吃'),
(@last_id, '0', '下饭吃'),
(@last_id, '0', '宅男女神'),
(@last_id, '0', '咸的'),
(@last_id, '0', '辣的'),
(@last_id, '0', '中国人吃的很多'),
(@last_id, '0', '外国人不太吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"妖怪","normal":"魔鬼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '所有负面词的代言'),
(@last_id, '0', '神话里'),
(@last_id, '0', '一种形象'),
(@last_id, '0', '中国的神话里很多'),
(@last_id, '0', '外国的神话里很多'),
(@last_id, '0', '一种负面形象'),
(@last_id, '0', '一般都是坏的'),
(@last_id, '0', '金角大王'),
(@last_id, '0', '银角大王'),
(@last_id, '0', '蜘蛛精');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"薄荷糖","normal":"润喉糖"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '草本的'),
(@last_id, '0', '甜的'),
(@last_id, '0', '凉的'),
(@last_id, '0', '去除口气的'),
(@last_id, '0', '吃的'),
(@last_id, '0', '对喉咙好'),
(@last_id, '0', '一般都是植物做的'),
(@last_id, '0', '超市里就有卖'),
(@last_id, '0', '牌子有很多种'),
(@last_id, '0', '对身体好');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"胡适","normal":"鲁迅"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '名人'),
(@last_id, '0', '男人'),
(@last_id, '0', '作家'),
(@last_id, '0', '民国时代的'),
(@last_id, '0', '做过老师'),
(@last_id, '0', '文笔很好'),
(@last_id, '0', '有观点'),
(@last_id, '0', '活到了建国后'),
(@last_id, '0', '不是党员'),
(@last_id, '0', '是教授');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"黑胡椒","normal":"咖喱粉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '味道很冲'),
(@last_id, '0', '植物'),
(@last_id, '0', '一种调味剂'),
(@last_id, '0', '外国人吃的比较多'),
(@last_id, '0', '辣的'),
(@last_id, '0', '吃牛肉可以用到'),
(@last_id, '0', '吃饭可以用到'),
(@last_id, '0', '泰国'),
(@last_id, '0', '美国');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"织女","normal":"牛郎"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '神话故事'),
(@last_id, '0', '中国的'),
(@last_id, '0', '有节日庆祝'),
(@last_id, '0', '七夕节'),
(@last_id, '0', '鹊桥'),
(@last_id, '0', '爱情故事'),
(@last_id, '0', '一年见一次'),
(@last_id, '0', '王母娘娘'),
(@last_id, '0', '一个贫穷男与富家女的故事'),
(@last_id, '0', '浪漫');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"说话","normal":"唱歌"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '人的一种技能'),
(@last_id, '0', '要用到人身上的器官'),
(@last_id, '0', '张开才可以'),
(@last_id, '0', '可以让人很动情'),
(@last_id, '0', '可以让人很讨厌'),
(@last_id, '0', '我这方面不是很厉害'),
(@last_id, '0', '没有舌头就不能做'),
(@last_id, '0', '这是一门技术活'),
(@last_id, '0', '在中国有很多这方面的比赛'),
(@last_id, '0', '高晓松可以当评委');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"床单","normal":"床垫"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '卧室里有'),
(@last_id, '0', '家里用的'),
(@last_id, '0', '床上用的'),
(@last_id, '0', '经常要洗的'),
(@last_id, '0', '压在身下的'),
(@last_id, '0', '有很多花纹的'),
(@last_id, '0', '很厚的'),
(@last_id, '0', '很薄的'),
(@last_id, '0', '很朴素的'),
(@last_id, '0', '不常洗的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"康师傅方便面","normal":"统一方便面"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃的'),
(@last_id, '0', '可以快速吃完的'),
(@last_id, '0', '要用到开水的'),
(@last_id, '0', '要用到叉子或者筷子'),
(@last_id, '0', '日本流传过来的'),
(@last_id, '0', '中国的牌子'),
(@last_id, '0', '不健康'),
(@last_id, '0', '有酱料包'),
(@last_id, '0', '有面'),
(@last_id, '0', '有肉');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"杀鸡取卵","normal":"杀鸡儆猴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟动物有关'),
(@last_id, '0', '一个成语'),
(@last_id, '0', '不是正面的成语'),
(@last_id, '0', '跟鸡过不去'),
(@last_id, '0', '有典故的'),
(@last_id, '0', '战国时期的故事'),
(@last_id, '0', '鸡是被冤枉的'),
(@last_id, '0', '人不能贪心'),
(@last_id, '0', '外国的故事'),
(@last_id, '0', '都是为了利益');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"宝马","normal":"路虎"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '现在变街车了'),
(@last_id, '0', '越野能力很强'),
(@last_id, '0', '德国的'),
(@last_id, '0', '英国的'),
(@last_id, '0', '比较贵'),
(@last_id, '0', '男生开比较多'),
(@last_id, '0', '女生开很霸气'),
(@last_id, '0', '车上的音响系统特别好'),
(@last_id, '0', '每个男人都想拥有它'),
(@last_id, '0', '车名跟动物有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"QQ管家","normal":"金山卫士"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电脑软件'),
(@last_id, '0', '杀毒'),
(@last_id, '0', '有多样化的功能'),
(@last_id, '0', '安全'),
(@last_id, '0', '不是360'),
(@last_id, '0', '也有手机版本的'),
(@last_id, '0', 'logo像盾牌'),
(@last_id, '0', '四个字'),
(@last_id, '0', '其实不安装也没关系的'),
(@last_id, '0', '不是鲁大师');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"时钟","normal":"手表"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '记时'),
(@last_id, '0', '时间'),
(@last_id, '0', '滴答滴答'),
(@last_id, '0', '刻有数字'),
(@last_id, '0', '有的款式没数字'),
(@last_id, '0', '也可以作为装饰品'),
(@last_id, '0', '名牌的可以很贵'),
(@last_id, '0', '没有链子'),
(@last_id, '0', '大小尺寸不一'),
(@last_id, '0', '戴在手上的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"狡诈","normal":"虚伪"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种品质'),
(@last_id, '0', '不咋地'),
(@last_id, '0', '灰太狼'),
(@last_id, '0', '狐狸'),
(@last_id, '0', '不实诚'),
(@last_id, '0', '骗'),
(@last_id, '0', '我不是这样的人'),
(@last_id, '0', '一般反面人物都是这样的'),
(@last_id, '0', '容嬷嬷'),
(@last_id, '0', '四大恶人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"柚子茶","normal":"柠檬水"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '和水果有关'),
(@last_id, '0', '酸'),
(@last_id, '0', '喝的'),
(@last_id, '0', '不能空腹喝'),
(@last_id, '0', '冷热都可以'),
(@last_id, '0', '夏天喝的比较多'),
(@last_id, '0', '可以切片'),
(@last_id, '0', '可以榨汁'),
(@last_id, '0', '饮料'),
(@last_id, '0', '餐厅和超市都有卖');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"空手道","normal":"跆拳道"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '体育运动'),
(@last_id, '0', '武术的一种'),
(@last_id, '0', '穿白色的运动服'),
(@last_id, '0', '做的时候会叫喊'),
(@last_id, '0', '搏击类'),
(@last_id, '0', '有腰带'),
(@last_id, '0', '日本比较厉害'),
(@last_id, '0', '韩国比较厉害'),
(@last_id, '0', '用手'),
(@last_id, '0', '用脚');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"卫生纸","normal":"心相印"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '擦'),
(@last_id, '0', '讲卫生'),
(@last_id, '0', '是一种纸'),
(@last_id, '0', '不能用来书写'),
(@last_id, '0', '薄薄的'),
(@last_id, '0', '人人都买得起'),
(@last_id, '0', '一包一包的'),
(@last_id, '0', '厨房用'),
(@last_id, '0', '厕所用'),
(@last_id, '0', '有牌子');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"足球","normal":"排球"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '运动'),
(@last_id, '0', '多人参加'),
(@last_id, '0', '两方对垒'),
(@last_id, '0', '可看性高'),
(@last_id, '0', '两个字'),
(@last_id, '0', '精彩'),
(@last_id, '0', '只能用手'),
(@last_id, '0', '只能用脚'),
(@last_id, '0', '球类运动'),
(@last_id, '0', '日本在亚洲也挺厉害的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"乌龟","normal":"甲鱼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '带壳的'),
(@last_id, '0', '长寿'),
(@last_id, '0', '乌漆嘛黑的'),
(@last_id, '0', '水陆两栖'),
(@last_id, '0', '一种动物'),
(@last_id, '0', '可以吃'),
(@last_id, '0', '很补'),
(@last_id, '0', '一般都是冬天吃的'),
(@last_id, '0', '有四个脚'),
(@last_id, '0', '有尾巴');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"葡萄酒","normal":"酸梅汤"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '饮料'),
(@last_id, '0', '红色的'),
(@last_id, '0', '酸酸甜甜'),
(@last_id, '0', '果子'),
(@last_id, '0', '不能喝太多'),
(@last_id, '0', '解暑的'),
(@last_id, '0', '晚上喝比较好'),
(@last_id, '0', '古代开始就有人喝了'),
(@last_id, '0', '李白为这个写过诗'),
(@last_id, '0', '要用植物');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"广东","normal":"广西"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '省份'),
(@last_id, '0', '南方'),
(@last_id, '0', '潮湿'),
(@last_id, '0', '临海'),
(@last_id, '0', '名字跟方位有关'),
(@last_id, '0', '口音听不懂'),
(@last_id, '0', '吃的比较杂'),
(@last_id, '0', '唱歌很好听'),
(@last_id, '0', '跟香港很近'),
(@last_id, '0', '东西很好吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"冯小刚","normal":"陈凯歌"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '导演'),
(@last_id, '0', '男性'),
(@last_id, '0', '贺岁片'),
(@last_id, '0', '葛优'),
(@last_id, '0', '自己也演戏'),
(@last_id, '0', '儿子特别帅'),
(@last_id, '0', '最近经常出现在选秀节目中'),
(@last_id, '0', '年纪比较大'),
(@last_id, '0', '拿过很多奖'),
(@last_id, '0', '最近有撕逼大戏');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"孟飞","normal":"乐嘉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '光头'),
(@last_id, '0', '节目主持人'),
(@last_id, '0', '性格测试'),
(@last_id, '0', '非常勿扰'),
(@last_id, '0', '男的'),
(@last_id, '0', '金刚芭比'),
(@last_id, '0', '大家会叫他爷爷'),
(@last_id, '0', '情感专家'),
(@last_id, '0', '开了面馆'),
(@last_id, '0', '知名人士');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"罗志祥","normal":"林俊杰"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男艺人'),
(@last_id, '0', '歌星'),
(@last_id, '0', '台湾歌手'),
(@last_id, '0', '出过国'),
(@last_id, '0', '喜欢hebe'),
(@last_id, '0', '还演过偶像剧'),
(@last_id, '0', '拿过奖'),
(@last_id, '0', '新加坡'),
(@last_id, '0', '被称为天王');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"华山","normal":"恒山"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '景点'),
(@last_id, '0', '五岳之一'),
(@last_id, '0', '很高'),
(@last_id, '0', '从来没去过'),
(@last_id, '0', '国家一级风景名胜区'),
(@last_id, '0', '以险著称'),
(@last_id, '0', '在北方'),
(@last_id, '0', '山'),
(@last_id, '0', '旅游胜地'),
(@last_id, '0', '金庸武侠剧里曾有一幕与它有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鸡米花","normal":"爆米花"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '看电影会吃'),
(@last_id, '0', '不是很贵'),
(@last_id, '0', '一颗一颗的'),
(@last_id, '0', '有用纸盒装，也有用纸桶的'),
(@last_id, '0', '油炸的'),
(@last_id, '0', '美国人很爱看'),
(@last_id, '0', '小孩子爱吃'),
(@last_id, '0', '容易发胖'),
(@last_id, '0', '食物'),
(@last_id, '0', '需要高温烹制');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"烧鸭","normal":"烧鸡"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '广东人喜欢吃'),
(@last_id, '0', '家禽'),
(@last_id, '0', '食物'),
(@last_id, '0', '很油腻'),
(@last_id, '0', '茶餐厅里基本都有'),
(@last_id, '0', '一般要蘸酱吃'),
(@last_id, '0', '两条腿的动物'),
(@last_id, '0', '南宁人很爱吃'),
(@last_id, '0', '很美味，受广大民众喜爱'),
(@last_id, '0', '外面看起来是黑色的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"酒后驾车","normal":"醉酒驾车"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '违法乱纪的事'),
(@last_id, '0', '有生命危险'),
(@last_id, '0', '不能做这件事'),
(@last_id, '0', '警察经常在路边查'),
(@last_id, '0', '要进局子的'),
(@last_id, '0', '电视上经常宣传杜绝此类事件'),
(@last_id, '0', '一般以男性为主'),
(@last_id, '0', '跟开车有关'),
(@last_id, '0', '喝酒后干的一件事'),
(@last_id, '0', '很多交通事故都与它有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"贵妃","normal":"皇后"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女人'),
(@last_id, '0', '一种身份的象征'),
(@last_id, '0', '皇帝的人'),
(@last_id, '0', '古代的称呼，现在没人这么叫'),
(@last_id, '0', '身份尊贵'),
(@last_id, '0', '宫斗里经常出现'),
(@last_id, '0', '一般都心狠手辣'),
(@last_id, '0', '皇帝的配偶'),
(@last_id, '0', '很有权力'),
(@last_id, '0', '特别受皇帝宠爱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"菠萝","normal":"榴莲"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '水果'),
(@last_id, '0', '需要去皮吃'),
(@last_id, '0', '皮很硬'),
(@last_id, '0', '黄色的'),
(@last_id, '0', '一般生长在热带'),
(@last_id, '0', '很多人都讨厌吃'),
(@last_id, '0', '吃多了容易上火'),
(@last_id, '0', '对女性有好处的水果'),
(@last_id, '0', '是舶来品，中国早期没有种植'),
(@last_id, '0', '我不喜欢吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"创始人","normal":"CEO"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '马云、马化腾这种身份'),
(@last_id, '0', '公司一把手'),
(@last_id, '0', '大老板'),
(@last_id, '0', '一般都很有钱'),
(@last_id, '0', '出门带保镖'),
(@last_id, '0', '很厉害的人'),
(@last_id, '0', '有极高的认识任免权'),
(@last_id, '0', '员工都很怕他'),
(@last_id, '0', '大多是男性身份'),
(@last_id, '0', '职务的名称');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小兵张嘎","normal":"王二小"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '小孩'),
(@last_id, '0', '革命时期的人'),
(@last_id, '0', '语文课本上出现过'),
(@last_id, '0', '是个男孩'),
(@last_id, '0', '抗日英雄'),
(@last_id, '0', '算是个名人'),
(@last_id, '0', '帮助八路军消灭鬼子'),
(@last_id, '0', '年纪在十三岁左右'),
(@last_id, '0', '河北人'),
(@last_id, '0', '已经被拍成影视作品');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"娘炮","normal":"伪娘"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '形容男生很像女的'),
(@last_id, '0', '某些动作不够man'),
(@last_id, '0', '缺少阳刚之气'),
(@last_id, '0', '男的打扮成女人'),
(@last_id, '0', '动漫里有很多这种角色'),
(@last_id, '0', '娘娘腔'),
(@last_id, '0', '奶油味十足'),
(@last_id, '0', '很难找到女朋友'),
(@last_id, '0', '男同性恋里可能有这样的'),
(@last_id, '0', 'ladyboy');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"保安","normal":"保镖"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的为主'),
(@last_id, '0', '领导人身边会带很多这种人'),
(@last_id, '0', '保护安全的人'),
(@last_id, '0', '一种工作称谓'),
(@last_id, '0', '明星出行都会带'),
(@last_id, '0', '一般都穿黑衣服'),
(@last_id, '0', '印象中一直戴着墨镜'),
(@last_id, '0', '公司里都会有'),
(@last_id, '0', '对身体素质要求比较高'),
(@last_id, '0', '能力强');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"天才","normal":"人才"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '智商很高'),
(@last_id, '0', '比普通人更快的接收新事物'),
(@last_id, '0', '专业技能很高'),
(@last_id, '0', '可以连续跳级'),
(@last_id, '0', '像爱因斯坦这种人'),
(@last_id, '0', '很有天赋'),
(@last_id, '0', '比其他人的智商都要高'),
(@last_id, '0', '小脑发达'),
(@last_id, '0', '三才之一'),
(@last_id, '0', '莫扎特');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"丘吉尔","normal":"罗斯福"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '外国老头'),
(@last_id, '0', '已经去世'),
(@last_id, '0', '国家统治人'),
(@last_id, '0', '人名'),
(@last_id, '0', '万人之上，没有之下'),
(@last_id, '0', '推行新政'),
(@last_id, '0', '曾经连任'),
(@last_id, '0', '在任期间对国家发展起到很重要的推动作用'),
(@last_id, '0', '政治家'),
(@last_id, '0', '与世界大战有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"英雄联盟","normal":"穿越火线"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '游戏'),
(@last_id, '0', '男生很爱玩'),
(@last_id, '0', '可以单机玩'),
(@last_id, '0', '国外游戏团队开发'),
(@last_id, '0', '对战游戏'),
(@last_id, '0', '经常喝朋友一起玩'),
(@last_id, '0', '人多更好玩'),
(@last_id, '0', '里面很多女生也在玩'),
(@last_id, '0', '有很多角色可以选择');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"动物园","normal":"马戏团"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '动物'),
(@last_id, '0', '很多人喜欢'),
(@last_id, '0', '要花钱看'),
(@last_id, '0', '很多地方都有'),
(@last_id, '0', '一定会有老虎、狮子等'),
(@last_id, '0', '小时候去过'),
(@last_id, '0', '供人观赏'),
(@last_id, '0', '一般是家长带小孩去'),
(@last_id, '0', '有专业的饲养员'),
(@last_id, '0', '动物会被关起来');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"范特西","normal":"心太软"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '台湾歌手唱过的一首歌'),
(@last_id, '0', '专辑名称'),
(@last_id, '0', '发行很多年'),
(@last_id, '0', '很好听，很喜欢'),
(@last_id, '0', '耳熟能详的'),
(@last_id, '0', '天王级别的早期作品'),
(@last_id, '0', '风靡大街小巷'),
(@last_id, '0', '有十多年的历史了'),
(@last_id, '0', '男歌手'),
(@last_id, '0', '情歌');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"年终奖","normal":"压岁钱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '年底才有'),
(@last_id, '0', '过年才有'),
(@last_id, '0', '别人给的'),
(@last_id, '0', '带有祝福的寓意'),
(@last_id, '0', '特定年纪的人才能收到'),
(@last_id, '0', '不是所有人过年都会有'),
(@last_id, '0', '跟钱有关'),
(@last_id, '0', '跟数字有关'),
(@last_id, '0', '数目每年都不固定'),
(@last_id, '0', '有的人多，有的人少');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"粉笔","normal":"彩笔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以写字'),
(@last_id, '0', '笔的一种'),
(@last_id, '0', '小孩经常要用'),
(@last_id, '0', '孩子们很喜欢'),
(@last_id, '0', '学校里会有'),
(@last_id, '0', '写字、画画都能用'),
(@last_id, '0', '有很多颜色'),
(@last_id, '0', '老师的教书神器'),
(@last_id, '0', '不能吃'),
(@last_id, '0', '含有氧化钙');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"铁观音","normal":"碧螺春"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '茶叶'),
(@last_id, '0', '绿色的'),
(@last_id, '0', '某个品种的名字'),
(@last_id, '0', '南方盛产'),
(@last_id, '0', '中国传统名茶'),
(@last_id, '0', '具有养生保健功能'),
(@last_id, '0', '唐朝时期是贡品'),
(@last_id, '0', '很刮油，吃多了有你可以喝一杯'),
(@last_id, '0', '有1000年的历史了'),
(@last_id, '0', '爸爸很爱喝');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"欧元","normal":"美元"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以买东西'),
(@last_id, '0', '流通货币'),
(@last_id, '0', '在中国没办法拿来买东西'),
(@last_id, '0', '面值超过100'),
(@last_id, '0', '等值面额的都比人民币值钱'),
(@last_id, '0', '出国要用'),
(@last_id, '0', '最近汇率跌了'),
(@last_id, '0', '国际货币'),
(@last_id, '0', '要去银行换才有'),
(@last_id, '0', '钱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"美容","normal":"美发"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '能把人变漂亮'),
(@last_id, '0', '需要花钱'),
(@last_id, '0', '消费群体以女性为主'),
(@last_id, '0', '经常怂恿你办卡'),
(@last_id, '0', '跟头部有关'),
(@last_id, '0', '与时尚有关'),
(@last_id, '0', '沙龙'),
(@last_id, '0', '发廊'),
(@last_id, '0', '爱美人士常去场所');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"搜狐","normal":"雅虎"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '搜索引擎'),
(@last_id, '0', '联想到中关村'),
(@last_id, '0', '跟张朝阳有关'),
(@last_id, '0', '看视频的'),
(@last_id, '0', '感觉现在没多少人在用'),
(@last_id, '0', '互联网产物'),
(@last_id, '0', '我也曾注册过邮箱'),
(@last_id, '0', '总部在美国'),
(@last_id, '0', '中国人创办'),
(@last_id, '0', '经常用它的搜索功能');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小姐","normal":"嫩模"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女的'),
(@last_id, '0', '工作时间为晚上为主'),
(@last_id, '0', '服务行业'),
(@last_id, '0', '带有贬意'),
(@last_id, '0', '一般女生都很讨厌别人这么叫自己'),
(@last_id, '0', '前提长的要好看'),
(@last_id, '0', '身材好'),
(@last_id, '0', '男人都喜欢'),
(@last_id, '0', '一般都很年轻'),
(@last_id, '0', '挺赚钱的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"保温瓶","normal":"暖水瓶"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '冬天必备'),
(@last_id, '0', '里面有开水'),
(@last_id, '0', '女孩子来大姨妈要用'),
(@last_id, '0', '装水的容器'),
(@last_id, '0', '不容易变冷'),
(@last_id, '0', '能给开水保持热温'),
(@last_id, '0', '真空的'),
(@last_id, '0', '一个生活用品'),
(@last_id, '0', '家家户户都有'),
(@last_id, '0', '密封性强');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"微信","normal":"支付宝"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '转账必备'),
(@last_id, '0', '里面有很多好友'),
(@last_id, '0', 'app 名称'),
(@last_id, '0', '马爸爸家的产品'),
(@last_id, '0', '可以看好友动态'),
(@last_id, '0', '互联网产品'),
(@last_id, '0', '聊天软件'),
(@last_id, '0', '抢红包'),
(@last_id, '0', '扫一扫'),
(@last_id, '0', '摇一摇');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"日光灯","normal":"节能灯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '日用品'),
(@last_id, '0', '要用电'),
(@last_id, '0', '电灯泡的一种'),
(@last_id, '0', '照明设备'),
(@last_id, '0', '家家必备物品'),
(@last_id, '0', '有开关控制'),
(@last_id, '0', '晚上一定要用的'),
(@last_id, '0', '可以给我们带来光明'),
(@last_id, '0', '小小的一个，有玻璃外罩'),
(@last_id, '0', '一般挂在天花板上');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"胡萝卜","normal":"大白菜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '蔬菜'),
(@last_id, '0', '兔子爱吃的'),
(@last_id, '0', '便宜'),
(@last_id, '0', '冬天上市，口感最佳'),
(@last_id, '0', '有大有小'),
(@last_id, '0', '原产于中国'),
(@last_id, '0', '麻辣烫里必点'),
(@last_id, '0', '减肥的人很爱吃'),
(@last_id, '0', '没钱的时候只好吃它了'),
(@last_id, '0', '很有营养价值');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大理","normal":"丽江"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟云南有关'),
(@last_id, '0', '旅游城市'),
(@last_id, '0', '《心花怒放》'),
(@last_id, '0', '古镇繁华'),
(@last_id, '0', '少数民族很多'),
(@last_id, '0', '一路向西唱的就是这里'),
(@last_id, '0', '天龙八部在这里拍摄取景'),
(@last_id, '0', '山清水秀，特别美丽'),
(@last_id, '0', '鲜花饼'),
(@last_id, '0', '四季如春');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"象棋","normal":"军棋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '需要两个人玩'),
(@last_id, '0', '对战类'),
(@last_id, '0', '棋类益智游戏'),
(@last_id, '0', '高档木材制作而成'),
(@last_id, '0', '很早之前就有了'),
(@last_id, '0', '把对方关键棋子吃掉就是胜利'),
(@last_id, '0', '可明玩，也可暗玩'),
(@last_id, '0', '小棋会被大棋吃'),
(@last_id, '0', '我不会玩'),
(@last_id, '0', '老人家很喜欢玩这个');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"胶水","normal":"胶带"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以粘住东西'),
(@last_id, '0', '文具店有卖'),
(@last_id, '0', '美工课上会用到'),
(@last_id, '0', '起到固定作用'),
(@last_id, '0', '一定要和其他东西放在一起才能产生作用'),
(@last_id, '0', '只能用在固体上，不能用于液体'),
(@last_id, '0', '没有颜色'),
(@last_id, '0', '一种粘合物'),
(@last_id, '0', '弄到手上很难洗'),
(@last_id, '0', '不能食用');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"耍无赖","normal":"耍流氓"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男人对女人做的一件事情'),
(@last_id, '0', '违法乱纪的事情'),
(@last_id, '0', '流氓才这么做'),
(@last_id, '0', '对一种行为的描述'),
(@last_id, '0', '调戏良家妇女'),
(@last_id, '0', '无赖手段'),
(@last_id, '0', '正常人干不出这事'),
(@last_id, '0', '行为恶劣'),
(@last_id, '0', '事态严重的话会被警察请去喝茶'),
(@last_id, '0', '吃饭不付钱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"激活","normal":"脉动"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '饮料'),
(@last_id, '0', '液体'),
(@last_id, '0', '可以喝的'),
(@last_id, '0', '口味好喝，水果味'),
(@last_id, '0', '有很多味道可以选择'),
(@last_id, '0', '电视上经常做广告'),
(@last_id, '0', '功能性饮料'),
(@last_id, '0', '娃哈哈'),
(@last_id, '0', '喝了能补充体力'),
(@last_id, '0', '运动饮料');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"沙琪玛","normal":"威化饼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以吃的'),
(@last_id, '0', '零食'),
(@last_id, '0', '饼干类'),
(@last_id, '0', '很甜'),
(@last_id, '0', '长方体的'),
(@last_id, '0', '传统特色糕点'),
(@last_id, '0', '历史源远'),
(@last_id, '0', '吃起来脆脆的'),
(@last_id, '0', '热量很高'),
(@last_id, '0', '入口即化');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"青椒","normal":"辣椒"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '食物'),
(@last_id, '0', '绿色的'),
(@last_id, '0', '四川人很喜欢'),
(@last_id, '0', '辣'),
(@last_id, '0', '蜡笔小新有句口头禅里就有它'),
(@last_id, '0', '吃多了容易上火'),
(@last_id, '0', '生吃熟吃都可以'),
(@last_id, '0', '我特别爱吃'),
(@last_id, '0', '江浙沪的人一般不吃'),
(@last_id, '0', '吃火锅一定要有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"可爱多","normal":"哈根达斯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '冰淇淋品牌'),
(@last_id, '0', '夏天必备'),
(@last_id, '0', '很多口味可以选择'),
(@last_id, '0', '可以在店里吃也可以带走吃'),
(@last_id, '0', '不是特别便宜'),
(@last_id, '0', '差不多要两个球'),
(@last_id, '0', '知名度很高'),
(@last_id, '0', '还拍了系列微电影'),
(@last_id, '0', '夏天经常吃'),
(@last_id, '0', '会买很多放在家里冰箱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"电风扇","normal":"空调"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电器'),
(@last_id, '0', '夏天必备'),
(@last_id, '0', '能带来凉爽'),
(@last_id, '0', '没有它的夏天将会活不下去'),
(@last_id, '0', '基本家家都有'),
(@last_id, '0', '离不开电'),
(@last_id, '0', '这个东西有大有小'),
(@last_id, '0', '可以落地，也可以安装在墙上'),
(@last_id, '0', '可以制造风'),
(@last_id, '0', '分强、中、弱档');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"吊带","normal":"肚兜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '衣服'),
(@last_id, '0', '布料很少'),
(@last_id, '0', '一般不能单穿'),
(@last_id, '0', '女人的'),
(@last_id, '0', '有肩带'),
(@last_id, '0', '穿在最里面的'),
(@last_id, '0', '如今也有人开始单独穿了'),
(@last_id, '0', '贴身内衣'),
(@last_id, '0', '穿上很性感'),
(@last_id, '0', '一年四季都能穿');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"星际争霸","normal":"魔兽争霸"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '游戏'),
(@last_id, '0', '男生很爱玩'),
(@last_id, '0', '淡季也可以玩'),
(@last_id, '0', '国外游戏团队开发'),
(@last_id, '0', '对战游戏'),
(@last_id, '0', '经常和朋友一起玩'),
(@last_id, '0', '一定要人多才玩得带劲'),
(@last_id, '0', '里面很多女生也在玩'),
(@last_id, '0', '有很多角色可以选择'),
(@last_id, '0', '玩这个游戏很费钱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"专家","normal":"教授"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '在学术、技艺等方面有专门技能'),
(@last_id, '0', '特别精通某一学科'),
(@last_id, '0', '对某项技艺有较高造诣'),
(@last_id, '0', '可以教导别人'),
(@last_id, '0', '专业知识全面的人'),
(@last_id, '0', '他们一般在各种大学里'),
(@last_id, '0', '有个NBA球星叫这个名字'),
(@last_id, '0', '搞科研的'),
(@last_id, '0', '现在也有了另一层的贬意'),
(@last_id, '0', '大家都很相信他们的言论');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"篮球鞋","normal":"跑步鞋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '鞋子'),
(@last_id, '0', '明星爱穿'),
(@last_id, '0', '功能性很明确'),
(@last_id, '0', '跑步的时候会穿'),
(@last_id, '0', '打球的时候很串'),
(@last_id, '0', '我有一双耐克的'),
(@last_id, '0', '有很多以明星名字命名的'),
(@last_id, '0', '运动性能的'),
(@last_id, '0', '穿上能够保护腿足和膝盖');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"亚瑟士","normal":"NB纽巴伦"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '品牌的名字'),
(@last_id, '0', '外国牌'),
(@last_id, '0', '以运动鞋著称'),
(@last_id, '0', '各大商场都有专柜门店'),
(@last_id, '0', '他们家鞋子穿起来很舒服'),
(@last_id, '0', '年轻人中大受欢迎'),
(@last_id, '0', '款式都很新潮'),
(@last_id, '0', '买过两双这个牌子的鞋'),
(@last_id, '0', '好多人跑马拉松会穿这个牌子'),
(@last_id, '0', '我表示没听过…');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"梅花","normal":"樱花"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '花名'),
(@last_id, '0', '颜色很多，有粉色，也有红色的'),
(@last_id, '0', '冬天开放'),
(@last_id, '0', '树上开出来的'),
(@last_id, '0', '女生都很喜欢这种花'),
(@last_id, '0', '会特意跑到日本去看'),
(@last_id, '0', '诗人都喜欢拿它写诗'),
(@last_id, '0', '可以做成食材'),
(@last_id, '0', '大片花海开出来特别的美');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"狮子","normal":"豹子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '动物'),
(@last_id, '0', '很凶猛'),
(@last_id, '0', '跑起来特别快'),
(@last_id, '0', '百兽之王'),
(@last_id, '0', '小孩都很怕这种动物'),
(@last_id, '0', '刚生下来的时候长得像小猫'),
(@last_id, '0', '大型猫科动物'),
(@last_id, '0', '黄色的皮毛'),
(@last_id, '0', '有部动画片的主角就是它'),
(@last_id, '0', '食肉类');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"秋衣","normal":"秋裤"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟冬天有关'),
(@last_id, '0', '年纪大了就要穿了'),
(@last_id, '0', '御寒圣品'),
(@last_id, '0', '网上很有网友恶搞它'),
(@last_id, '0', '保暖'),
(@last_id, '0', '穿在里面的内衣'),
(@last_id, '0', '不能外穿'),
(@last_id, '0', '妈妈总是逼着我穿它'),
(@last_id, '0', '适合在家穿'),
(@last_id, '0', '要贴身穿的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"帽子","normal":"围巾"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '御寒圣品'),
(@last_id, '0', '情侣很喜欢给彼此送这个'),
(@last_id, '0', '针织品'),
(@last_id, '0', '冬天必备'),
(@last_id, '0', '很挡风的'),
(@last_id, '0', '很多人都会用情侣款'),
(@last_id, '0', '小时候妈妈会手工织给我'),
(@last_id, '0', '有很多种搭配方式'),
(@last_id, '0', '很温暖'),
(@last_id, '0', '有多种材料：针织、棉纺、丝绸');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"人偶","normal":"木偶"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '小孩玩的'),
(@last_id, '0', '很逼真'),
(@last_id, '0', '长得跟人很像'),
(@last_id, '0', '有一部恐怖片跟它有关'),
(@last_id, '0', '玩具'),
(@last_id, '0', '戏剧里会用它做道具'),
(@last_id, '0', '女孩子比较喜欢'),
(@last_id, '0', '范冰冰曾做过这种造型走红毯'),
(@last_id, '0', '古代曾是殉葬品'),
(@last_id, '0', '蔷薇少女');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"章鱼","normal":"乌贼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '会喷墨'),
(@last_id, '0', '生活在海里'),
(@last_id, '0', '海鲜'),
(@last_id, '0', '有很多条腿'),
(@last_id, '0', '韩国人很喜欢生吃'),
(@last_id, '0', '经常做火锅主材料'),
(@last_id, '0', '烧烤摊上的常驻客'),
(@last_id, '0', '具有丰富的食疗价值'),
(@last_id, '0', '可以变色'),
(@last_id, '0', '铁板的吃法很美味');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"呆子","normal":"傻子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '天然萌自然呆'),
(@last_id, '0', '不够聪明'),
(@last_id, '0', '关系很亲密的人可以这样称呼'),
(@last_id, '0', '不能随便这样叫别人'),
(@last_id, '0', '形容人的'),
(@last_id, '0', '有点木'),
(@last_id, '0', '脑子不灵光'),
(@last_id, '0', '反应慢'),
(@last_id, '0', '开玩笑的时候会说'),
(@last_id, '0', '不喜欢被人这样称呼');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"火锅","normal":"烤肉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种美味的食物吃法'),
(@last_id, '0', '很多人都喜欢'),
(@last_id, '0', '全国各地都有类似的餐厅'),
(@last_id, '0', '一群人吃比较有意思'),
(@last_id, '0', '天气冷更适合'),
(@last_id, '0', '需要搭配调料'),
(@last_id, '0', '可以吃肉'),
(@last_id, '0', '很烫'),
(@last_id, '0', '容易上火不能天天吃'),
(@last_id, '0', '可以选择不同的味道');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"暴雨","normal":"雷阵雨"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种自然现象'),
(@last_id, '0', '每年都会发生'),
(@last_id, '0', '全世界都有'),
(@last_id, '0', '夏天比较频繁'),
(@last_id, '0', '一般不能出门'),
(@last_id, '0', '会引发洪灾'),
(@last_id, '0', '街道可能被淹'),
(@last_id, '0', '会打雷闪电'),
(@last_id, '0', '好多水'),
(@last_id, '0', '可能还会伴随着大风');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"神经病","normal":"白痴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种不健康的状态'),
(@last_id, '0', '骂人的话'),
(@last_id, '0', '绝对不可以随意这样评论人家'),
(@last_id, '0', '身体不健康'),
(@last_id, '0', '不像正常人'),
(@last_id, '0', '智力不正常'),
(@last_id, '0', '语言能力不正常'),
(@last_id, '0', '跟大脑神经受损有关'),
(@last_id, '0', '有可能先天的也可能后天的'),
(@last_id, '0', '父母都不希望自己小孩这样子');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"脚趾头","normal":"脚后跟"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '身体的一部分'),
(@last_id, '0', '下半身'),
(@last_id, '0', '在腿上'),
(@last_id, '0', '要接触袜子或者鞋子'),
(@last_id, '0', '受伤都会挺痛的'),
(@last_id, '0', '鞋子不合脚会痛'),
(@last_id, '0', '走路要靠它'),
(@last_id, '0', '容易受伤'),
(@last_id, '0', '穿高跟鞋很受罪'),
(@last_id, '0', '每天都要洗');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"护照","normal":"绿卡"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '通行证名'),
(@last_id, '0', '和身份证一样重要的东西'),
(@last_id, '0', '出国必须要'),
(@last_id, '0', '没有这个无法出国'),
(@last_id, '0', '证明国籍身份的'),
(@last_id, '0', '有这个东西在外国才是合法的'),
(@last_id, '0', '旅游居住在国外需要'),
(@last_id, '0', '很多人想要有美国的这个东西'),
(@last_id, '0', '在国外丢了会很惨'),
(@last_id, '0', '每个人的都是独一无二的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"紫薯","normal":"红薯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '埋在地里的，需要挖出来'),
(@last_id, '0', '人人都可以吃'),
(@last_id, '0', '淀粉比较多'),
(@last_id, '0', '属于粗粮'),
(@last_id, '0', '吃了对身体挺好的'),
(@last_id, '0', '可以煮着吃也可以烤着吃'),
(@last_id, '0', '烤的味道很好'),
(@last_id, '0', '一年四季都可以买到'),
(@last_id, '0', '南方北方都卖'),
(@last_id, '0', '容易断');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"雪糕","normal":"冰棍"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '吃着凉凉的'),
(@last_id, '0', '夏天特别适合吃'),
(@last_id, '0', '女孩子更喜欢'),
(@last_id, '0', '很多种口味'),
(@last_id, '0', '从小吃到大'),
(@last_id, '0', '自己家里也可做'),
(@last_id, '0', '小卖部都有卖的'),
(@last_id, '0', '基本都是甜的'),
(@last_id, '0', '巧克力味，香芋味，奶油味很不错'),
(@last_id, '0', '感冒了不能吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"兴高采烈","normal":"欢天喜地"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '兴致特别高'),
(@last_id, '0', '精神超级好'),
(@last_id, '0', '一个人心情很棒'),
(@last_id, '0', '碰到开心事的时候会这样'),
(@last_id, '0', '心想事成了'),
(@last_id, '0', '反义词是垂头丧气'),
(@last_id, '0', '形容一个人的心情状态'),
(@last_id, '0', '欢乐喜庆的场面'),
(@last_id, '0', '过年小孩子收压岁钱就是这个状态'),
(@last_id, '0', '特别嗨的样子');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"拉面","normal":"炒面"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '是一种面条'),
(@last_id, '0', '有粗的有细的'),
(@last_id, '0', '加了调料更好吃'),
(@last_id, '0', '北方吃得更多些'),
(@last_id, '0', '可以加鸡蛋加肉加青菜一起'),
(@last_id, '0', '可以手工做可以机器做'),
(@last_id, '0', '中过传统小吃'),
(@last_id, '0', '基本上很多人都喜欢'),
(@last_id, '0', '面馆里都会卖'),
(@last_id, '0', '大街小巷都可以吃到');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"明道","normal":"阮经天"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '帅哥'),
(@last_id, '0', '明星'),
(@last_id, '0', '很出名的男明星'),
(@last_id, '0', '台湾的'),
(@last_id, '0', '身材超级好'),
(@last_id, '0', '拍了很多部电视剧'),
(@last_id, '0', '在大陆很受欢迎'),
(@last_id, '0', '既演电视还演电影'),
(@last_id, '0', '很多女生心目中的男神'),
(@last_id, '0', '最近没有他们的新闻');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"芭蕾","normal":"爵士舞"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种舞蹈'),
(@last_id, '0', '来自西方'),
(@last_id, '0', '不是中国本土的'),
(@last_id, '0', '很自由的舞蹈'),
(@last_id, '0', '我都不会跳'),
(@last_id, '0', '对演员要求特别高'),
(@last_id, '0', '现在非常流行'),
(@last_id, '0', '全世界都有人在学'),
(@last_id, '0', '需要配合音乐'),
(@last_id, '0', '聚会的时候会表演');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"聪明","normal":"可爱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一个形容词'),
(@last_id, '0', '经常用来形容人'),
(@last_id, '0', '可以形容小动物'),
(@last_id, '0', '是一个褒义词'),
(@last_id, '0', '我喜欢听别人这么夸我'),
(@last_id, '0', '还可以形容小动物'),
(@last_id, '0', '说一个人的品性啥的会这样子说'),
(@last_id, '0', '别人听到会高兴'),
(@last_id, '0', '比较乖巧伶俐'),
(@last_id, '0', '我家小狗狗就是这样的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"甲骨文","normal":"象形文"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种文字类型'),
(@last_id, '0', '中国很早期的一种文字'),
(@last_id, '0', '那时候还没有纸'),
(@last_id, '0', '写字需要刻下来'),
(@last_id, '0', '跟现在的汉子差别很大'),
(@last_id, '0', '有不同的形状'),
(@last_id, '0', '是研究远古时代的重要参考资料'),
(@last_id, '0', '考古学家必须要懂'),
(@last_id, '0', '现代人基本不会'),
(@last_id, '0', '我们很难看懂');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"百度","normal":"谷歌"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有了计算机之后才有的'),
(@last_id, '0', '互联网发展的产物'),
(@last_id, '0', '搜索东西的网站'),
(@last_id, '0', '特别多的信息'),
(@last_id, '0', '有什么不懂的都可以搜'),
(@last_id, '0', '很大的互联网公司'),
(@last_id, '0', '年轻人几乎都会用'),
(@last_id, '0', '电脑手机都可以用'),
(@last_id, '0', '信息检索'),
(@last_id, '0', '上网查信息必备');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"高跟鞋","normal":"平底鞋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '鞋子'),
(@last_id, '0', '穿在脚上的'),
(@last_id, '0', '不容的款式'),
(@last_id, '0', '真皮的比较贵'),
(@last_id, '0', '价格不一样'),
(@last_id, '0', '可以有不同的材质'),
(@last_id, '0', '女生很喜欢'),
(@last_id, '0', '生活必需品'),
(@last_id, '0', '名牌的穿着很有范'),
(@last_id, '0', '如果不合脚穿起来很痛苦');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"麻辣烫","normal":"酸辣粉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '小吃'),
(@last_id, '0', '喜欢吃辣的人喜欢'),
(@last_id, '0', '不能天天吃'),
(@last_id, '0', '需要各种不同的调料'),
(@last_id, '0', '吃起来很开胃'),
(@last_id, '0', '女生更喜欢吃'),
(@last_id, '0', '有悠久历史'),
(@last_id, '0', '重庆四川吃的很多'),
(@last_id, '0', '全国各地都有卖的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"分手","normal":"离婚"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '发生在男女之间'),
(@last_id, '0', '两个人分开'),
(@last_id, '0', '没有人喜欢走到这一步'),
(@last_id, '0', '一般心情都很糟糕'),
(@last_id, '0', '两个人合不来'),
(@last_id, '0', '可能从此以后不来往了'),
(@last_id, '0', '做决定前要慎重'),
(@last_id, '0', '两个人会发生冲突'),
(@last_id, '0', '曾经在一起'),
(@last_id, '0', '心情很郁闷');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"私生子","normal":"孤儿"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '某一类特殊孩子'),
(@last_id, '0', '没有完整的家庭'),
(@last_id, '0', '爸妈不能陪着一起长大'),
(@last_id, '0', '很少感受家的温暖'),
(@last_id, '0', '可能会被别人指指点点'),
(@last_id, '0', '中国很多这样的孩子'),
(@last_id, '0', '父母不负责任'),
(@last_id, '0', '会住在孤儿院'),
(@last_id, '0', '挺可怜的'),
(@last_id, '0', '我们不能嘲笑他们');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"孤儿","normal":"弃儿"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '某一类特殊孩子'),
(@last_id, '0', '没有完整的家庭'),
(@last_id, '0', '爸妈不能陪着一起长大'),
(@last_id, '0', '很少感受家的温暖'),
(@last_id, '0', '可能会被别人指指点点'),
(@last_id, '0', '中国很多这样的孩子'),
(@last_id, '0', '父母不负责任'),
(@last_id, '0', '会住在孤儿院或者儿童福利院'),
(@last_id, '0', '挺可怜的'),
(@last_id, '0', '我们不能嘲笑他们');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"香奈儿","normal":"迪奥"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '品牌'),
(@last_id, '0', '全世界都很出名'),
(@last_id, '0', '香水特别棒'),
(@last_id, '0', '法国的品牌'),
(@last_id, '0', '奢侈品'),
(@last_id, '0', '女性很喜欢'),
(@last_id, '0', '女装非常棒'),
(@last_id, '0', '很多大牌设计师设计'),
(@last_id, '0', '衣服剪裁精致'),
(@last_id, '0', '在中国很受欢迎');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"电灯","normal":"吊灯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '灯具'),
(@last_id, '0', '照明'),
(@last_id, '0', '日常生活必备'),
(@last_id, '0', '不同形状'),
(@last_id, '0', '有节能的'),
(@last_id, '0', '不同的亮度可以调节'),
(@last_id, '0', '不仅照明还有装饰作用'),
(@last_id, '0', '价格有高有低'),
(@last_id, '0', '有专门的灯具店可以买'),
(@last_id, '0', '有电才可以用');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"狮子","normal":"老虎"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种动物'),
(@last_id, '0', '生活在森林草原上'),
(@last_id, '0', '动物之王'),
(@last_id, '0', '猫科动物'),
(@last_id, '0', '肉食动物'),
(@last_id, '0', '非常凶猛'),
(@last_id, '0', '跑得很快'),
(@last_id, '0', '体型比豹子大'),
(@last_id, '0', '会吃人'),
(@last_id, '0', '动物园里有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"恰恰舞","normal":"拉丁舞"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种舞蹈'),
(@last_id, '0', '动作比较快'),
(@last_id, '0', '单人双人都有可以跳'),
(@last_id, '0', '不是中国本土的'),
(@last_id, '0', '西方传进来的舞蹈'),
(@last_id, '0', '男的女的都可以跳'),
(@last_id, '0', '两个人需要配合默契'),
(@last_id, '0', '舞会的时候可以跳'),
(@last_id, '0', '很严肃的场合不跳这种舞'),
(@last_id, '0', '音乐调皮欢快');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"郭德纲","normal":"周星驰"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '家喻户晓'),
(@last_id, '0', '大人小孩都喜欢'),
(@last_id, '0', '特别搞笑'),
(@last_id, '0', '一看他们表演就特高兴'),
(@last_id, '0', '培养了很多新人出来'),
(@last_id, '0', '火了很多年了'),
(@last_id, '0', '年纪比较大'),
(@last_id, '0', '搞笑大师'),
(@last_id, '0', '喜欢讲段子'),
(@last_id, '0', '招牌笑声');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"旅游","normal":"郊游"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '出去玩'),
(@last_id, '0', '节假日人很多'),
(@last_id, '0', '上班时间不能去'),
(@last_id, '0', '风景好的地方人多'),
(@last_id, '0', '一家人去挺好的'),
(@last_id, '0', '可以约好有同事一起'),
(@last_id, '0', '需要有钱有时间'),
(@last_id, '0', '地点不限'),
(@last_id, '0', '放松心情'),
(@last_id, '0', '很好的放松方式');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"斯诺克","normal":"花式九球"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '台球'),
(@last_id, '0', '桌球'),
(@last_id, '0', '专门的场地玩'),
(@last_id, '0', '需要球杆和球'),
(@last_id, '0', '球是不同颜色的'),
(@last_id, '0', '考验个人技术'),
(@last_id, '0', '娱乐项目'),
(@last_id, '0', '竞技比赛'),
(@last_id, '0', '全世界都有人玩'),
(@last_id, '0', '有专业的有业余的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大麻","normal":"病毒"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '对身体有害'),
(@last_id, '0', '接触了人会不舒服'),
(@last_id, '0', '可能会有生命危险'),
(@last_id, '0', '慢性'),
(@last_id, '0', '破坏人的免疫系统'),
(@last_id, '0', '要到医院治疗'),
(@last_id, '0', '会上瘾'),
(@last_id, '0', '对身体伤害很大'),
(@last_id, '0', '在身体里停留很长时间'),
(@last_id, '0', '一般会传染');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"爆米花","normal":"薯片"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '零食'),
(@last_id, '0', '消磨时间的'),
(@last_id, '0', '看电视看电影可以吃'),
(@last_id, '0', '热量比较高'),
(@last_id, '0', '女孩子吃多了容易胖'),
(@last_id, '0', '不同口味的'),
(@last_id, '0', '酥酥脆脆的'),
(@last_id, '0', '很常见的零食'),
(@last_id, '0', '没有人不会吃的'),
(@last_id, '0', '便利店经常卖');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"增高鞋","normal":"高跟鞋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种鞋子'),
(@last_id, '0', '女性穿的多一些'),
(@last_id, '0', '可以让人变高'),
(@last_id, '0', '没有平底鞋舒服'),
(@last_id, '0', '矮个子女生更喜欢'),
(@last_id, '0', '不同款式'),
(@last_id, '0', '不同材质'),
(@last_id, '0', '价格高低不一样'),
(@last_id, '0', '可以让人更自信'),
(@last_id, '0', '质量好的穿着比较舒适');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"失望","normal":"绝望"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种情绪'),
(@last_id, '0', '比较低落'),
(@last_id, '0', '心情不好'),
(@last_id, '0', '遇到不好的事情了'),
(@last_id, '0', '可能遭受了打击'),
(@last_id, '0', '垂头丧气'),
(@last_id, '0', '想哭'),
(@last_id, '0', '很抑郁'),
(@last_id, '0', '谁都不喜欢有这种情绪'),
(@last_id, '0', '感到没有希望');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"木地板","normal":"竹地板"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '地板'),
(@last_id, '0', '家里装修的时候要考虑'),
(@last_id, '0', '装修材料'),
(@last_id, '0', '有不同类型的'),
(@last_id, '0', '价格高低不等'),
(@last_id, '0', '居家，写字楼都需要'),
(@last_id, '0', '算是比较高档的地板'),
(@last_id, '0', '不同的尺寸大小'),
(@last_id, '0', '专业装修师傅安装'),
(@last_id, '0', '铺好后需要隔一段时间入住');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"雷克萨斯","normal":"凯迪拉克"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '美国总统的座驾'),
(@last_id, '0', '中高端品牌'),
(@last_id, '0', '汽车品牌'),
(@last_id, '0', '同级中卖的不是特别好'),
(@last_id, '0', '旗下有不同的车型'),
(@last_id, '0', '有自己的专属标志'),
(@last_id, '0', '日本品牌'),
(@last_id, '0', '历史比较悠久'),
(@last_id, '0', '比较著名的汽车公司旗下品牌'),
(@last_id, '0', '一直在研发设计不同的车型');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"镜片","normal":"镜框"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '眼镜'),
(@last_id, '0', '眼镜的重要组成部分'),
(@last_id, '0', '戴眼镜的人必备'),
(@last_id, '0', '不同材质的'),
(@last_id, '0', '有的价格高有的价格低'),
(@last_id, '0', '不同的颜色'),
(@last_id, '0', '有的人是为了起装饰作用'),
(@last_id, '0', '是一个框框'),
(@last_id, '0', '掉在地上有可能会摔坏'),
(@last_id, '0', '放在眼镜盒里的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"豆腐脑","normal":"豆腐块"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '豆制品'),
(@last_id, '0', '用黄豆做的'),
(@last_id, '0', '蛋白质丰富'),
(@last_id, '0', '经常吃可以补充蛋白质'),
(@last_id, '0', '需要把黄豆磨碎'),
(@last_id, '0', '全国各地都可以买到'),
(@last_id, '0', '有不同的吃法'),
(@last_id, '0', '可以加不同的调味料'),
(@last_id, '0', '吃起来软软的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"冻豆腐","normal":"老豆腐"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '豆制品'),
(@last_id, '0', '用黄豆做的'),
(@last_id, '0', '蛋白质丰富'),
(@last_id, '0', '经常吃可以补充蛋白质'),
(@last_id, '0', '需要把黄豆磨碎'),
(@last_id, '0', '可以添加不同的调料'),
(@last_id, '0', '味道很鲜美'),
(@last_id, '0', '在中国历史很悠久'),
(@last_id, '0', '中国传统特色小吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"散打","normal":"武术"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '体育格斗运动'),
(@last_id, '0', '很消耗体力'),
(@last_id, '0', '踢、打、摔'),
(@last_id, '0', '攻击性比较强'),
(@last_id, '0', '被打了会受伤'),
(@last_id, '0', '出手速度可以很快'),
(@last_id, '0', '有很长的历史'),
(@last_id, '0', '中国人很擅长'),
(@last_id, '0', '可以防身'),
(@last_id, '0', '需要长期训练才能很专业');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"近视","normal":"散光"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟视力有关'),
(@last_id, '0', '眼睛不正常'),
(@last_id, '0', '需要配戴眼镜'),
(@last_id, '0', '通过专业测试可以测度数'),
(@last_id, '0', '可以通过手术矫正'),
(@last_id, '0', '看不清楚东西'),
(@last_id, '0', '大学生很多都会这样'),
(@last_id, '0', '读书坐姿不对会引发'),
(@last_id, '0', '经常做眼保健操有好处'),
(@last_id, '0', '如果不注意会越来越严重');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"妻管严","normal":"吃软饭"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '形容男人的'),
(@last_id, '0', '男的不喜欢听到这个称呼'),
(@last_id, '0', '男的没本事'),
(@last_id, '0', '被自己的老婆管着'),
(@last_id, '0', '女的一般不喜欢这样的'),
(@last_id, '0', '在中国很常见'),
(@last_id, '0', '不是一种和谐的男女关系'),
(@last_id, '0', '含有嘲讽意味'),
(@last_id, '0', '不是一个褒义词'),
(@last_id, '0', '大部分男的不喜欢做这样的人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"刘邦","normal":"项羽"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '历史人物'),
(@last_id, '0', '生活在秦朝末期'),
(@last_id, '0', '起义反秦'),
(@last_id, '0', '楚汉战争'),
(@last_id, '0', '善于谋略'),
(@last_id, '0', '家喻户晓的人物'),
(@last_id, '0', '起义军的首领'),
(@last_id, '0', '身边有很多谋士'),
(@last_id, '0', '善于谋略'),
(@last_id, '0', '有很强的军事领导才能');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"蕾丝","normal":"基友"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '形容人的关系'),
(@last_id, '0', '两个人关系很好'),
(@last_id, '0', '感情特别好的两个同性'),
(@last_id, '0', '现在越来越多人可以接受了'),
(@last_id, '0', '关系很亲密，经常一起行动'),
(@last_id, '0', '生活中随处可见'),
(@last_id, '0', '搞基'),
(@last_id, '0', '发生在同性之间'),
(@last_id, '0', '两个人的关系'),
(@last_id, '0', '和一般男女朋友关系不一样');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"58同城","normal":"赶集网"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '分类信息网站'),
(@last_id, '0', '在中国很多年了'),
(@last_id, '0', '房屋出租'),
(@last_id, '0', '二手房租赁'),
(@last_id, '0', '招聘'),
(@last_id, '0', '二手车买卖'),
(@last_id, '0', '生活类网站'),
(@last_id, '0', '找兼职'),
(@last_id, '0', '家居服务'),
(@last_id, '0', '和另一个类似的公司合并了');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"文物","normal":"古董"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '古代的东西'),
(@last_id, '0', '历史久远'),
(@last_id, '0', '字画'),
(@last_id, '0', '瓷器'),
(@last_id, '0', '年代越久越珍贵'),
(@last_id, '0', '非常重要的研究资料'),
(@last_id, '0', '一般放在博物馆'),
(@last_id, '0', '价值连城'),
(@last_id, '0', '有人会收藏'),
(@last_id, '0', '有人仿制');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"面包","normal":"蛋糕"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '一般是甜的'),
(@last_id, '0', '可以做的很漂亮'),
(@last_id, '0', '烘焙'),
(@last_id, '0', '不同的口味'),
(@last_id, '0', '可莎蜜儿，哈根达斯都有卖'),
(@last_id, '0', '可以买也可以自己做'),
(@last_id, '0', '面粉做的'),
(@last_id, '0', '可以加奶油'),
(@last_id, '0', '松松软软的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"背包","normal":"书包"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '包包'),
(@last_id, '0', '比女生的手提包大一些'),
(@last_id, '0', '可以装很多东西'),
(@last_id, '0', '外出的时候比较方便'),
(@last_id, '0', '可以装电脑'),
(@last_id, '0', '很不容易的'),
(@last_id, '0', '日常生活用品'),
(@last_id, '0', '不同的材质'),
(@last_id, '0', '现在设计的越来越时尚'),
(@last_id, '0', '有很多小袋子在上面');

INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"黑巧克力","normal":"白巧克力"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种吃的'),
(@last_id, '0', '甜的'),
(@last_id, '0', '男生经常送女生'),
(@last_id, '0', '是从西方传进来的'),
(@last_id, '0', '情人节的时候很多'),
(@last_id, '0', '女生比较喜欢吃'),
(@last_id, '0', '有淡淡的苦味'),
(@last_id, '0', '饿了来一块可以补充能量'),
(@last_id, '0', '费列罗的很不错'),
(@last_id, '0', '德芙');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"罗玉凤","normal":"犀利哥"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '网红'),
(@last_id, '0', '在网上被讨论的很多'),
(@last_id, '0', '外表一般般'),
(@last_id, '0', '外形被很多人开玩笑'),
(@last_id, '0', '网友搜集了很多相关的事'),
(@last_id, '0', '现在没有以前火了'),
(@last_id, '0', '有自己很独特的称呼'),
(@last_id, '0', '算是草根阶层'),
(@last_id, '0', '虽然很火但不是明星'),
(@last_id, '0', '不会一直都被人们关注');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"黄牛","normal":"牦牛"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '牛的一个品种'),
(@last_id, '0', '体型很大'),
(@last_id, '0', '肉可以吃'),
(@last_id, '0', '哺乳动物'),
(@last_id, '0', '草食性动物'),
(@last_id, '0', '奶可以喝'),
(@last_id, '0', '可以农耕也可以运东西'),
(@last_id, '0', '皮革可以做衣服鞋子'),
(@last_id, '0', '有两个角'),
(@last_id, '0', '有长长的尾巴');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"甄嬛传","normal":"大长今"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电视剧'),
(@last_id, '0', '有很多集'),
(@last_id, '0', '主角是女的'),
(@last_id, '0', '播放的时候超级火'),
(@last_id, '0', '剧里除了女主角还有很多女的'),
(@last_id, '0', '女主角经常遭陷害'),
(@last_id, '0', '女主角很善良'),
(@last_id, '0', '讲了女主角的爱情'),
(@last_id, '0', '在国外也很受欢迎'),
(@last_id, '0', '不是现代剧');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"香蕉汁","normal":"苹果汁"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '水果'),
(@last_id, '0', '水果榨的'),
(@last_id, '0', '营养很丰富'),
(@last_id, '0', '比可乐好'),
(@last_id, '0', '自己可以买来榨榨'),
(@last_id, '0', '维生素丰富'),
(@last_id, '0', '甜甜的'),
(@last_id, '0', '夏天喝很舒服'),
(@last_id, '0', '味道很不错'),
(@last_id, '0', '女孩子多喝可以养颜');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"杯子","normal":"盘子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '日常生活用品'),
(@last_id, '0', '每家都会有'),
(@last_id, '0', '装东西'),
(@last_id, '0', '有塑料的，有玻璃的'),
(@last_id, '0', '有的很精致，有的很普通'),
(@last_id, '0', '超市都有卖的'),
(@last_id, '0', '各种不同的形状'),
(@last_id, '0', '我天天都要用'),
(@last_id, '0', '装烫的东西会烫手'),
(@last_id, '0', '陶瓷的很好看');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"水果刀","normal":"菜刀"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '日常生活用品'),
(@last_id, '0', '每家每户都会有'),
(@last_id, '0', '切东西的'),
(@last_id, '0', '有大有小'),
(@last_id, '0', '很锋利'),
(@last_id, '0', '刀'),
(@last_id, '0', '不小心有可能会割到手'),
(@last_id, '0', '可以切水果也可以切蔬菜'),
(@last_id, '0', '我做菜经常需要用'),
(@last_id, '0', '曾经割到手');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"成吉思汗","normal":"努尔哈赤"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '历史人物'),
(@last_id, '0', '男的'),
(@last_id, '0', '生活在古代'),
(@last_id, '0', '少数民族'),
(@last_id, '0', '少数民族首领'),
(@last_id, '0', '建立新的封建王朝'),
(@last_id, '0', '擅长骑射'),
(@last_id, '0', '故乡在中国北方'),
(@last_id, '0', '灭了前朝皇帝'),
(@last_id, '0', '卓越的政治军事才能');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"好老公","normal":"好男人"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '形容男性'),
(@last_id, '0', '男的品性良好'),
(@last_id, '0', '男人里面优秀的一类人'),
(@last_id, '0', '男人都喜欢被这样夸'),
(@last_id, '0', '女的希望遇到这样的人'),
(@last_id, '0', '和这样的男人在一起很幸福'),
(@last_id, '0', '跟渣男恰恰相反的男人'),
(@last_id, '0', '女人都希望自己的另一半是这种人'),
(@last_id, '0', '善良真诚的男人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"林青霞","normal":"张曼玉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女性'),
(@last_id, '0', '女明星'),
(@last_id, '0', '长得非常漂亮'),
(@last_id, '0', '八十年代非常火'),
(@last_id, '0', '曾经是很多男性的梦中情人'),
(@last_id, '0', '演员'),
(@last_id, '0', '很多的代表性作品'),
(@last_id, '0', '不是中国大陆的明星'),
(@last_id, '0', '现在年纪比较大了'),
(@last_id, '0', '现在不演电影了');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"玻璃","normal":"树脂"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '化学材料'),
(@last_id, '0', '非金属材料'),
(@last_id, '0', '日常生活中很常见'),
(@last_id, '0', '眼镜片的材料'),
(@last_id, '0', '高温下会融化'),
(@last_id, '0', '有的是透明的'),
(@last_id, '0', '用途很广泛'),
(@last_id, '0', '可以变硬也可以变软'),
(@last_id, '0', '可以做成不同的形状'),
(@last_id, '0', '可以用来制作工艺品');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小姐","normal":"姑娘"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女性'),
(@last_id, '0', '对女性的称呼'),
(@last_id, '0', '一般是还没结婚的女性'),
(@last_id, '0', '年纪不是很大'),
(@last_id, '0', '对陌生人这样称呼'),
(@last_id, '0', '朋友之间一般不会这样叫'),
(@last_id, '0', '泛指年轻女性'),
(@last_id, '0', '有的人不喜欢被这样叫'),
(@last_id, '0', '前面加个姓氏更礼貌一些'),
(@last_id, '0', '日常称谓词');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"三只松鼠","normal":"良品铺子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '食品品牌'),
(@last_id, '0', '在中国很火'),
(@last_id, '0', '休闲零食'),
(@last_id, '0', '很多种类的零食'),
(@last_id, '0', '坚果'),
(@last_id, '0', '倡导绿色健康食品'),
(@last_id, '0', '天猫淘宝上有'),
(@last_id, '0', '双十一很多人买'),
(@last_id, '0', '购买非常方便'),
(@last_id, '0', '买零食非常不错的选择');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"热气球","normal":"孔明灯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '会飞上天'),
(@last_id, '0', '需要点火'),
(@last_id, '0', '最终可能会掉下来'),
(@last_id, '0', '需要热源'),
(@last_id, '0', '不需要电能'),
(@last_id, '0', '历史很悠久'),
(@last_id, '0', '大城市里一般不让用'),
(@last_id, '0', '我自己曾经玩过'),
(@last_id, '0', '在过去有军事用途'),
(@last_id, '0', '可以自己做');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"娃哈哈","normal":"养乐多"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '喝的'),
(@last_id, '0', '饮料'),
(@last_id, '0', '酸酸甜甜的'),
(@last_id, '0', '一般都是瓶装的'),
(@last_id, '0', '一瓶的价格不是很高'),
(@last_id, '0', '小孩子很喜欢喝'),
(@last_id, '0', '女生比较喜欢'),
(@last_id, '0', '超级便利店都买得到'),
(@last_id, '0', '全国各地都卖'),
(@last_id, '0', '有助于消化');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"历史","normal":"政治"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一门学科'),
(@last_id, '0', '初中高中都会开设'),
(@last_id, '0', '文科'),
(@last_id, '0', '各个国家都会研究'),
(@last_id, '0', '涉及到国家层面的'),
(@last_id, '0', '有很长的历史'),
(@last_id, '0', '每个国家情况不一样'),
(@last_id, '0', '下面还有很多细分学科'),
(@last_id, '0', '出了很多的研究专家'),
(@last_id, '0', '高中时要背很多的内容');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"皇后","normal":"妃子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女性'),
(@last_id, '0', '已婚女性'),
(@last_id, '0', '生活在古代'),
(@last_id, '0', '皇宫里的女人'),
(@last_id, '0', '皇帝的老婆'),
(@last_id, '0', '皇宫里地位很高'),
(@last_id, '0', '有可能被皇帝废了'),
(@last_id, '0', '经常和皇宫里其他女人斗'),
(@last_id, '0', '现在没有这样的称谓了'),
(@last_id, '0', '有很多可以使唤的下人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"卡布奇诺","normal":"摩卡"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '咖啡'),
(@last_id, '0', '喝的饮料'),
(@last_id, '0', '星巴克'),
(@last_id, '0', '起源于意大利'),
(@last_id, '0', '味道有些苦'),
(@last_id, '0', '现煮的更香醇'),
(@last_id, '0', '很多人喜欢喝'),
(@last_id, '0', '味道比较浓郁'),
(@last_id, '0', '含有咖啡因'),
(@last_id, '0', '喝了可以提神');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"宿舍","normal":"公寓"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '房子'),
(@last_id, '0', '住的地方'),
(@last_id, '0', '学校里就有'),
(@last_id, '0', '一个人住很爽'),
(@last_id, '0', '可能几个人一起住'),
(@last_id, '0', '环境有的好有的不好'),
(@last_id, '0', '可以出租'),
(@last_id, '0', '不是特别豪华的别墅'),
(@last_id, '0', '面积可大可小'),
(@last_id, '0', '年轻人住的比较多');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小品","normal":"话剧"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种艺术形式'),
(@last_id, '0', '需要人来表演'),
(@last_id, '0', '不像歌舞一直有配乐'),
(@last_id, '0', '时间不会很久'),
(@last_id, '0', '在舞台上表演'),
(@last_id, '0', '一般是几个人表演'),
(@last_id, '0', '需要事先进行排练'),
(@last_id, '0', '有喜剧，悲剧'),
(@last_id, '0', '不能回放'),
(@last_id, '0', '春晚会有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"飞轮海","normal":"五月天"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '乐队'),
(@last_id, '0', '男明星'),
(@last_id, '0', '超级帅'),
(@last_id, '0', '好几个男生组合在一起'),
(@last_id, '0', '出了很多专辑'),
(@last_id, '0', '开演唱会'),
(@last_id, '0', '唱歌'),
(@last_id, '0', '火了很多年'),
(@last_id, '0', '他们的很多首歌耳熟能详'),
(@last_id, '0', '5个人的组合');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"蔡依林","normal":"萧亚轩"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女性'),
(@last_id, '0', '台湾的'),
(@last_id, '0', '女歌手'),
(@last_id, '0', '唱了很多首歌'),
(@last_id, '0', '长得很漂亮'),
(@last_id, '0', '身材很好'),
(@last_id, '0', '现在30多岁'),
(@last_id, '0', '在大陆很受欢迎'),
(@last_id, '0', '获得过音乐方面的大奖'),
(@last_id, '0', '至今还没有结婚');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"炒面","normal":"炒饭"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '食物'),
(@last_id, '0', '特色小吃'),
(@last_id, '0', '小餐馆里就有'),
(@last_id, '0', '需要炒着吃的'),
(@last_id, '0', '加鸡蛋加青菜会很好吃'),
(@last_id, '0', '自己在家里就可以做'),
(@last_id, '0', '可以加不同的辅料炒'),
(@last_id, '0', '全国各地都很普遍'),
(@last_id, '0', '我喜欢加辣椒'),
(@last_id, '0', '吃起来很香');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"上课","normal":"上班"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟我们每个人相关'),
(@last_id, '0', '有时间安排'),
(@last_id, '0', '工作日一般需要做'),
(@last_id, '0', '节假日可以休息'),
(@last_id, '0', '需要到专门的地方进行'),
(@last_id, '0', '一般还有其他人一起'),
(@last_id, '0', '有时候会很累'),
(@last_id, '0', '需要大脑不停地思考'),
(@last_id, '0', '可以接触很多东西'),
(@last_id, '0', '几乎每个人都会有这个过程');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"开心","normal":"快乐"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种情绪'),
(@last_id, '0', '美好的感觉'),
(@last_id, '0', '碰上好事情了'),
(@last_id, '0', '自己的事情都做完了会有这种感觉'),
(@last_id, '0', '帮助到了别人我就会这样'),
(@last_id, '0', '希望自己每天都是这个状态'),
(@last_id, '0', '会笑'),
(@last_id, '0', '爸妈希望自己的孩子是这样的状态'),
(@last_id, '0', '积极正面的'),
(@last_id, '0', '这样的情绪有益于身体健康');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"谢娜","normal":"吴昕"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '美女'),
(@last_id, '0', '主持人'),
(@last_id, '0', '湖南卫视的主持人'),
(@last_id, '0', '跟何炅是搭档'),
(@last_id, '0', '非常火'),
(@last_id, '0', '长得很漂亮'),
(@last_id, '0', '快乐大本营'),
(@last_id, '0', '认识杜海涛'),
(@last_id, '0', '快乐家族中的一员'),
(@last_id, '0', '拥有很多的男粉丝');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"宾馆","normal":"招待所"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '住的地方'),
(@last_id, '0', '出差的时候会去住'),
(@last_id, '0', '需要花钱'),
(@last_id, '0', '一般不会常住'),
(@last_id, '0', '环境有好有差'),
(@last_id, '0', '各个城市都有'),
(@last_id, '0', '入住需要登记个人信息'),
(@last_id, '0', '标间，单人间，双人间都有'),
(@last_id, '0', '属于服务行业'),
(@last_id, '0', '有的还提供吃的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"酒店","normal":"宾馆"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '住的地方'),
(@last_id, '0', '旅游之前需要提前订'),
(@last_id, '0', '节假日会很挤'),
(@last_id, '0', '短期住宿'),
(@last_id, '0', '有高档低档的'),
(@last_id, '0', '不仅可以住还可以吃东西'),
(@last_id, '0', '安全舒适最重要'),
(@last_id, '0', '有服务员'),
(@last_id, '0', '入住前需要登记个人信息'),
(@last_id, '0', '很多城市都有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"饭店","normal":"酒店"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '住的地方'),
(@last_id, '0', '旅游之前需要提前订'),
(@last_id, '0', '节假日会很挤'),
(@last_id, '0', '短期住宿'),
(@last_id, '0', '有高档低档的'),
(@last_id, '0', '不仅可以住还可以吃东西'),
(@last_id, '0', '安全舒适最重要'),
(@last_id, '0', '有服务员'),
(@last_id, '0', '入住前需要登记个人信息'),
(@last_id, '0', '很多城市都有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奶瓶","normal":"奶嘴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '婴儿用的'),
(@last_id, '0', '含在嘴里'),
(@last_id, '0', '牛奶'),
(@last_id, '0', '奶瓶的一部分'),
(@last_id, '0', '有婴儿的家里都会有'),
(@last_id, '0', '婴儿特别喜欢'),
(@last_id, '0', '哄小婴儿睡觉的时候可以用'),
(@last_id, '0', '有妈妈的感觉'),
(@last_id, '0', '要注意清洁卫生'),
(@last_id, '0', '经常需要消毒');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"瞒天过海","normal":"草船借箭"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '成语'),
(@last_id, '0', '历史典故'),
(@last_id, '0', '打战的计谋'),
(@last_id, '0', '由历史事件演变而来'),
(@last_id, '0', '迷惑对方'),
(@last_id, '0', '制造假象'),
(@last_id, '0', '跟很著名的历史人物有关'),
(@last_id, '0', '很容易被迷惑'),
(@last_id, '0', '多疑的人容易被骗'),
(@last_id, '0', '古代的故事');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"百合网","normal":"聚缘网"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '婚恋服务机构'),
(@last_id, '0', '找男朋友女朋友'),
(@last_id, '0', '在中国比较出名'),
(@last_id, '0', '同行竞争很激烈'),
(@last_id, '0', '适合单身男女'),
(@last_id, '0', '交友中介'),
(@last_id, '0', '交友平台'),
(@last_id, '0', '年轻男女注册较多'),
(@last_id, '0', '主打婚恋交友'),
(@last_id, '0', '需要注册会员才能享受更多服务');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"台北","normal":"首尔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '大城市'),
(@last_id, '0', '人口密集'),
(@last_id, '0', '在亚洲'),
(@last_id, '0', '经济繁荣'),
(@last_id, '0', '城市地位很高'),
(@last_id, '0', '经常有人去旅游'),
(@last_id, '0', '那里的人都会说英语'),
(@last_id, '0', '跟中国关系密切'),
(@last_id, '0', '很有当地特色'),
(@last_id, '0', '有著名的景点');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"油饼","normal":"油条"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '面食'),
(@last_id, '0', '一般早餐吃得比较多'),
(@last_id, '0', '比较油腻'),
(@last_id, '0', '油炸'),
(@last_id, '0', '传统特色小吃'),
(@last_id, '0', '老北京风味'),
(@last_id, '0', '配合豆浆很好吃'),
(@last_id, '0', '热量比较高'),
(@last_id, '0', '想减肥的不能多吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"相册","normal":"相框"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '存放照片'),
(@last_id, '0', '有木的'),
(@last_id, '0', '塑料材质的'),
(@last_id, '0', '可以用来观赏'),
(@last_id, '0', '一般家里都会有'),
(@last_id, '0', '可以放大照片也可以放小照片'),
(@last_id, '0', '设计的很精美'),
(@last_id, '0', '有不同的颜色'),
(@last_id, '0', '送别人做礼物也是可以的'),
(@last_id, '0', '可以长期保存');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鸡肉","normal":"猪肉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '肉类'),
(@last_id, '0', '食物'),
(@last_id, '0', '菜市场天天有人卖'),
(@last_id, '0', '超市可以买到'),
(@last_id, '0', '可以炒着吃'),
(@last_id, '0', '特别美味'),
(@last_id, '0', '有很多种不同的做法'),
(@last_id, '0', '一般都是弄熟了才吃'),
(@last_id, '0', '吃素的人不吃'),
(@last_id, '0', '营养很丰富');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"唐伯虎","normal":"梁山伯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男性'),
(@last_id, '0', '生活在古代'),
(@last_id, '0', '有关他的故事家喻户晓'),
(@last_id, '0', '读了很多书'),
(@last_id, '0', '喜欢上了一个女子并且很痴情'),
(@last_id, '0', '想要读书考科举'),
(@last_id, '0', '为情所困'),
(@last_id, '0', '含有嘲讽意味'),
(@last_id, '0', '有很多有关他的改编影视剧和电影'),
(@last_id, '0', '很有才华');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"处女","normal":"玉女"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女性'),
(@last_id, '0', '形容年轻女性'),
(@last_id, '0', '还未结婚'),
(@last_id, '0', '冰清玉洁'),
(@last_id, '0', '纯净美好'),
(@last_id, '0', '清丽脱俗'),
(@last_id, '0', '长得很漂亮'),
(@last_id, '0', '清新淡雅的年轻姑娘'),
(@last_id, '0', '娱乐圈有的女明星就被这样称呼'),
(@last_id, '0', '男人都很喜欢这样的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"微波炉","normal":"电烤箱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '家用电器'),
(@last_id, '0', '温度很高'),
(@last_id, '0', '加热食物'),
(@last_id, '0', '厨房常用电器之一'),
(@last_id, '0', '可以做面包，做pizza'),
(@last_id, '0', '有辐射'),
(@last_id, '0', '使用的时候要注意安全'),
(@last_id, '0', '容易烫手'),
(@last_id, '0', '现在的很智能'),
(@last_id, '0', '有时间和温度控制');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"轮滑","normal":"滑板"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '运动器材'),
(@last_id, '0', '年轻人比较喜欢'),
(@last_id, '0', '全国各地都可以玩'),
(@last_id, '0', '容易摔跤'),
(@last_id, '0', '需要不断练习才会很熟练'),
(@last_id, '0', '厉害的人可以玩出很多花样'),
(@last_id, '0', '最好佩戴护膝'),
(@last_id, '0', '滑起来速度很快'),
(@last_id, '0', '可以代步'),
(@last_id, '0', '马路上玩最好要注意安全');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"机器猫","normal":"加菲猫"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '小猫咪'),
(@last_id, '0', '很可爱'),
(@last_id, '0', '动漫角色'),
(@last_id, '0', '很多小孩子都看过'),
(@last_id, '0', '长得胖胖的'),
(@last_id, '0', '小孩子很喜欢'),
(@last_id, '0', '有相关的动画片'),
(@last_id, '0', '深入人心的角色'),
(@last_id, '0', '有玩具在卖'),
(@last_id, '0', '童年的记忆');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"池塘","normal":"水池"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '蓄水的'),
(@last_id, '0', '一般不是很大'),
(@last_id, '0', '会有小鱼在里面'),
(@last_id, '0', '水比较清澈'),
(@last_id, '0', '可以养小蝌蚪'),
(@last_id, '0', '水位高低会有变化'),
(@last_id, '0', '比湖泊小'),
(@last_id, '0', '水不是特别深'),
(@last_id, '0', '水不像河里的一样会流动'),
(@last_id, '0', '可以养殖荷花观赏');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鱼蛋","normal":"牛丸"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种吃的食物'),
(@last_id, '0', '圆圆的'),
(@last_id, '0', '煮火锅最适合了'),
(@last_id, '0', '吃起来味道很香'),
(@last_id, '0', '丸子一样的'),
(@last_id, '0', '需要冷藏'),
(@last_id, '0', '和汤圆差不多一样大小'),
(@last_id, '0', '口感比较Q弹'),
(@last_id, '0', '里面可以添加馅料'),
(@last_id, '0', '一般都是煮着吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"冰淇淋","normal":"冰棍"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '夏天吃的'),
(@last_id, '0', '冰的'),
(@last_id, '0', '有奶油'),
(@last_id, '0', '会融化'),
(@last_id, '0', '舔着吃'),
(@last_id, '0', '咬着吃'),
(@last_id, '0', '含着吃'),
(@last_id, '0', '吃完后有个棒棒'),
(@last_id, '0', '甜甜的'),
(@last_id, '0', '有时候上面有巧克力');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"橄榄球","normal":"篮球"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一个体育运动'),
(@last_id, '0', '团队协作完成'),
(@last_id, '0', '对体能要求很高'),
(@last_id, '0', '男的玩的比较好'),
(@last_id, '0', '美国拿过冠军'),
(@last_id, '0', '参加的人都很壮'),
(@last_id, '0', '球类运动'),
(@last_id, '0', '需要撞击'),
(@last_id, '0', '需要弹跳'),
(@last_id, '0', '装备很酷');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"朱元璋","normal":"李世民"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国的'),
(@last_id, '0', '皇帝'),
(@last_id, '0', '男的'),
(@last_id, '0', '开国皇帝创造盛世'),
(@last_id, '0', '儿子死的比他早'),
(@last_id, '0', '为人残暴'),
(@last_id, '0', '出身草根'),
(@last_id, '0', '喜欢养鸟'),
(@last_id, '0', '儿媳妇比较厉害'),
(@last_id, '0', '儿子很厉害');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"苏黎世","normal":"日内瓦"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '国外的'),
(@last_id, '0', '一个地名'),
(@last_id, '0', '欧洲的'),
(@last_id, '0', '讲英语'),
(@last_id, '0', '有运河'),
(@last_id, '0', '风景很好'),
(@last_id, '0', '瑞士的'),
(@last_id, '0', '美食很多'),
(@last_id, '0', '历史名城'),
(@last_id, '0', '有很多神话故事');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鹤顶红","normal":"氰化物"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '化学'),
(@last_id, '0', '物理'),
(@last_id, '0', '毒药'),
(@last_id, '0', '中国'),
(@last_id, '0', '外国'),
(@last_id, '0', '古代'),
(@last_id, '0', '古装剧中经常有'),
(@last_id, '0', '只要一点点就可以致死'),
(@last_id, '0', '基本上救不活'),
(@last_id, '0', '我没见过');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"联想","normal":"戴尔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电器'),
(@last_id, '0', '名牌'),
(@last_id, '0', '电脑'),
(@last_id, '0', '国产'),
(@last_id, '0', '外国的'),
(@last_id, '0', '价格比较合理'),
(@last_id, '0', '有外星人系列'),
(@last_id, '0', 'Windows操作系统'),
(@last_id, '0', '显示器比较好'),
(@last_id, '0', '鼠标不怎么好');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"夏新","normal":"波导"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '国产的'),
(@last_id, '0', '手机'),
(@last_id, '0', '便宜'),
(@last_id, '0', '过气'),
(@last_id, '0', '基本没人买'),
(@last_id, '0', '安卓系统'),
(@last_id, '0', '以前女生用比较多'),
(@last_id, '0', '厦门的企业'),
(@last_id, '0', '宁波的企业'),
(@last_id, '0', '我没用过');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"苹果","normal":"鸭梨"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '水果'),
(@last_id, '0', '多汁'),
(@last_id, '0', '红色的'),
(@last_id, '0', '黄色的'),
(@last_id, '0', '有核'),
(@last_id, '0', '便宜'),
(@last_id, '0', '讲过'),
(@last_id, '0', '补铁'),
(@last_id, '0', '可以熬着吃'),
(@last_id, '0', '切开放久了会变黄');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"千斤顶","normal":"备胎"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '汽车'),
(@last_id, '0', '汽车坏了要用'),
(@last_id, '0', '车里常备'),
(@last_id, '0', '特别好用'),
(@last_id, '0', '很重'),
(@last_id, '0', '需要把车抬起来'),
(@last_id, '0', '一般人都不会弄'),
(@last_id, '0', '修理厂的会弄'),
(@last_id, '0', '对质量要求特别高'),
(@last_id, '0', '使用时需要旋转');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"平步青云","normal":"飞黄腾达"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '成语'),
(@last_id, '0', '形容人很顺利'),
(@last_id, '0', '从草根到了人才'),
(@last_id, '0', '升官很快'),
(@last_id, '0', '出自韩愈'),
(@last_id, '0', '前面2个字是一匹马的名字'),
(@last_id, '0', '出自史记'),
(@last_id, '0', '近义词一步登天'),
(@last_id, '0', '近义词青云直上'),
(@last_id, '0', '反义词一蹶不振');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"短信","normal":"彩信"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '手机的'),
(@last_id, '0', '接收和发送信息'),
(@last_id, '0', '以前用的比较多，现在不太用'),
(@last_id, '0', '以前还会存起来，现在不太存'),
(@last_id, '0', '收到的基本都是垃圾信息现在'),
(@last_id, '0', '可以接收图片'),
(@last_id, '0', '按条数收费'),
(@last_id, '0', '沟通交流方式'),
(@last_id, '0', '现在这上面广告和骗子多'),
(@last_id, '0', '沟通成本高');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"炒饭","normal":"刀削面"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '很好吃'),
(@last_id, '0', '炒着吃'),
(@last_id, '0', '一般和青菜和蛋炒着吃'),
(@last_id, '0', '我刚刚晚饭吃过'),
(@last_id, '0', '很劲道'),
(@last_id, '0', '面食'),
(@last_id, '0', '饭食'),
(@last_id, '0', '一般小摊上卖'),
(@last_id, '0', '一般早饭不吃'),
(@last_id, '0', '一定要现场制作');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"周杰伦","normal":"王力宏"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '明星'),
(@last_id, '0', '歌手'),
(@last_id, '0', '自己会做词曲'),
(@last_id, '0', '演过电影'),
(@last_id, '0', '发过很多专辑，拿过冠军'),
(@last_id, '0', '火了十几年了'),
(@last_id, '0', '现在当爸爸了'),
(@last_id, '0', '生了个女儿'),
(@last_id, '0', '当过评委');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小文艺","normal":"小清新"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一般形容女的'),
(@last_id, '0', '是一个形容词'),
(@last_id, '0', '如果是男的话，这个男的很娘炮'),
(@last_id, '0', '很干净'),
(@last_id, '0', '一些明媚而忧伤的文章就是这样'),
(@last_id, '0', '未婚前的奶茶妹妹'),
(@last_id, '0', '关晓彤'),
(@last_id, '0', '杨紫'),
(@last_id, '0', '迪丽热巴'),
(@last_id, '0', '古力娜扎');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"海尔","normal":"海信"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电器'),
(@last_id, '0', '中国名牌'),
(@last_id, '0', '公司创立于青岛'),
(@last_id, '0', '电视很有名'),
(@last_id, '0', '冰箱、洗衣机很有名'),
(@last_id, '0', '有好几十年历史了'),
(@last_id, '0', '中国本土的牌子'),
(@last_id, '0', '这家公司已经上市'),
(@last_id, '0', '有自己的专利'),
(@last_id, '0', '有收购其他公司的经历');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"飞镖","normal":"飞刀"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '是一个游戏'),
(@last_id, '0', '不小心会伤到人'),
(@last_id, '0', '需要刻苦的练习'),
(@last_id, '0', '武侠小说中经常有'),
(@last_id, '0', '手腕需要很灵活'),
(@last_id, '0', '小小的'),
(@last_id, '0', '锋利'),
(@last_id, '0', '可以带身上'),
(@last_id, '0', '一套有很多一模一样的'),
(@last_id, '0', '有这样的比赛');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"蜜蜂","normal":"黄蜂"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '动物'),
(@last_id, '0', '有翅膀'),
(@last_id, '0', '六条腿'),
(@last_id, '0', '许多公的共用一个老婆'),
(@last_id, '0', '黄色和黑色间隔'),
(@last_id, '0', '会伤人'),
(@last_id, '0', '有针'),
(@last_id, '0', '会跳舞'),
(@last_id, '0', '成群结队'),
(@last_id, '0', '被它攻击可能会死人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"段誉","normal":"虚竹"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国的'),
(@last_id, '0', '古代的'),
(@last_id, '0', '小说人物'),
(@last_id, '0', '男的'),
(@last_id, '0', '金庸'),
(@last_id, '0', '他有结拜的兄弟'),
(@last_id, '0', '武功很高'),
(@last_id, '0', '最后娶到了自己喜欢的女人'),
(@last_id, '0', '大哥是萧峰'),
(@last_id, '0', '很多人喜欢他');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"钻石","normal":"水晶"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '很贵'),
(@last_id, '0', '最开始在山洞里'),
(@last_id, '0', '透明的'),
(@last_id, '0', '越大越贵'),
(@last_id, '0', '很硬的'),
(@last_id, '0', '女人最喜欢'),
(@last_id, '0', '证明真爱的方式'),
(@last_id, '0', '装饰用'),
(@last_id, '0', '可以戴手上'),
(@last_id, '0', '炫富');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"雷克萨斯","normal":"本田"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '车'),
(@last_id, '0', '名牌'),
(@last_id, '0', '日本的'),
(@last_id, '0', '中高档'),
(@last_id, '0', '有很多系列'),
(@last_id, '0', '两厢的比较好看'),
(@last_id, '0', '屏幕很大'),
(@last_id, '0', '避震效果很好'),
(@last_id, '0', '音响效果很好'),
(@last_id, '0', '不经撞');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"微波炉","normal":"电烤箱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电器'),
(@last_id, '0', '很多家庭主妇喜欢'),
(@last_id, '0', '热热的'),
(@last_id, '0', '会转动'),
(@last_id, '0', '有灯'),
(@last_id, '0', '温度很高'),
(@last_id, '0', '懒人必备'),
(@last_id, '0', '主要做吃的'),
(@last_id, '0', '荤的素的都可以做'),
(@last_id, '0', '一般单身狗家里不会有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"花泽类","normal":"道明寺"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '帅哥'),
(@last_id, '0', '霸道总裁'),
(@last_id, '0', '花式美男'),
(@last_id, '0', '个子很高'),
(@last_id, '0', '很痴情'),
(@last_id, '0', '最早是日本的漫画'),
(@last_id, '0', '有电视剧'),
(@last_id, '0', '台湾、日本、韩国都拍过'),
(@last_id, '0', 'F4');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"空客","normal":"波音"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '天上飞的'),
(@last_id, '0', '有翅膀的'),
(@last_id, '0', '金属制造'),
(@last_id, '0', '汽油动力'),
(@last_id, '0', '一次可以带很多人上天'),
(@last_id, '0', '有漂亮的空姐'),
(@last_id, '0', '速度很快'),
(@last_id, '0', '美国人发明的'),
(@last_id, '0', '如果出事，死亡概率大'),
(@last_id, '0', '一般人都不会');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鲜花","normal":"干花"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '植物'),
(@last_id, '0', '很美丽'),
(@last_id, '0', '很香'),
(@last_id, '0', '寿命很长'),
(@last_id, '0', '寿命不长'),
(@last_id, '0', '可以代表爱情'),
(@last_id, '0', '女生喜欢'),
(@last_id, '0', '需要瓶子才能保存'),
(@last_id, '0', '一年四季都有'),
(@last_id, '0', '养眼');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"喷气飞机","normal":"直升飞机"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '飞的'),
(@last_id, '0', '金属做的'),
(@last_id, '0', '汽油动力'),
(@last_id, '0', '喷气动力'),
(@last_id, '0', '很酷'),
(@last_id, '0', '天气好的时候可以坐'),
(@last_id, '0', '2个驾驶员'),
(@last_id, '0', '表演时候需要'),
(@last_id, '0', '拍戏的时候需要'),
(@last_id, '0', '可以做的人很少');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"江南style","normal":"最炫民族风"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '神曲'),
(@last_id, '0', '中国的'),
(@last_id, '0', '韩国的'),
(@last_id, '0', '火遍中国'),
(@last_id, '0', '火遍亚洲'),
(@last_id, '0', '火遍全世界'),
(@last_id, '0', '洗脑'),
(@last_id, '0', '可以一直循环'),
(@last_id, '0', '可以跳广场舞'),
(@last_id, '0', '阿姨很喜欢');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"台灯","normal":"壁灯"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '家里用的'),
(@last_id, '0', '酒店里用的'),
(@last_id, '0', '会发光的'),
(@last_id, '0', '浪漫'),
(@last_id, '0', '昏暗'),
(@last_id, '0', '室内用'),
(@last_id, '0', '室外用'),
(@last_id, '0', '形状多样'),
(@last_id, '0', '价格便宜'),
(@last_id, '0', '温馨');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"石榴","normal":"葡萄"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '外边有皮'),
(@last_id, '0', '皮不能吃'),
(@last_id, '0', '多汁'),
(@last_id, '0', '抗氧化'),
(@last_id, '0', '红色'),
(@last_id, '0', '绿色'),
(@last_id, '0', '紫色'),
(@last_id, '0', '黄色'),
(@last_id, '0', '一颗一颗吃'),
(@last_id, '0', '一把一把吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"姐姐","normal":"表姐"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女的'),
(@last_id, '0', '长辈'),
(@last_id, '0', '年纪不会比我大很多'),
(@last_id, '0', '有她很温暖'),
(@last_id, '0', '有她很放心'),
(@last_id, '0', '跟自己有血缘关系'),
(@last_id, '0', '她的长辈跟我的长辈有血缘关系'),
(@last_id, '0', '从小到大总爱压自己一头'),
(@last_id, '0', '成熟比我早'),
(@last_id, '0', '结婚比我早');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"姑父","normal":"姨夫"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '长辈'),
(@last_id, '0', '跟我有关系'),
(@last_id, '0', '跟我没有血缘关系'),
(@last_id, '0', '跟我爸爸的姐妹有关系'),
(@last_id, '0', '跟我妈妈的姐妹有关系'),
(@last_id, '0', '我有这样的长辈'),
(@last_id, '0', '我没有这样的长辈'),
(@last_id, '0', '他的小孩跟我同辈'),
(@last_id, '0', '过年会给我红包');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"加多宝","normal":"凉茶"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '饮料'),
(@last_id, '0', '国产的'),
(@last_id, '0', '名牌'),
(@last_id, '0', '夏天爱喝'),
(@last_id, '0', '降火'),
(@last_id, '0', '有官司'),
(@last_id, '0', '红罐的'),
(@last_id, '0', '金罐的'),
(@last_id, '0', '配方正宗'),
(@last_id, '0', '跟另一个牌子很像');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奖金","normal":"薪水"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '钱'),
(@last_id, '0', '大家都喜欢'),
(@last_id, '0', '老板发的才是好的'),
(@last_id, '0', '发之前老板会找你谈话'),
(@last_id, '0', '是对你在公司的表现的肯定'),
(@last_id, '0', '一般按月给'),
(@last_id, '0', '给多少看你的表现'),
(@last_id, '0', '有时候一次性会给你很多'),
(@last_id, '0', '一下就会花完'),
(@last_id, '0', '财务总想扣你一点');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大蒜","normal":"小葱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '绿的'),
(@last_id, '0', '白的'),
(@last_id, '0', '杀菌的'),
(@last_id, '0', '很香'),
(@last_id, '0', '我不喜欢'),
(@last_id, '0', '有的人很喜欢'),
(@last_id, '0', '植物'),
(@last_id, '0', '炒菜用的'),
(@last_id, '0', '切出来吃'),
(@last_id, '0', '去腥味');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"生姜","normal":"大蒜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '植物'),
(@last_id, '0', '白的'),
(@last_id, '0', '黄的'),
(@last_id, '0', '炒菜用'),
(@last_id, '0', '不能单独当菜吃'),
(@last_id, '0', '切出来吃'),
(@last_id, '0', '去腥味'),
(@last_id, '0', '增强免疫力'),
(@last_id, '0', '有些人很喜欢生吃'),
(@last_id, '0', '我不喜欢吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"芝麻酱","normal":"花生酱"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '植物做的'),
(@last_id, '0', '厨房里有'),
(@last_id, '0', '酱料'),
(@last_id, '0', '我不喜欢吃'),
(@last_id, '0', '我很喜欢吃'),
(@last_id, '0', '有些人吃了会过敏'),
(@last_id, '0', '很香'),
(@last_id, '0', '火锅可以吃'),
(@last_id, '0', '黄色的'),
(@last_id, '0', '浓稠的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"头发","normal":"胡须"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '毛'),
(@last_id, '0', '男生一般都留不长'),
(@last_id, '0', '女生有，但是很短小'),
(@last_id, '0', '很难打理'),
(@last_id, '0', '要打理好很费钱'),
(@last_id, '0', '每天都要修理'),
(@last_id, '0', '扎扎的'),
(@last_id, '0', '老人一般能留很长'),
(@last_id, '0', '慢慢会变白'),
(@last_id, '0', '没什么用');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"飞行员","normal":"空军"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的多'),
(@last_id, '0', '一种职业'),
(@last_id, '0', '在天上飞的'),
(@last_id, '0', '很帅气'),
(@last_id, '0', '女生都很喜欢这样的男生'),
(@last_id, '0', '制服'),
(@last_id, '0', '有降落伞'),
(@last_id, '0', '驾驶飞机的'),
(@last_id, '0', '随时有危险'),
(@last_id, '0', '有时候会给领导人表演');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"姐姐","normal":"妹妹"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女的'),
(@last_id, '0', '跟我有血缘关系的'),
(@last_id, '0', '我没有'),
(@last_id, '0', '我有2个'),
(@last_id, '0', '爸爸妈妈比较喜欢的'),
(@last_id, '0', '两个字发音是一样的'),
(@last_id, '0', '我有一个，很讨厌'),
(@last_id, '0', '会跟我抢东西吃的'),
(@last_id, '0', '要跟我分财产的'),
(@last_id, '0', '很可爱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奸雄","normal":"霸王"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男的'),
(@last_id, '0', '中国的'),
(@last_id, '0', '古代很多'),
(@last_id, '0', '项羽'),
(@last_id, '0', '人很有权力'),
(@last_id, '0', '在他身边要小心'),
(@last_id, '0', '董卓'),
(@last_id, '0', '曹操'),
(@last_id, '0', '吕布'),
(@last_id, '0', '张辽');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"超级模仿秀","normal":"百变大咖秀"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国的'),
(@last_id, '0', '综艺节目'),
(@last_id, '0', '何炅'),
(@last_id, '0', '谢娜'),
(@last_id, '0', '贾乃亮'),
(@last_id, '0', '李维嘉'),
(@last_id, '0', '王祖蓝'),
(@last_id, '0', '要化妆'),
(@last_id, '0', '要口技'),
(@last_id, '0', '对表演有很高的要求');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"四面楚歌","normal":"霸王别姬"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一个成语'),
(@last_id, '0', '讲的是项羽的故事'),
(@last_id, '0', '跟女人有关'),
(@last_id, '0', '项羽在乌江'),
(@last_id, '0', '项羽临死之前的故事'),
(@last_id, '0', '张国荣演过电影'),
(@last_id, '0', '跟唱歌有关'),
(@last_id, '0', '一个令人扼腕叹息的故事'),
(@last_id, '0', '被刘邦打败了'),
(@last_id, '0', '无心干仗');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"猪肉白菜","normal":"韭菜鸡蛋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一个菜'),
(@last_id, '0', '荤素搭配'),
(@last_id, '0', '南方人喜欢吃'),
(@last_id, '0', '东北人喜欢吃'),
(@last_id, '0', '有些人不喜欢哪个气味'),
(@last_id, '0', '一般包饺子会有这种馅'),
(@last_id, '0', '很好吃'),
(@last_id, '0', '要剁碎了混起来'),
(@last_id, '0', '可以烧汤'),
(@last_id, '0', '加点醋更好吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"联想","normal":"三星"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电器'),
(@last_id, '0', '手机'),
(@last_id, '0', '电视'),
(@last_id, '0', '显示器'),
(@last_id, '0', '韩国的'),
(@last_id, '0', '国产的'),
(@last_id, '0', '会爆炸'),
(@last_id, '0', '收购过其他企业'),
(@last_id, '0', '价格中等'),
(@last_id, '0', '型号多样');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"超级女声","normal":"我是歌手"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国的'),
(@last_id, '0', '综艺节目'),
(@last_id, '0', '主持人何炅'),
(@last_id, '0', '湖南台的'),
(@last_id, '0', '有很多明星参加'),
(@last_id, '0', '评委是明星'),
(@last_id, '0', '评委是观众'),
(@last_id, '0', '孙楠参加过'),
(@last_id, '0', '韩红参加过'),
(@last_id, '0', '李宇春参加过');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"吕雉","normal":"武则天"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女的'),
(@last_id, '0', '中国的'),
(@last_id, '0', '古代的'),
(@last_id, '0', '掌权的'),
(@last_id, '0', '很阴毒'),
(@last_id, '0', '睡过皇帝'),
(@last_id, '0', '杀过自己小孩'),
(@last_id, '0', '汉朝的'),
(@last_id, '0', '唐朝的'),
(@last_id, '0', '想当皇帝');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"吵架","normal":"分手"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种行为'),
(@last_id, '0', '男女比较多'),
(@last_id, '0', '非常激烈'),
(@last_id, '0', '大家的方式都不一样'),
(@last_id, '0', '事后会很伤心'),
(@last_id, '0', '有时候能和好'),
(@last_id, '0', '有时候不能和好'),
(@last_id, '0', '也可以是同性之间的'),
(@last_id, '0', '一定有一个冲突点'),
(@last_id, '0', '可以双人，也可以多人');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"向日葵","normal":"葵花籽"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以剥开来吃的'),
(@last_id, '0', '跟太阳有很大的关系'),
(@last_id, '0', '植物'),
(@last_id, '0', '乡下有很多'),
(@last_id, '0', '可以当零食吃'),
(@last_id, '0', '不贵'),
(@last_id, '0', '有奶油味的'),
(@last_id, '0', '有核桃味的'),
(@last_id, '0', '一包一包卖'),
(@last_id, '0', '称斤卖');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"天鹅","normal":"白鸽"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '白色的'),
(@last_id, '0', '动物'),
(@last_id, '0', '可以飞'),
(@last_id, '0', '有羽毛'),
(@last_id, '0', '有翅膀'),
(@last_id, '0', '比较肥'),
(@last_id, '0', '大城市都有'),
(@last_id, '0', '不怕人'),
(@last_id, '0', '是纯洁的象征'),
(@last_id, '0', '广场也有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"复旦","normal":"交大"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国名牌大学'),
(@last_id, '0', '985工程'),
(@last_id, '0', '211工程'),
(@last_id, '0', '在上海'),
(@last_id, '0', '公立大学'),
(@last_id, '0', '百年老校'),
(@last_id, '0', '全国排前十'),
(@last_id, '0', '江泽民毕业的大学'),
(@last_id, '0', '学校总部在杨浦区'),
(@last_id, '0', '一本录取');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"80后","normal":"90后"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '对一类人的称呼'),
(@last_id, '0', '按照出生年份划分的一类人'),
(@last_id, '0', '自我而个性'),
(@last_id, '0', '独生子女的一代'),
(@last_id, '0', '迷茫的一代'),
(@last_id, '0', 'Y一代'),
(@last_id, '0', '看《古惑仔》长大'),
(@last_id, '0', '玩魂斗罗'),
(@last_id, '0', '超级玛丽'),
(@last_id, '0', '喜洋洋和灰太狼');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"U盘","normal":"移动硬盘"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种存储设备'),
(@last_id, '0', '体积很小'),
(@last_id, '0', '方便携带'),
(@last_id, '0', '采用USB接口'),
(@last_id, '0', '以硬盘为存储介质'),
(@last_id, '0', '与电脑一起使用'),
(@last_id, '0', '电子产品'),
(@last_id, '0', '常用办公设备'),
(@last_id, '0', '内存容量有大小区分'),
(@last_id, '0', '由外壳和机芯两部分组成');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"打针","normal":"针灸"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '能治病'),
(@last_id, '0', '有疼痛感'),
(@last_id, '0', '通常在医院里进行'),
(@last_id, '0', '一种治疗措施'),
(@last_id, '0', '有一根针'),
(@last_id, '0', '扎进皮肤里'),
(@last_id, '0', '身上很多部位都可以扎'),
(@last_id, '0', '小朋友会哭'),
(@last_id, '0', '害怕'),
(@last_id, '0', '中医');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"台湾人","normal":"香港人"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国某个地区的人的称呼'),
(@last_id, '0', '说粤语'),
(@last_id, '0', '说闽南语'),
(@last_id, '0', '传统习俗保存比较好'),
(@last_id, '0', '信妈祖'),
(@last_id, '0', '跟大陆关系紧密'),
(@last_id, '0', '和大陆关系时好时坏'),
(@last_id, '0', '谢霆锋'),
(@last_id, '0', '蔡依林'),
(@last_id, '0', '1997年');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"张作霖","normal":"张学良"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '军阀'),
(@last_id, '0', '东三省'),
(@last_id, '0', '爱国将领'),
(@last_id, '0', '爱女人'),
(@last_id, '0', '张大帅'),
(@last_id, '0', '参加过甲午战争'),
(@last_id, '0', '东北王'),
(@last_id, '0', '皇姑屯事件'),
(@last_id, '0', '积极抗日'),
(@last_id, '0', '民国四大美男子之一');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"舅舅","normal":"叔叔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '对男性长辈的称呼'),
(@last_id, '0', '妈妈的兄弟'),
(@last_id, '0', '爸爸的兄弟'),
(@last_id, '0', '跟外甥特别亲'),
(@last_id, '0', '亲属的一种称呼'),
(@last_id, '0', '对于和父辈年龄相仿的人的尊称'),
(@last_id, '0', '南北方通用'),
(@last_id, '0', 'uncle'),
(@last_id, '0', '和自己同姓');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"人渣","normal":"废物"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '道德败坏的人'),
(@last_id, '0', '品格低劣的人'),
(@last_id, '0', '常用于辱骂他人'),
(@last_id, '0', '对社会毫无贡献'),
(@last_id, '0', '港台片中常见'),
(@last_id, '0', '被人唾弃的'),
(@last_id, '0', '可指人也可指物'),
(@last_id, '0', '无用的人'),
(@last_id, '0', '贬义的'),
(@last_id, '0', '不受人欢迎的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"海底捞","normal":"豆捞坊"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '火锅店'),
(@last_id, '0', '很有名'),
(@last_id, '0', '服务特别好'),
(@last_id, '0', '出书'),
(@last_id, '0', '火锅连锁品牌'),
(@last_id, '0', '源于澳门'),
(@last_id, '0', '海产品丰富'),
(@last_id, '0', '川味火锅'),
(@last_id, '0', '直营连锁'),
(@last_id, '0', '可以外卖');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"小偷","normal":"强盗"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '企图不劳而获的人'),
(@last_id, '0', '抢占他人财产'),
(@last_id, '0', '会伤及他人性命'),
(@last_id, '0', '触犯法律'),
(@last_id, '0', '社会败类'),
(@last_id, '0', '扒手'),
(@last_id, '0', '以钱财为目的'),
(@last_id, '0', '从古至今都有'),
(@last_id, '0', '偷偷摸摸'),
(@last_id, '0', '用强制力把他人钱财据为己有');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"配角","normal":"龙套"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '次要角色'),
(@last_id, '0', '喻做次要工作的人'),
(@last_id, '0', '戏剧里的角色之一'),
(@last_id, '0', '舞台上对角色的称谓'),
(@last_id, '0', '吴孟达'),
(@last_id, '0', '也叫文堂'),
(@last_id, '0', '群演'),
(@last_id, '0', '衬托主角的'),
(@last_id, '0', '不一定有台词'),
(@last_id, '0', '走过场');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"吉林","normal":"辽宁"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '东北三省之一'),
(@last_id, '0', '重工业基地'),
(@last_id, '0', '临黄海'),
(@last_id, '0', '临渤海'),
(@last_id, '0', '与俄罗斯接壤'),
(@last_id, '0', '与朝鲜一江之隔'),
(@last_id, '0', '与日本隔海相望'),
(@last_id, '0', '黑土地之乡'),
(@last_id, '0', '长白山'),
(@last_id, '0', '松花江');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"达芬奇","normal":"米开朗基罗"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '意大利人'),
(@last_id, '0', '文艺复兴时期'),
(@last_id, '0', '雕塑艺术代表人物'),
(@last_id, '0', '文艺复兴后三杰之一'),
(@last_id, '0', '小学美术课就介绍过他'),
(@last_id, '0', '有一颗以他名字命名的小行星'),
(@last_id, '0', '佛罗伦萨'),
(@last_id, '0', '有多重身份'),
(@last_id, '0', '画家'),
(@last_id, '0', '蒙娜丽莎');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"播音员","normal":"主持人"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种职业'),
(@last_id, '0', '需要发音标准'),
(@last_id, '0', '对说话和表达有很高要求'),
(@last_id, '0', '对容貌有要求'),
(@last_id, '0', '大多都是年轻人'),
(@last_id, '0', '很容易成为名人'),
(@last_id, '0', '光鲜亮丽'),
(@last_id, '0', '让人羡慕'),
(@last_id, '0', '在广播或电视台工作'),
(@last_id, '0', '不限性别');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"草鱼","normal":"鲶鱼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种鱼'),
(@last_id, '0', '可以吃'),
(@last_id, '0', '很常见'),
(@last_id, '0', '吃草'),
(@last_id, '0', '吃腐食'),
(@last_id, '0', '有的体型非常巨大'),
(@last_id, '0', '做西湖醋鱼'),
(@last_id, '0', '肉质很肥腻'),
(@last_id, '0', '有胡须'),
(@last_id, '0', '生长在淡水环境里');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"主席","normal":"总统"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '国家元首'),
(@last_id, '0', '政党领袖'),
(@last_id, '0', '习大大'),
(@last_id, '0', '奥巴马'),
(@last_id, '0', '政府首脑'),
(@last_id, '0', '社会主义国家'),
(@last_id, '0', '公司或者集团的最高负责人'),
(@last_id, '0', '共和制国家'),
(@last_id, '0', '有固定任期'),
(@last_id, '0', '选举产生');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"木瓜","normal":"黄瓜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种水果'),
(@last_id, '0', '营养丰富'),
(@last_id, '0', '可生吃，也可加热'),
(@last_id, '0', '有美容的功效'),
(@last_id, '0', '有助于减肥'),
(@last_id, '0', '口感清脆'),
(@last_id, '0', '能丰胸'),
(@last_id, '0', '有淡淡清香'),
(@last_id, '0', '长条形状'),
(@last_id, '0', '通常去皮吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"班花","normal":"女神"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '指很美的女性'),
(@last_id, '0', '受到很多男生喜欢'),
(@last_id, '0', '暗恋对象'),
(@last_id, '0', '不容易接近'),
(@last_id, '0', '特别出众'),
(@last_id, '0', '很多人追求'),
(@last_id, '0', '漂亮的女生'),
(@last_id, '0', '读书时候每个班都有'),
(@last_id, '0', '同学会时最想见到的人'),
(@last_id, '0', '被很多女生嫉妒');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"油墨","normal":"砚台"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '有一股独特的清香'),
(@last_id, '0', '文房四宝之一'),
(@last_id, '0', '黑色的'),
(@last_id, '0', '能用来书写绘画'),
(@last_id, '0', '用来印刷'),
(@last_id, '0', '避光保存'),
(@last_id, '0', '没有固定形状'),
(@last_id, '0', '可用于喷绘'),
(@last_id, '0', '中国古代开始使用'),
(@last_id, '0', '活字印刷术');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"山西","normal":"陕西"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '和方向有关'),
(@last_id, '0', '一个省份'),
(@last_id, '0', '壶口瀑布'),
(@last_id, '0', '黄土高坡'),
(@last_id, '0', '窑洞'),
(@last_id, '0', '喜欢吃面食'),
(@last_id, '0', '产小米'),
(@last_id, '0', '各种面条非常有名'),
(@last_id, '0', '小吃特别多'),
(@last_id, '0', '秦腔');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奶油","normal":"奶酪"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '奶制品'),
(@last_id, '0', '脂肪含量高'),
(@last_id, '0', '西方人很依赖它'),
(@last_id, '0', '有天然的和人造的两种'),
(@last_id, '0', '做蛋糕少不了它'),
(@last_id, '0', '大多数东方人不习惯它的味道'),
(@last_id, '0', '有一个一个的洞'),
(@last_id, '0', '有很多不同口味'),
(@last_id, '0', '成品通常成固体'),
(@last_id, '0', '中国部分少数民族的传统食品');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"麦当劳","normal":"肯德基"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '洋快餐'),
(@last_id, '0', '炸鸡'),
(@last_id, '0', '汉堡'),
(@last_id, '0', '甜筒'),
(@last_id, '0', '美国的'),
(@last_id, '0', '很早就进入中国'),
(@last_id, '0', '家喻户晓'),
(@last_id, '0', '六个翅膀的鸡'),
(@last_id, '0', '巨无霸'),
(@last_id, '0', '红色的LOGO');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"泰国","normal":"印度"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '亚洲国家'),
(@last_id, '0', '东南亚国家'),
(@last_id, '0', '很神秘'),
(@last_id, '0', '很古老'),
(@last_id, '0', '很热'),
(@last_id, '0', '不太富裕'),
(@last_id, '0', '跟中国关系密切'),
(@last_id, '0', '大象'),
(@last_id, '0', '咖喱'),
(@last_id, '0', '有强烈的宗教信仰');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"发小","normal":"闺蜜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '形容非常要好的朋友'),
(@last_id, '0', '无话不说'),
(@last_id, '0', '关系亲密'),
(@last_id, '0', '知道彼此的很多秘密'),
(@last_id, '0', '女生之间的一种关系'),
(@last_id, '0', '从小一起长大'),
(@last_id, '0', '年龄相仿'),
(@last_id, '0', '没有性别限制'),
(@last_id, '0', '形影不离'),
(@last_id, '0', '知根知底');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"路由器","normal":"机顶盒"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种连接设备'),
(@last_id, '0', '体积小巧'),
(@last_id, '0', '扁平形状'),
(@last_id, '0', '与电视机有关'),
(@last_id, '0', '与互联网有关'),
(@last_id, '0', '电视信号传输'),
(@last_id, '0', '互联网络的枢纽'),
(@last_id, '0', '广泛应用于各个行业'),
(@last_id, '0', '家庭日常拥有'),
(@last_id, '0', '家庭使用非常方便');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"信用卡","normal":"储蓄卡"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '银行卡的一种'),
(@last_id, '0', '金融产品'),
(@last_id, '0', '有磁条和芯片两种'),
(@last_id, '0', '金融交易卡'),
(@last_id, '0', '可以存钱'),
(@last_id, '0', '可以取钱'),
(@last_id, '0', '可实现刷卡消费'),
(@last_id, '0', '非现金交易付款方式'),
(@last_id, '0', '要定期还款'),
(@last_id, '0', '向银行借钱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"本拉登","normal":"萨达姆"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '领袖人物'),
(@last_id, '0', '阿拉伯人'),
(@last_id, '0', '在某一组织或团体中享有极高声望'),
(@last_id, '0', '已经死了'),
(@last_id, '0', '全世界闻名'),
(@last_id, '0', '反面人物'),
(@last_id, '0', '911'),
(@last_id, '0', '基地组织'),
(@last_id, '0', '伊拉克战争'),
(@last_id, '0', '海湾战争');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大衣","normal":"风衣"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种衣服款式'),
(@last_id, '0', '秋冬天穿的'),
(@last_id, '0', '男女都有'),
(@last_id, '0', '通常比较贵'),
(@last_id, '0', '对面料和剪裁要求很高'),
(@last_id, '0', '挡风效果很好'),
(@last_id, '0', '保暖效果不错'),
(@last_id, '0', '穿着它会很潇洒'),
(@last_id, '0', '通常比较长'),
(@last_id, '0', '通常比较薄');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"西红柿炒鸡蛋","normal":"黄瓜炒鸡蛋"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一道家常菜'),
(@last_id, '0', '全国各地都有'),
(@last_id, '0', '一种鸡蛋的炒法'),
(@last_id, '0', '奥运会入场式时候对中国队的称呼'),
(@last_id, '0', '有股清香'),
(@last_id, '0', '我会做的第一道菜'),
(@last_id, '0', '黄和绿'),
(@last_id, '0', '黄和红'),
(@last_id, '0', '价格很实惠'),
(@last_id, '0', '蔬菜和鸡蛋的组合');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"舞蹈学院","normal":"电影学院"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '教授专门学科的大学'),
(@last_id, '0', '女生都特别美'),
(@last_id, '0', '男生都特别帅'),
(@last_id, '0', '出明星的地方'),
(@last_id, '0', '教人表演的学校'),
(@last_id, '0', '一种专业学院'),
(@last_id, '0', '明星梦实现的地方'),
(@last_id, '0', '北京有很多'),
(@last_id, '0', '学费很贵'),
(@last_id, '0', '属于艺术类院校');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"双色球","normal":"大乐透"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '彩票的一种'),
(@last_id, '0', '每周开奖三天'),
(@last_id, '0', '单注价格2元'),
(@last_id, '0', '福利彩票'),
(@last_id, '0', '体育彩票'),
(@last_id, '0', '单注2元'),
(@last_id, '0', '全国都有'),
(@last_id, '0', '可追加投注'),
(@last_id, '0', '通常会电视直播开奖'),
(@last_id, '0', '最高开出过4亿多的奖金');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"流行乐","normal":"古典乐"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种音乐风格'),
(@last_id, '0', '巴赫是典型代表'),
(@last_id, '0', '从西方开始'),
(@last_id, '0', '历史非常非常久'),
(@last_id, '0', '又分很多的流派'),
(@last_id, '0', '很通俗易懂'),
(@last_id, '0', '大多数人都听过'),
(@last_id, '0', '年轻人比较喜欢'),
(@last_id, '0', '年长者会比较喜欢'),
(@last_id, '0', '非常高雅');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"年糕","normal":"饺子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '小吃的一种'),
(@last_id, '0', '地方特色美食'),
(@last_id, '0', '北方吃的比较多'),
(@last_id, '0', '有很多种吃法'),
(@last_id, '0', '地域特色浓'),
(@last_id, '0', '过年的时候少不了'),
(@last_id, '0', '一种面食'),
(@last_id, '0', '一种米制品'),
(@last_id, '0', '能当主食'),
(@last_id, '0', '有很多种馅儿');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"汉武帝","normal":"秦始皇"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '中国古代历史中的一位皇帝'),
(@last_id, '0', '非常有成就的历史人物'),
(@last_id, '0', '小学历史课本里有介绍'),
(@last_id, '0', '中国历史上著名的改革家'),
(@last_id, '0', '铁腕'),
(@last_id, '0', '中国第一位皇帝'),
(@last_id, '0', '万里长城'),
(@last_id, '0', '统一度量衡'),
(@last_id, '0', '年少登基'),
(@last_id, '0', '罢黜百家，独尊儒术');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"豆浆油条","normal":"牛奶面包"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '经典早餐搭配'),
(@last_id, '0', '有干有湿'),
(@last_id, '0', '味道非常好'),
(@last_id, '0', '随处可得'),
(@last_id, '0', '不贵'),
(@last_id, '0', '吃得饱又吃得好'),
(@last_id, '0', '西方传入'),
(@last_id, '0', '一种早餐组合'),
(@last_id, '0', '传统的吃法'),
(@last_id, '0', '古早味');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"烟台","normal":"青岛"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '城市名'),
(@last_id, '0', '山东的城市'),
(@last_id, '0', '海边城市'),
(@last_id, '0', '吃海鲜'),
(@last_id, '0', '喝啤酒'),
(@last_id, '0', '很美'),
(@last_id, '0', '凉爽'),
(@last_id, '0', '产水果'),
(@last_id, '0', '东方瑞士'),
(@last_id, '0', '道教发祥地');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"臭豆腐","normal":"榴莲"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一种食物'),
(@last_id, '0', '味道非常浓烈'),
(@last_id, '0', '味道不太好闻'),
(@last_id, '0', '很多人爱，也很多人讨厌'),
(@last_id, '0', '闻起来臭，吃起来香'),
(@last_id, '0', '营养丰富'),
(@last_id, '0', '颜色是黄黄的'),
(@last_id, '0', '可以当零食来吃'),
(@last_id, '0', '热量很高'),
(@last_id, '0', '气味很容易传播');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"华为","normal":"中兴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '国产手机品牌'),
(@last_id, '0', '民族振兴的代表'),
(@last_id, '0', '民营通讯科技公司'),
(@last_id, '0', '产品和服务出口到世界各地'),
(@last_id, '0', '拿下5G时代'),
(@last_id, '0', '民族品牌'),
(@last_id, '0', '任正非'),
(@last_id, '0', '很低调'),
(@last_id, '0', '中国最大的通讯设备上市公司'),
(@last_id, '0', '在深圳和上海两地上市');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鲸鱼","normal":"鲨鱼"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '生活在大海里'),
(@last_id, '0', '有杀伤力'),
(@last_id, '0', '嗜血'),
(@last_id, '0', '体型很大'),
(@last_id, '0', '哺乳动物'),
(@last_id, '0', '一种鱼'),
(@last_id, '0', '会喷水'),
(@last_id, '0', '常遭人捕杀'),
(@last_id, '0', '有很多以此为题材的电影'),
(@last_id, '0', '深海里活动');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"屋顶","normal":"屋檐"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '房屋结构的组成部分'),
(@last_id, '0', '比较高'),
(@last_id, '0', '建筑学里的专业术语'),
(@last_id, '0', '有平的和尖的两种'),
(@last_id, '0', '国外有很多圆形的设计'),
(@last_id, '0', '燕子很喜欢'),
(@last_id, '0', '无论几层楼的房子都有'),
(@last_id, '0', '各种材质结构的房屋都有'),
(@last_id, '0', '房屋前后坡的边缘部分'),
(@last_id, '0', '周杰伦有一首歌');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"防晒霜","normal":"润肤乳"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '滋润皮肤用的'),
(@last_id, '0', '一种护肤品'),
(@last_id, '0', '一种乳液'),
(@last_id, '0', '对皮肤有保护功能'),
(@last_id, '0', '脸上和身上都能用'),
(@last_id, '0', '夏天用的比较多'),
(@last_id, '0', '通常会黏黏的'),
(@last_id, '0', '一年四季都使用'),
(@last_id, '0', '男女都可以使用'),
(@last_id, '0', '阻挡光照');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奶瓶","normal":"奶粉"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '每个婴儿都会用到的'),
(@last_id, '0', '会和水发生关系'),
(@last_id, '0', '有的很便宜，有的很贵'),
(@last_id, '0', '很多妈妈都会买国外牌子'),
(@last_id, '0', '婴儿会持续用到两三岁'),
(@last_id, '0', '很牛奶有关'),
(@last_id, '0', '小孩用起来很费，经常续购'),
(@last_id, '0', '跟食物有关'),
(@last_id, '0', '有助于宝宝成长'),
(@last_id, '0', '罐装型');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"丝瓜","normal":"黄瓜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '蔬菜'),
(@last_id, '0', '营养价值高'),
(@last_id, '0', '吃了对身体好'),
(@last_id, '0', '夏天吃的比较多'),
(@last_id, '0', '一般都要去皮吃'),
(@last_id, '0', '外面跟里面的颜色不太一样'),
(@last_id, '0', '价格不贵'),
(@last_id, '0', '我喜欢吃熟的'),
(@last_id, '0', '美容'),
(@last_id, '0', '长在藤上的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"安静","normal":"宁静"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一首歌里面有这个词'),
(@last_id, '0', '形容周边环境声音很小'),
(@last_id, '0', '一般会和教室有关'),
(@last_id, '0', '上课时老师会提这个要求'),
(@last_id, '0', '盛夏的午后'),
(@last_id, '0', '形容词'),
(@last_id, '0', '梁静茹有首歌是这个'),
(@last_id, '0', '周杰伦有首歌是这个'),
(@last_id, '0', '形容心情的平静');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大话西游","normal":"西游降魔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟周星驰有关'),
(@last_id, '0', '跟孙悟空有关'),
(@last_id, '0', '里面有牛魔王'),
(@last_id, '0', '神话故事'),
(@last_id, '0', '电影片名'),
(@last_id, '0', '故事经过改编翻拍'),
(@last_id, '0', '月光宝盒'),
(@last_id, '0', '里面的唐僧有点厉害的'),
(@last_id, '0', '经典台词和“一万年”有关'),
(@last_id, '0', '一个男人的自传');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"世袭制","normal":"禅让制"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '要两个人才能完成的事情'),
(@last_id, '0', '一般是皇亲贵族，跟普通老百姓没啥关系'),
(@last_id, '0', '爸爸给儿子的'),
(@last_id, '0', '帝王家的事'),
(@last_id, '0', '传男不传女'),
(@last_id, '0', '奴隶社会时期的事情'),
(@last_id, '0', '继承人'),
(@last_id, '0', '在我国已经被取代了'),
(@last_id, '0', '跟国家掌权者有关'),
(@last_id, '0', '古代的君主制度');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"果粒橙","normal":"美汁源"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '热带水果'),
(@last_id, '0', '橙汁味'),
(@last_id, '0', '能看见果粒'),
(@last_id, '0', '吃火锅的时候我会喝'),
(@last_id, '0', '胡歌代言'),
(@last_id, '0', '陈奕迅做过广告'),
(@last_id, '0', '除了橙汁味其实别的口味也很好喝'),
(@last_id, '0', '黄色的'),
(@last_id, '0', '有大瓶也有小瓶'),
(@last_id, '0', '可口可乐的一个品牌');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"柠檬汁","normal":"鲜橙汁"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '原材料很常见'),
(@last_id, '0', '水果味'),
(@last_id, '0', '很多人都爱喝'),
(@last_id, '0', '可以自己在家做'),
(@last_id, '0', '补充维生素'),
(@last_id, '0', '一般鲜榨的更好喝'),
(@last_id, '0', '每天一杯很美容'),
(@last_id, '0', '黄色的'),
(@last_id, '0', '有大瓶也有小瓶'),
(@last_id, '0', '汇源');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"文成公主","normal":"太平公主"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女的'),
(@last_id, '0', '被拍成过影视作品'),
(@last_id, '0', '古代人'),
(@last_id, '0', '皇帝的女儿'),
(@last_id, '0', '几乎拥有天下的公主'),
(@last_id, '0', '足智多谋，对政治很敏感'),
(@last_id, '0', '唐朝的'),
(@last_id, '0', '为国家做过很大贡献'),
(@last_id, '0', '周迅演过她'),
(@last_id, '0', '历史上关于她的事迹还挺多的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"青春期","normal":"更年期"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '某个年龄阶段的特定描述'),
(@last_id, '0', '行为会和其他时候不同'),
(@last_id, '0', '会有一些比较明显的症状'),
(@last_id, '0', '其实男女都有这种现象'),
(@last_id, '0', '容易生气'),
(@last_id, '0', '动不动就跟人吵架'),
(@last_id, '0', '妈妈会很烦躁'),
(@last_id, '0', '一种生理上的特定时期'),
(@last_id, '0', '不是病'),
(@last_id, '0', '不可避免的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"办公桌","normal":"写字台"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '四条腿'),
(@last_id, '0', '每个办公室都有'),
(@last_id, '0', '鞋子要用的'),
(@last_id, '0', '带抽屉'),
(@last_id, '0', '一般会和椅子配套'),
(@last_id, '0', '白色居多'),
(@last_id, '0', '去宜家能买到'),
(@last_id, '0', '木头材料'),
(@last_id, '0', '到我大腿这么高'),
(@last_id, '0', '一般放在书房');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"白板","normal":"黑板"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '能写字的'),
(@last_id, '0', '老师会用到'),
(@last_id, '0', '教学用品'),
(@last_id, '0', '面积很大的一块'),
(@last_id, '0', '可以挂在墙上，也可以放在支架上'),
(@last_id, '0', '要用和它颜色不同的笔写才能看到'),
(@last_id, '0', '可以画画'),
(@last_id, '0', '要用特殊的笔写'),
(@last_id, '0', '写完可以擦掉'),
(@last_id, '0', '会产生灰尘');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"蜡笔","normal":"粉笔"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '可以写字'),
(@last_id, '0', '笔的一种'),
(@last_id, '0', '小孩经常要用'),
(@last_id, '0', '孩子们很喜欢'),
(@last_id, '0', '学校里会有'),
(@last_id, '0', '写字、画画都能用'),
(@last_id, '0', '有很多颜色'),
(@last_id, '0', '老师的教书神器'),
(@last_id, '0', '不能吃'),
(@last_id, '0', '含有氧化钙');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"周芷若","normal":"岳灵珊"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女的'),
(@last_id, '0', '会武功'),
(@last_id, '0', '长得很漂亮'),
(@last_id, '0', '金庸笔下的人物'),
(@last_id, '0', '高圆圆'),
(@last_id, '0', '最后的下场比较惨'),
(@last_id, '0', '武侠小说'),
(@last_id, '0', '深得书中男主人公喜爱'),
(@last_id, '0', '长头发'),
(@last_id, '0', '拍成连续剧了');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"橘子","normal":"柠檬"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '圆圆的'),
(@last_id, '0', '黄色'),
(@last_id, '0', '剥皮吃'),
(@last_id, '0', '里面是一瓣一瓣的'),
(@last_id, '0', '酸'),
(@last_id, '0', '可以榨果汁'),
(@last_id, '0', '我很喜欢吃'),
(@last_id, '0', '水果'),
(@last_id, '0', '很便宜'),
(@last_id, '0', '冬天吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"北京人在纽约","normal":"北京遇上西雅图"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电影片名'),
(@last_id, '0', '已经上映了'),
(@last_id, '0', '讲述中国人在国外的故事'),
(@last_id, '0', '境外拍摄剧'),
(@last_id, '0', '折射出东西文化的差异'),
(@last_id, '0', '折射出国热潮'),
(@last_id, '0', '爱情喜剧片'),
(@last_id, '0', '男主演很帅'),
(@last_id, '0', '女神很漂亮'),
(@last_id, '0', '跟美国有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"毯子","normal":"被子"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '御寒'),
(@last_id, '0', '盖在身上'),
(@last_id, '0', '冬天要用'),
(@last_id, '0', '天冷离不开'),
(@last_id, '0', '晚上睡觉必须有'),
(@last_id, '0', '很暖和'),
(@last_id, '0', '有很多材料制成的'),
(@last_id, '0', '纺织用品'),
(@last_id, '0', '温暖'),
(@last_id, '0', '家家必备');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"红烧排骨","normal":"糖醋里脊"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '菜名'),
(@last_id, '0', '有肉'),
(@last_id, '0', '甜甜的'),
(@last_id, '0', '放酱油'),
(@last_id, '0', '特别下饭'),
(@last_id, '0', '要用猪肉'),
(@last_id, '0', '妈妈的拿手菜'),
(@last_id, '0', '很不错的家常菜'),
(@last_id, '0', '要放糖'),
(@last_id, '0', '有骨头');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"海啸","normal":"台风"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '自然现象'),
(@last_id, '0', '伴随风'),
(@last_id, '0', '会造成财产损失'),
(@last_id, '0', '气象学名词'),
(@last_id, '0', '在沿海城市活动'),
(@last_id, '0', '印度曾发生过很厉害的'),
(@last_id, '0', '跟海有关'),
(@last_id, '0', '破坏性很强'),
(@last_id, '0', '伴随着水'),
(@last_id, '0', '日本曾遭遇严重灾害');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"鼻子","normal":"嘴巴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '器官'),
(@last_id, '0', '长在脸上'),
(@last_id, '0', '可以通气'),
(@last_id, '0', '有洞洞'),
(@last_id, '0', '可以进东西，也可以出东西'),
(@last_id, '0', '呼吸'),
(@last_id, '0', '七窍之一'),
(@last_id, '0', '靠近眼睛'),
(@last_id, '0', '靠近耳朵'),
(@last_id, '0', '会有液体');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"苹果","normal":"梨"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '水果'),
(@last_id, '0', '圆圆的'),
(@last_id, '0', '甜'),
(@last_id, '0', '水分很多'),
(@last_id, '0', '每天一个，远离疾病'),
(@last_id, '0', '长在树上'),
(@last_id, '0', '有很多与它有关的谚语'),
(@last_id, '0', '北方种植的品种较好'),
(@last_id, '0', '里面是白白的'),
(@last_id, '0', '可去皮，也可带皮吃');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"星星","normal":"流星"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '天上的'),
(@last_id, '0', '会发光'),
(@last_id, '0', '天文名词'),
(@last_id, '0', '离地球很远'),
(@last_id, '0', '会用人的名字来命名'),
(@last_id, '0', '要晚上才能看见'),
(@last_id, '0', '有时只有一颗，有时有很多'),
(@last_id, '0', '云多的日子看不见'),
(@last_id, '0', '它本身是不会发光的'),
(@last_id, '0', '很美');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"金牛座","normal":"白羊座"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '十二星座之一'),
(@last_id, '0', '跟动物有关'),
(@last_id, '0', '生日在上半年'),
(@last_id, '0', '会在四月份过生日'),
(@last_id, '0', '看重金钱'),
(@last_id, '0', '喜欢挑战'),
(@last_id, '0', '充满激情的星座'),
(@last_id, '0', '对这个星座的人不太了解'),
(@last_id, '0', '这个星座的人可以和处女座做好朋友'),
(@last_id, '0', '头上有两个角的动物');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"天秤座","normal":"摩羯座"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '十二星座之一'),
(@last_id, '0', '一般在下半年过生日'),
(@last_id, '0', '很敏感'),
(@last_id, '0', '有点闷骚'),
(@last_id, '0', '对感情谨慎'),
(@last_id, '0', '不清楚，反正我不是这个星座的'),
(@last_id, '0', '跟天蝎座很近'),
(@last_id, '0', '跟水瓶座相差不超过3个月'),
(@last_id, '0', '和金牛座最配'),
(@last_id, '0', '有选择恐惧症');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"圣经","normal":"佛经"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '经书'),
(@last_id, '0', '很厚很厚的书'),
(@last_id, '0', '已经被翻译成很多国家的语言'),
(@last_id, '0', '一般信教徒都会人手一本'),
(@last_id, '0', '传教布道的工具'),
(@last_id, '0', '这本书的地位很高'),
(@last_id, '0', '存在几千年了'),
(@last_id, '0', '并不是一个人写的'),
(@last_id, '0', '写书历时一千多年'),
(@last_id, '0', '非中国原创，是被翻译成汉文的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"女神","normal":"女生"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '女孩子'),
(@last_id, '0', '不管年纪多大，都可以被这样称呼'),
(@last_id, '0', '容貌姣好'),
(@last_id, '0', '我很喜欢'),
(@last_id, '0', '男性对女性的一种称呼'),
(@last_id, '0', '如雅典娜'),
(@last_id, '0', '郭沫若出过诗集'),
(@last_id, '0', '如果再多加一个字，意思便大不同'),
(@last_id, '0', '宅男最爱');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"情人","normal":"炮友"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男人女人都可以有'),
(@last_id, '0', '爱情伦理关系'),
(@last_id, '0', '见不得人'),
(@last_id, '0', '会在床上发生关系'),
(@last_id, '0', '一般会比较固定'),
(@last_id, '0', '性伙伴'),
(@last_id, '0', '不正当恋爱关系'),
(@last_id, '0', '描述两个人的关系'),
(@last_id, '0', '有时候需要花钱稳定感情'),
(@last_id, '0', '如果被人知道会遭受指指点点');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"柯震东","normal":"房祖名"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '男人'),
(@last_id, '0', '单眼皮'),
(@last_id, '0', '演电影'),
(@last_id, '0', '是个艺人'),
(@last_id, '0', '吸毒被抓'),
(@last_id, '0', '如今已经大不如前了'),
(@last_id, '0', '演过很多脍炙人口的影视作品'),
(@last_id, '0', '获过奖'),
(@last_id, '0', '和好友一起吸毒被抓'),
(@last_id, '0', '14年被抓的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"跑步","normal":"竞走"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '体育运动'),
(@last_id, '0', '要用脚'),
(@last_id, '0', '以速度取胜'),
(@last_id, '0', '奥运会比赛项目'),
(@last_id, '0', '有助于减肥'),
(@last_id, '0', '锻炼身体的方式'),
(@last_id, '0', '手脚并用'),
(@last_id, '0', '比走路要快'),
(@last_id, '0', '比赛里程很长'),
(@last_id, '0', '双脚不能同时离地');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"娃哈哈","normal":"爽歪歪"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '儿童饮料'),
(@last_id, '0', '童年记忆'),
(@last_id, '0', '小孩比较爱喝'),
(@last_id, '0', '第二个字和第三个字是一样的'),
(@last_id, '0', '总部在杭州'),
(@last_id, '0', '含有多种矿物质'),
(@last_id, '0', '一般不按瓶卖，要一排一排卖'),
(@last_id, '0', '乳饮料'),
(@last_id, '0', '宗庆后'),
(@last_id, '0', '液体是白色的');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"被罩","normal":"枕套"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '居家用品'),
(@last_id, '0', '睡觉要用'),
(@last_id, '0', '家家必备'),
(@last_id, '0', '床上用品'),
(@last_id, '0', '四件套之一'),
(@last_id, '0', '纺织用品'),
(@last_id, '0', '每个人家里肯定不止一套'),
(@last_id, '0', '方形的'),
(@last_id, '0', '可以往里面塞东西'),
(@last_id, '0', '可以随时换洗');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"购物","normal":"刷卡"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '要花钱的'),
(@last_id, '0', '商场里产生的一种行为'),
(@last_id, '0', '离不开钱'),
(@last_id, '0', '可以买到东西'),
(@last_id, '0', '消费行为'),
(@last_id, '0', '买东西'),
(@last_id, '0', '完事儿了会给你一张小票'),
(@last_id, '0', '可以买实物，也可以买虚拟物品'),
(@last_id, '0', '会经常去做的一件事');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"红灯区","normal":"按摩房"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '红色的灯'),
(@last_id, '0', '男人爱去'),
(@last_id, '0', '情爱场所'),
(@last_id, '0', '衣着暴露'),
(@last_id, '0', '去了要花钱的'),
(@last_id, '0', '要被警察抓的'),
(@last_id, '0', '在某些国家合法'),
(@last_id, '0', '一般会躺着'),
(@last_id, '0', '会有肌肤接触'),
(@last_id, '0', '去完出来会很舒服');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"大保健","normal":"足浴"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '按摩'),
(@last_id, '0', '一般女性来服务'),
(@last_id, '0', '要花钱的'),
(@last_id, '0', '每个地方都有这种店'),
(@last_id, '0', '男人比较爱去'),
(@last_id, '0', '服务行业'),
(@last_id, '0', '消费行为'),
(@last_id, '0', '服务身体某个部位'),
(@last_id, '0', '别人来服务你'),
(@last_id, '0', '会有肌肤的接触');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"列宁格勒","normal":"圣彼得堡"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '和俄罗斯有关'),
(@last_id, '0', '国外的城市'),
(@last_id, '0', '城市名称'),
(@last_id, '0', '靠近波罗的海'),
(@last_id, '0', '沿海城市'),
(@last_id, '0', '被称为俄罗斯的“北方首都”'),
(@last_id, '0', '工业城市'),
(@last_id, '0', '很漂亮的旅游城市'),
(@last_id, '0', '改过名字'),
(@last_id, '0', '俄罗斯的教育之都');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"西游记","normal":"封神榜"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '电视剧'),
(@last_id, '0', '里面的人会飞'),
(@last_id, '0', '故事背景在古代'),
(@last_id, '0', '正义打败邪恶的故事'),
(@last_id, '0', '有很多神仙'),
(@last_id, '0', '根据小说拍成影视作品'),
(@last_id, '0', '故事内容都是虚构的'),
(@last_id, '0', '童年记忆'),
(@last_id, '0', '已经翻拍过很多版'),
(@last_id, '0', '一到寒暑假就会放');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"周瑜","normal":"诸葛亮"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '三国演义的人物'),
(@last_id, '0', '聪明，智商高'),
(@last_id, '0', '既生瑜何生亮'),
(@last_id, '0', '赤壁之战'),
(@last_id, '0', '治世能臣'),
(@last_id, '0', '文笔很好'),
(@last_id, '0', '东汉末年名将'),
(@last_id, '0', '史上的一个奇才'),
(@last_id, '0', '和曹操对着干'),
(@last_id, '0', '与“刘”字有关');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"妖怪","normal":"怪物"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '怪异的物类'),
(@last_id, '0', '小孩看了会害怕'),
(@last_id, '0', '超自然生命'),
(@last_id, '0', '小妖精一类的'),
(@last_id, '0', '神话故事中总会出现'),
(@last_id, '0', '有生命气息的'),
(@last_id, '0', '一般由动物演变而来'),
(@last_id, '0', '史莱克'),
(@last_id, '0', '长相很奇异'),
(@last_id, '0', '总是与奥特曼一起出现');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"卧底","normal":"线人"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '是一个人'),
(@last_id, '0', '形容某种身份'),
(@last_id, '0', '跟警察有关'),
(@last_id, '0', '和犯罪有关'),
(@last_id, '0', '给警察提供破案信息'),
(@last_id, '0', '一般不能被人知道'),
(@last_id, '0', '秘密活动的'),
(@last_id, '0', '有生命危险'),
(@last_id, '0', '通过贩卖信息获得报酬'),
(@last_id, '0', '警察获取情报的来源之一');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"艾滋病","normal":"传染病"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '疾病'),
(@last_id, '0', '可以从一个人发生到另一个人身上去'),
(@last_id, '0', '感染病'),
(@last_id, '0', '很危险的'),
(@last_id, '0', '得了这种病会被人疏离'),
(@last_id, '0', '大家都很害怕跟患这种病的人接触'),
(@last_id, '0', '能够通过途径感染别人'),
(@last_id, '0', '治不好'),
(@last_id, '0', '会通过性爱传播'),
(@last_id, '0', '如果怀孕的话会感染给小孩');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"奔跑吧兄弟","normal":"全员加速中"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '综艺节目'),
(@last_id, '0', '在电视或者网上可以看到'),
(@last_id, '0', '大型游戏秀节目'),
(@last_id, '0', '非原创，买了国外的节目版权'),
(@last_id, '0', '已经录播了好几季'),
(@last_id, '0', '黄晓明做客过'),
(@last_id, '0', '有王宝强'),
(@last_id, '0', '很多男女明星一起出演'),
(@last_id, '0', '需要完成任务'),
(@last_id, '0', '每一期都会有嘉宾');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"氢气","normal":"氮气"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '气体'),
(@last_id, '0', '透明的'),
(@last_id, '0', '无色无味'),
(@last_id, '0', '空气中就有它'),
(@last_id, '0', '化学用语'),
(@last_id, '0', '可以变成液体'),
(@last_id, '0', '会凝固成雪花状'),
(@last_id, '0', '化学式用一个字母＋2来表示'),
(@last_id, '0', '从水里能找到它'),
(@last_id, '0', '可引起爆炸');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"模拟城市","normal":"虚拟人生"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '一款游戏'),
(@last_id, '0', '模拟类游戏'),
(@last_id, '0', '城市建造'),
(@last_id, '0', '上世纪90年代就有了'),
(@last_id, '0', '可以开展一段虚拟的生活'),
(@last_id, '0', '有各种职业角色供你选择'),
(@last_id, '0', '画面制作非常精美'),
(@last_id, '0', '我没玩过这款游戏'),
(@last_id, '0', '没有打斗、感觉更像智力游戏'),
(@last_id, '0', '适合女生玩');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"七喜","normal":"雪碧"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '白色的'),
(@last_id, '0', '碳酸饮料'),
(@last_id, '0', '柠檬味'),
(@last_id, '0', '带气泡'),
(@last_id, '0', '如果摇一摇再打开会喷泡沫'),
(@last_id, '0', '拍了很多微电影'),
(@last_id, '0', '里面含有二氧化碳'),
(@last_id, '0', '不宜多喝，对身体不好'),
(@last_id, '0', '冰镇过后更好喝'),
(@last_id, '0', '出了很多新口味');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"天秤座","normal":"天蝎座"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '十二星座之一'),
(@last_id, '0', '带有“天”字'),
(@last_id, '0', '生日在下半年'),
(@last_id, '0', '十一月份会过生日'),
(@last_id, '0', '个性冷漠'),
(@last_id, '0', '感觉很高冷'),
(@last_id, '0', '和摩羯座很靠近'),
(@last_id, '0', '和双鱼座特别搭'),
(@last_id, '0', '身边没有这个星座的朋友');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"打麻将","normal":"打豆豆"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '消遣时间的项目'),
(@last_id, '0', '很好玩的'),
(@last_id, '0', '一个动作'),
(@last_id, '0', '需要靠手来完成'),
(@last_id, '0', '有“打”字'),
(@last_id, '0', '游戏'),
(@last_id, '0', '在电脑上玩'),
(@last_id, '0', '老少皆宜'),
(@last_id, '0', '人多才好玩'),
(@last_id, '0', '会让人很兴奋');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"麻辣烫","normal":"火锅"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '不辣不好吃'),
(@last_id, '0', '需要用水煮熟'),
(@last_id, '0', '通常配着蘸料吃'),
(@last_id, '0', '一说就会想到四川和重庆'),
(@last_id, '0', '中国独创美食'),
(@last_id, '0', '到了冬天就想吃'),
(@last_id, '0', '要放肉才好吃'),
(@last_id, '0', '人多才好吃'),
(@last_id, '0', '蔬菜肉类一起吃'),
(@last_id, '0', '川味特色');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"优乐美","normal":"香飘飘"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '饮料'),
(@last_id, '0', '粉末状'),
(@last_id, '0', '小饿小困要吃的'),
(@last_id, '0', '用开水冲泡才能喝'),
(@last_id, '0', '有习惯'),
(@last_id, '0', '多种口味供选择'),
(@last_id, '0', '奶香味'),
(@last_id, '0', '奶茶'),
(@last_id, '0', '可以从固体变为液体'),
(@last_id, '0', '里面有珍珠');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"乐不思蜀","normal":"得意忘形"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '四字成语'),
(@last_id, '0', '形容人高兴过了头'),
(@last_id, '0', '带有贬义色彩'),
(@last_id, '0', '有点失态'),
(@last_id, '0', '表示一个人很快乐'),
(@last_id, '0', '根据古时的典故而来'),
(@last_id, '0', '忘乎所以'),
(@last_id, '0', '一词多义，有好有坏'),
(@last_id, '0', '同义词忘乎所以');
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES (0, '1', '{"spy":"葵花点穴手","normal":"佛山无影脚"}');
SET @last_id = (SELECT `id` FROM `game_setting` ORDER BY `id` DESC LIMIT 1);
INSERT INTO `game_setting` (`rid`, `type`, `content`) VALUES 
(@last_id, '0', '跟影视剧有关'),
(@last_id, '0', '武术'),
(@last_id, '0', '用四肢施展开来'),
(@last_id, '0', '速度第一'),
(@last_id, '0', '准确性、命中率很高'),
(@last_id, '0', '古装背景'),
(@last_id, '0', '会给对方造成很大的杀伤力'),
(@last_id, '0', '武功名字有点长'),
(@last_id, '0', '男性使用的技能');
