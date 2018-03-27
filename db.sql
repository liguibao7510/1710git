/*
 * 作者：laowang
 * 技术交流论坛：www.phper.video
 * 官方QQ群：188386255
 * 框架版本：ThinkPHP V 5.1
 * 【功能】：userrelated配置文件
 * MySQL版本：>=5.5
 * 版本号 V 1.0
 */

 	
-- 【开发版本：微信版和APP，此为接口开发，web和app均可使用】

-- 模块
-- ①：商品(产品)(产品信息独立模板)
    -- 品牌表
    create table if not exists cf_brand(
        brd_id int(11) unsigned not null auto_increment primary key comment '主键id',
        brd_name varchar(32) not null comment '品牌名称',
        brd_product_classify int(11) unsigned not null default 0 comment '所属分类',
        brd_img varchar(128) not null default '' comment '图标',
        brd_description varchar(128) not null default '' comment '描述',
        brd_index smallint(5) unsigned not null default 0 comment '排序',
        key (brd_product_classify),
        key (brd_index)
    )engine=Innodb charset=utf8mb4 comment '品牌表';

    -- 产品表
    create table if not exists cf_product(
        prdt_id int(11) unsigned not null auto_increment primary key comment '主键id',
        prdt_name varchar(64) not null comment '产品名称',
        prdt_brand int(11) unsigned not null default 0 comment '品牌',
        prdt_classify smallint(5) unsigned not null default 0 comment '分类',
        prdt_cover_img varchar(128) not null default '' comment '产品封面图片',
        prdt_video varchar(128) not null default '' comment '产品视频',
        prdt_length smallint(5) not null default 0 comment '产品长度',
        prdt_width smallint(5) not null default 0 comment '产品宽度',
        prdt_heigth smallint(5) not null default 0 comment '产品高度',
        prdt_mweight int(11) not null default 0 comment '毛重',
        prdt_pweight int(11) not null default 0 comment '皮重',
        prdt_jweight int(11) not null default 0 comment '净重',
        prdt_country smallint(5) not null default 0 comment '产品产国',
        prdt_province smallint(5) not null default 0 comment '产地省份',
        prdt_city smallint(5) not null default 0 comment '产地城市',
        prdt_zone smallint(5) not null default 0 comment '产地区域',
        prdt_address varchar(128) not null comment '产地具体地址',
        prdt_material_quality varchar(64) not null default '' comment '材质',
        prdt_sku varchar(32) not null unique comment '产品sku',
        prdt_description text not null default '' comment '产品描述',
        prdt_stock int(11) unsigned not null default 0 comment '产品库存',
        prdt_create_time int(11) unsigned not null comment '创建时间',
        prdt_update_time int(11) unsigned not null comment '更新时间',
        key (prdt_brand),
        key (prdt_classify),
        key (prdt_country),
        key (prdt_province),
        key (prdt_city),
        key (prdt_zone),
        key (prdt_stock),
        key (prdt_create_time),
        key (prdt_update_time)
    )engine=Innodb charset=utf8mb4 comment '产品表';

    -- 产品售价表


    -- 产品分类表
    create table if not exists cf_productclassify(
        pdtcf_id int(11) unsigned not null auto_increment primary key comment '主键id',
        pdtcf_name varchar(32) not null comment '产品分类名称',
        pdtcf_img varchar(128) not null comment '产品分类图标',
        pdtcf_description varchar(128) not null default '' comment '描述或注释',
        pdtcf_pid int(11) unsigned not null default 0 comment '上级id',
        pdtcf_path varchar(64) not null default '' comment '全路径',
        pdtcf_level tinyint(3) unsigned not null default 0 comment '分类水平',
        key (pdtcf_pid)
    )engine=Innodb charset=utf8mb4 comment '产品分类表';
    

    -- 商品表
    create table if not exists cf_goods(
        gds_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        gds_prdt_sku int(11) unsigned not null comment '产品sku',
        gds_name varchar(32) not null comment '商品名称',
        gds_cover varchar(128) not null default '' comment '商品缩略图',
        gds_price decimal(10,2) unsigned not null default 0 comment '商品价格',
        gds_color varchar(16) not null default '' comment '商品颜色(#?????)',
        gds_stock int(11) unsigned not null default 0 comment '商品库存',
        gds_country smallint(5) not null default 0 comment '商品所在产国',
        gds_province smallint(5) not null default 0 comment '商品所在省份',
        gds_city smallint(5) not null default 0 comment '商品所在城市',
        gds_zone smallint(5) not null default 0 comment '商品所在区域',
        gds_address varchar(128) not null comment '商品所在具体地址',
        gds_description text not null default '' comment '商品描述',
        gds_is_grounding tinyint(1) unsigned not null default 1 comment '1-上架/2-下架',
        gds_avaliable_refunds tinyint(1) unsigned not null default 1 comment '1-可退货/2-不可退货',
        gds_refunds_limit_days smallint(5) unsigned not null default 0 comment '可退货期限(天数)',
        gds_exchange_limit_days smallint(5) unsigned not null default 0 comment '可换货期限(天数)',
        gds_create_time int(11) unsigned not null comment '创建时间',
        gds_update_time int(11) unsigned not null comment '更新时间',
        key (gds_prdt_sku),
        key (gds_price),
        key (gds_color),
        key (gds_stock),
        key (gds_country),
        key (gds_province),
        key (gds_city),
        key (gds_zone),
        key (gds_create_time),
        key (gds_update_time)
    )engine=Innodb charset=utf8mb4 comment '商品表';


-- 订单表
    create table if not exists cf_orderlist(
        ordlt_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ordlt_number varchar(32) not null unique comment '订单编号',
        ordlt_balance_accounts decimal(10,2) unsigned not null comment '订单金额',
        ordlt_status tinyint(3) unsigned not null default 1 comment '1-未支付/2-买家取消/3-卖家取消/4-未发货/5-已发货/6-已确认收货/7-申请退货/8-申请退款/9-申请售后',
        ordlt_create_time int(11) unsigned not null comment '创建时间',
        ordlt_update_time int(11) unsigned not null comment '更新时间',
        ordlt_is_del tinyint(1) unsigned not null default 1 comment '1-正常/2-已删除',
        key (ordlt_status),
        key (ordlt_create_time),
        key (ordlt_update_time)
    )engine=Innodb charset=utf8mb4 comment '订单表';

-- 订单详情表
    create table if not exists cf_orderdetail(
        orddetl_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        orddetl_orderno varchar(32) not null comment '所属订单',
        orddetl_gds_id bigint(19) unsigned not null comment '商品id',
        orddetl_name varchar(64) not null comment '订单名称(一般为商品名称)',
        orddetl_cover varchar(128) not null default '' comment '订单封面(一般为商品封面)',
        orddetl_buy_counts int(11) unsigned not null comment '购买数量',
        orddetl_unit_price decimal(10,2) unsigned not null comment '单价',
        orddetl_balance_accounts decimal(10,2) unsigned not null comment '金额',
        key (orddetl_orderno),
        key (orddetl_gds_id)
    )engine=Innodb charset=utf8mb4 comment '订单详情表';

-- ②：图片管理(单独管理，方便后期图床技术处理)
    -- 文章图片管理表
    create table if not exists cf_article_img (
        artimg_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        artimg_url varchar(128) not null comment '图片路径'
    )engine=Innodb charset=utf8mb4 comment '文章图片管理表';
-- ③：财务管理(交易记录)
    -- 用户账户表
    create table if not exists cf_account(
        ac_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ac_uid bigint(19) unsigned not null comment '用户id',
        ac_utype tinyint(3) unsigned not null comment '账户所属用户类型(例如1-普通用户/2-管理员等)',
        ac_number varchar(32) not null unique comment '账号',
        ac_status tinyint(3) unsigned not null default 1 comment '账户状态(1-正常/2-冻结)',
        key (ac_uid),
        key (ac_number)
    )engine=Innodb charset=utf8 comment '用户账户表';

    --账户变动日志表
    create table if not exists cf_aclog(
        aclg_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        aclg_ac_id bigint(19) unsigned not null comment '账户id',
        aclg_jftp tinyint(3) unsigned not null comment '积分类型',
        aclg_jfnum decimal(10,2) unsigned not null default 0.00 comment '变动积分数目',
        aclg_status varchar(16) not null default '' comment '状态变更记录json字串,{"before":1,"after":2}',
        aclg_type enum('0','1') not null comment '变动类型(0-积分变动/1-状态变动)',
        aclg_time int(10) unsigned not null comment '变动时间',
        aclg_description varchar(512) not null default '' comment '变动原因(奖励/惩罚/购物/转账等)',
        key (aclg_ac_id),
        key (aclg_time)
    )engine=Innodb charset=utf8 comment '账户变动日志表';

    -- 用户表与积分表中间关联表
    create table if not exists cf_aclinkjf(
        aclkjf_id bigint(19) unsigned not null auto_increment primary key comment '余额记录id',
        aclkjf_uid bigint(19) unsigned not null comment '用户id',
        aclkjf_jftype tinyint(3) unsigned not null comment '积分类型id',
        aclkjf_jfno decimal(10,2) not null default 0.00 comment '积分数目',
        key (aclkjf_uid),
        key (aclkjf_jftype)
    )engine=Innodb charset=utf8;

    -- 积分类型表
    create table if not exists cf_jifentype(
        jf_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        jf_name varchar(32) not null unique comment '积分类型名称',
        jf_img varchar(128) not null default '' comment '积分图标',
        jf_description varchar(512) not null default '' comment '积分类描述',
        jf_is_use enum('0','1') not null default '1' comment '是否启用此积分'
    )engine=Innodb charset=utf8 comment '积分类型表';
-- ④：支付管理(优先集成微信和支付宝独立接口插件)
-- ⑤：普通用户管理(企业/商家/个人)(独立分表)(账户)
    -- 用户表
    create table if not exists cf_users(
        urs_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        urs_name varchar(32) not null unique comment '用户名',
        urs_pwd varchar(64) not null default '' comment '密码',
        urs_headerimg varchar(128) not null default '' comment '头像',
        urs_type tinyint(3) unsigned not null default 1 comment '用户类型(1-普通/2-商户/3-企业)',
        urs_mbtype tinyint(3) unsigned not null default 1 comment '会员类型',
        urs_mblv smallint(5) unsigned not null default 0 comment '会员等级',
        urs_nike_name varchar(32) not null default '' comment '昵称',
        urs_true_name varchar(32) not null default '' comment '姓名',
        urs_age tinyint(3) unsigned not null default 18 comment '年龄',
        urs_sex enum('1','2','3') not null default '1' comment '性别(1-保密/2-男/3-女)',
        urs_zj_no varchar(64) not null default '' comment '证件号',
        urs_zj_img varchar(256) not null default '' comment '证件照',
        urs_educational tinyint(3) unsigned not null default 0 comment '学历',
        urs_major smallint(5) unsigned not null default 0 comment '专业',
        urs_sign varchar(128) not null default '' comment '用户签名',
        urs_hobby varchar(128) not null default '' comment '用户爱好',
        urs_country tinyint(3) unsigned not null default 1 comment '国家',
        urs_province int(11) unsigned not null default 0 comment '省',
        urs_city int(11) unsigned not null default 0 comment '市',
        urs_zone int(11) unsigned not null default 0 comment '区',
        urs_address varchar(64) not null default '' comment '用户地址',
        urs_phone varchar(11) not null default '' comment '用户手机',
        urs_qq varchar(16) not null default '' comment '用户QQ',
        urs_wx varchar(32) not null default '' comment '用户微信',
        urs_email varchar(64) not null default '' comment '用户邮箱',
        urs_ali varchar(64) not null default '' comment '支付宝账号',
        urs_wx_openid varchar(128) not null default '' comment '微信openid',
        urs_wx_unionid varchar(128) not null default '' comment '微信unionid',
        urs_ali_openid varchar(128) not null default '' comment '阿里openid',
        urs_ali_unionid varchar(128) not null default '' comment '阿里unionid',
        urs_qq_openid varchar(128) not null default '' comment 'QQ_openid',
        urs_qq_unionid varchar(128) not null default '' comment 'QQ_unionid',
        urs_status tinyint(3) not null default 1 comment '用户状态(1-未审核/2-正常/3-异常/4-禁止发言/5-禁止浏览)',
        urs_relieve_time int(11) unsigned not null default 0 comment '用户限制解除时间',
        urs_join_time int(11) unsigned not null comment '用户注册时间',
        key (urs_age),
        key (urs_educational),
        key (urs_major),
        key (urs_country),
        key (urs_province),
        key (urs_city),
        key (urs_zone),
        key (urs_wx_openid),
        key (urs_wx_unionid),
        key (urs_ali_openid),
        key (urs_ali_unionid),
        key (urs_qq_openid),
        key (urs_qq_unionid),
        key (urs_mblv),
        key (urs_join_time),
        key (urs_zj_no)
    )engine=Innodb charset=utf8 comment '用户表';

    -- 用户登录管理表
    create table if not exists cf_urslogin(
        urlg_id int(10) unsigned not null auto_increment primary key comment '主键id',
        urlg_uid bigint(19) unsigned not null unique comment '用户id',
        urlg_token varchar(128) not null unique comment '用户登录token',
        urlg_time int(11) unsigned not null comment 'token生成时间'
    )engine=Innodb charset=utf8 comment '用户登录管理表';

    -- 用户类型表
    create table if not exists cf_urstype(
        ustp_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        ustp_name varchar(32) not null unique comment '类型名称',
        ustp_img varchar(128) not null default '' comment '类型图标',
        ustp_description varchar(128) not null default '' comment '类型描述'
    )engine=Innodb charset=utf8 comment '用户类型表';

    -- 用户身材表
    create table if not exists cf_ursfigure(
        ursfg_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ursfg_uid bigint(19) unsigned not null unique comment '用户id',
        ursfg_heiht decimal(4,2) unsigned not null default 0 comment '身高',
        ursfg_weight decimal(4,2) unsigned not null default 0 comment '体重',
        ursfg_feature tinyint(3) unsigned not null default 0 comment '脸型',
        usrfg_chest decimal(4,2) unsigned not null default 0 comment '胸围',
        usrfg_waist decimal(4,2) unsigned not null default 0 comment '腰围',
        usrfg_hip decimal(4,2) unsigned not null default 0 comment '臀围',
        usrfg_description varchar(128) not null default '' comment '身材总描述',
        key (ursfg_heiht),
        key (ursfg_weight),
        key (usrfg_chest),
        key (usrfg_waist),
        key (usrfg_hip)
    )engine=Innodb charset=utf8 comment '用户身材表';

    -- 用户会员类型表
    create table if not exists cf_membertype(
        mbtp_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        mbtp_name varchar(32) not null unique comment '会员名称',
        mbtp_img varchar(128) not null default '' comment '会员图标',
        mbtp_description varchar(128) not null default '' comment '会员描述'
    )engine=Innodb charset=utf8 comment '用户会员类型表';

    -- 用户通知管理表
    create table if not exists cf_usernoticeset(
        ustcst_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ustcst_uid bigint(19) unsigned not null comment '用户id',
        ustcst_application tinyint(3) unsigned not null comment '通知应用(1-求职招聘/其它待确定中..ing)',
        ustcst_notice_mode tinyint(3) unsigned not null comment '0-拒绝所有/1-仅允许系统通知/2-系统和邮箱/3-系统和短信/4-允许所有',
        key (ustcst_uid),
        key (ustcst_application)
    )engine=Innodb charset=utf8 comment '用户通知管理表';


-- ⑥：店铺管理(传统商城/教育培训/汽车驾校等)
-- ⑦：消息管理(系统消息/留言/用户对话)
-- ⑧：文章管理
-- ⑨：兼职/全职招聘
    -- 兼职表
    create table if not exists cf_jianzhi(
        jz_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        jz_title varchar(128) not null comment '招聘名称(标题)',
        jz_keywords varchar(128) not null default '' comment '招聘关键字',
        jz_description varchar(256) not null default '' comment '招聘描述(摘要)',
        jz_hour_salary decimal(10,2) unsigned not null default 0 comment '兼职时薪',
        jz_day_salary decimal(10,2) unsigned not null default 0 comment '兼职日薪',
        jz_start_time varchar(8) not null comment '(每天)开始时间',
        jz_end_time varchar(8) not null comment '(每天)结束时间',
        jz_start_rest varchar(8) not null comment '每天开始休息时间',
        jz_end_rest varchar(8) not null comment '每天结束休息时间',
        jz_rest_time decimal(3,1) unsigned not null default 0 comment '(每天)休息时长',
        jz_start_date int(11) unsigned not null comment '开始日期',
        jz_end_date int(11) unsigned not null comment '结束日期',
        jz_end_employ int(11) unsigned not null comment '截止招聘日期', 
        jz_day_night tinyint(3) unsigned not null default 1 comment '白夜班(1-白班,2-晚班,3-白夜结合)',
        jz_pay_mode tinyint(3) unsigned not null comment '结算方式(1-现结,2-支付平台转账,3-银行卡转账,4-其它方式)',
        jz_pay_cycle tinyint(3) unsigned not null comment '结算周期(1-日结,2-做完结,3-周结,4-月结等)',
        jz_require tinyint(3) unsigned not null default 2 comment '工作要求(1-严格/2-一般/3-酱油),',
        jz_industry int(11) unsigned not null comment '所属行业',
        jz_province int(11) unsigned not null comment '所属省',
        jz_city int(11) unsigned not null comment '所属城市',
        jz_zone int(11) unsigned not null default 0 comment '所属地区',
        jz_address varchar(128) not null comment '工作地址',
        jz_host_cellphone varchar(11) not null default '' comment '招聘者手机',
        jz_host_telphone varchar(16) not null default '' comment '招聘者座机',
        jz_x decimal(16,12) not null default 0 comment '所在精度',
        jz_y decimal(16,12) not null default 0 comment '所在纬度',
        jz_employ_number smallint(5) unsigned not null comment '计划招聘人数',
        jz_emploied_number smallint(5) unsigned not null default 0 comment '已经招聘人数',
        jz_views int(11) unsigned not null default 0 comment '浏览量',
        jz_claim_height decimal(4,1) unsigned not null default 0 comment '要求身高',
        jz_claim_weight decimal(4,1) unsigned not null default 0 comment '要求体重',
        jz_claim_educational tinyint(3) unsigned not null default 0 comment '要求学历',
        jz_claim_minage tinyint(3) unsigned not null default 0 comment '最小年龄要求',
        jz_claim_maxage tinyint(3) unsigned not null default 0 comment '最大年龄要求',
        jz_claim_continuity tinyint(3) unsigned not null default 1 comment '是否要求连续做(1-要求/2-不要求)',
        jz_claim_sex tinyint(3) unsigned not null default 3 comment '性别要求(1-男/2-女/3-男女不限,默认3)',
        jz_claim_language tinyint(3) unsigned not null default 0 comment '要求语言技能',
        jz_cliam_exprience tinyint(3) unsigned not null default 0 comment '要求工作年限',
        jz_cliam_major smallint(5) unsigned not null default 0 comment '要求专业',
        jz_board tinyint(3) unsigned not null default 1 comment '是否包餐(1-不包/2-包)',
        jz_quarter tinyint(3) unsigned not null default 1 comment '是否提供住宿(1-不提供/2-提供)',
        jz_bonus varchar(128) not null default '' comment '提成情况',
        jz_provide_worker tinyint(3) unsigned not null default 1 comment '是否支持代招(1-不支持/2-支持)',
        jz_provide_bonus decimal(5,2) not null default 0 comment '代招提成?元/人',
        jz_contents varchar(2048) not null comment '招聘内容',
        jz_auther bigint(19) unsigned not null comment '招聘发布者',
        jz_publish_time int(11) unsigned not null comment '发布日期',
        jz_refresh_time int(11) unsigned not null comment '刷新时间',
        jz_weekend tinyint(3) unsigned not null default 1 comment '是否为周末(1-不是/2-是)',
        jz_expire tinyint(3) unsigned not null default 1 comment '是否已经过期(1-未过期/2-过期)',
        jz_check tinyint(3) unsigned not null default 1 comment '是否已经审核(1-未审核/2-已审核)',
        jz_is_hot tinyint(3) unsigned not null default 1 comment '是否热门推荐(1-否/2-是)',
        jz_is_delete tinyint(3) unsigned not null default 1 comment '是否已经逻辑删除(1-否/2-是)',
        key (jz_hour_salary),
        key (jz_day_salary),
        key (jz_start_date),
        key (jz_end_date),
        key (jz_industry),
        key (jz_zone),
        key (jz_x),
        key (jz_y),
        key (jz_claim_educational),
        key (jz_auther),
        key (jz_claim_minage),
        key (jz_claim_maxage),
        key (jz_claim_language),
        key (jz_refresh_time)
    )engine=Innodb charset=utf8 comment '兼职表';

    -- 兼职查看加密表
    create table if not exists cf_urjzmd(
        ujmd_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ujmd_uid bigint(19) unsigned not null comment '用户id',
        ujmd_action_id bigint(19) unsigned not null comment '指定的id',
        ujmd_out_time int(11) unsigned not null default 0 comment '过期时间',
        ujmd_token varchar(128) not null comment '加密后的token',
        key (ujmd_uid),
        key (ujmd_out_time),
        key (ujmd_token)
    )engine=Innodb charset=utf8 comment '兼职查看加密表';

    -- 兼职收藏表
    create table if not exists cf_jzcollect(
        jzclt_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        jzclt_uid bigint(19) unsigned not null comment '用户id',
        jzclt_jzid bigint(19) unsigned not null comment '兼职id',
        jzclt_time int(11) unsigned not null comment '收藏时间',
        key (jzclt_uid),
        key (jzclt_jzid),
        key (jzclt_time)
    )engine=Innodb charset=utf8 comment '兼职收藏表';

    -- 支付方式表
    create table if not exists cf_paytype(
        ptp_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        ptp_name varchar(32) not null comment '结算方式名称',
        ptp_img varchar(128) not null default '' comment '结算方式图标',
        ptp_description varchar(128) not null default '' comment '描述或注释'
    )engine=Innodb charset=utf8 comment '支付方式表';

    -- 结算周期表
    create table if not exists cf_paycycle(
        pcc_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        pcc_name varchar(32) not null comment '结算周期名称',
        pcc_img varchar(128) not null default '' comment '结算周期图标',
        pcc_description varchar(128) not null default '' comment '描述或注释'
    )engine=Innodb charset=utf8 comment '结算周期表';

    -- 全职表
    create table if not exists cf_shixiorquanzhi(
        sxrqz_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        sxrqz_title varchar(128) not null comment '招聘名称(标题)',
        sxrqz_keywords varchar(128) not null default '' comment '招聘关键字',
        sxrqz_description varchar(256) not null default '' comment '招聘描述(摘要)',
        sxrqz_start_time varchar(8) not null comment '(每天)开始时间',
        sxrqz_end_time varchar(8) not null comment '(每天)结束时间',
        sxrqz_start_rest varchar(8) not null comment '每天开始休息时间',
        sxrqz_end_rest varchar(8) not null comment '每天结束休息时间',
        sxrqz_rest_time decimal(3,1) unsigned not null default 0 comment '(每天)休息时长',
        sxrqz_end_employ int(11) unsigned not null comment '截止招聘日期', 
        sxrqz_day_night tinyint(3) unsigned not null default 1 comment '白夜班(1-白班,2-晚班,3-白夜结合)',
        sxrqz_month_salary decimal(7,1) unsigned not null comment '月薪',
        sxrqz_year_salary decimal(10,1) unsigned not null default comment '年薪',
        sxrqz_pay_mode tinyint(3) unsigned not null comment '结算方式(1-现结,2-支付平台转账,3-银行卡转账,4-其它方式)',
        sxrqz_pay_day tinyint(3) unsigned not null comment '发薪日(1-31)',
        sxrqz_industry int(11) unsigned not null comment '所属行业',
        sxrqz_province int(11) unsigned not null comment '所属省',
        sxrqz_city int(11) unsigned not null comment '所属城市',
        sxrqz_zone int(11) unsigned not null default 0 comment '所属地区',
        sxrqz_address varchar(128) not null comment '工作地址',
        sxrqz_host_cellphone varchar(11) not null default '' comment '招聘者手机',
        sxrqz_host_telphone varchar(16) not null default '' comment '招聘者座机',
        sxrqz_x decimal(16,12) not null default 0 comment '所在精度',
        sxrqz_y decimal(16,12) not null default 0 comment '所在纬度',
        sxrqz_employ_number smallint(5) unsigned not null comment '计划招聘人数',
        sxrqz_emploied_number smallint(5) unsigned not null default 0 comment '已经招聘人数',
        sxrqz_views int(11) unsigned not null default 0 comment '浏览量',
        sxrqz_claim_height decimal(4,1) unsigned not null default 0 comment '要求身高',
        sxrqz_claim_weight decimal(4,1) unsigned not null default 0 comment '要求体重',
        sxrqz_claim_educational tinyint(3) unsigned not null default 0 comment '要求学历',
        sxrqz_claim_minage tinyint(3) unsigned not null default 0 comment '最小年龄要求',
        sxrqz_claim_maxage tinyint(3) unsigned not null default 0 comment '最大年龄要求',
        sxrqz_claim_sex tinyint(3) unsigned not null default 3 comment '性别要求(1-男/2-女/3-男女不限,默认3)',
        sxrqz_claim_language tinyint(3) unsigned not null default 0 comment '要求语言技能',
        sxrqz_cliam_exprience tinyint(3) unsigned not null default 0 comment '要求工作年限',
        sxrqz_cliam_major smallint(5) unsigned not null default 0 comment '要求专业',
        sxrqz_board tinyint(3) unsigned not null default 1 comment '是否包餐(1-不包/2-包)',
        sxrqz_quarter tinyint(3) unsigned not null default 1 comment '是否提供住宿(1-不提供/2-提供)',
        sxrqz_insurance tinyint(3) unsigned not null default 1 comment '是否提供保险(1-不提供/2-提供)',
        sxrqz_fund tinyint(3) unsigned not null default 1 comment '是否提供公积金(1-不提供/2-提供)',
        sxrqz_weekend tinyint(3) unsigned not null comment '单双休(1-单休/2-双休/3-其它时间休)',
        sxrqz_welfare_supplement varchar(128) not null default '' comment '其它福利待遇补充或说明',
        sxrqz_provide_worker tinyint(3) unsigned not null default 1 comment '是否支持代招(1-不支持/2-支持)',
        sxrqz_provide_bonus decimal(5,2) not null default 0 comment '代招提成?元/人',
        sxrqz_contents varchar(2048) not null comment '招聘内容',
        sxrqz_auther bigint(19) unsigned not null comment '招聘发布者',
        sxrqz_publish_time int(11) unsigned not null comment '发布日期',
        sxrqz_refresh_time int(11) unsigned not null comment '刷新时间',
        sxrqz_expire tinyint(3) unsigned not null default 1 comment '是否已经过期(1-未过期/2-过期)',
        sxrqz_check tinyint(3) unsigned not null default 1 comment '是否已经审核(1-未审核/2-已审核)',
        sxrqz_is_hot tinyint(3) unsigned not null default 1 comment '是否热门推荐(1-否/2-是)',
        sxrqz_is_delete tinyint(3) unsigned not null default 1 comment '是否已经逻辑删除(1-否/2-是)',
        key (sxrqz_month_salary),
        key (sxrqz_year_salary),
        key (sxrqz_pay_mode),
        key (sxrqz_pay_day),
        key (sxrqz_industry),
        key (sxrqz_province),
        key (sxrqz_city),
        key (sxrqz_zone),
        key (sxrqz_x),
        key (sxrqz_y),
        key (sxrqz_claim_educational),
        key (sxrqz_claim_minage),
        key (sxrqz_claim_maxage),
        key (sxrqz_claim_language),
        key (sxrqz_cliam_exprience),
        key (sxrqz_cliam_major),
        key (sxrqz_auther),
        key (sxrqz_publish_time),
        key (sxrqz_refresh_time)
    )engine=Innodb charset=utf8 comment '全职表';

    -- 实习或全职查看加密表
    create table if not exists cf_sxrqzmd(
        sqmd_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        sqmd_uid bigint(19) unsigned not null comment '用户id',
        sqmd_action_id bigint(19) unsigned not null comment '指定的id',
        sqmd_out_time int(11) unsigned not null default 0 comment '过期时间',
        sqmd_token varchar(128) not null comment '加密后的token',
        key (sqmd_uid),
        key (sqmd_out_time),
        key (sqmd_token)
    )engine=Innodb charset=utf8 comment '兼职查看加密表';


    -- 招聘信息审核进度通知表

    -- 求职招聘记录表
    create table if not exists cf_qzzprecord(
        qzzprd_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        qzzprd_work_id bigint(19) unsigned not null comment '申请招聘信息id',
        qzzprd_work_title varchar(32) not null comment '招聘信息标题',
        qzzprd_work_type tinyint(3) unsigned not null comment '求职类型(1-兼职/2-实习或全职)',
        qzzprd_jli_id bigint(19) unsigned not null comment '简历id',
        qzzprd_jli_key varchar(64) not null comment '简历id(加密)',
        qzzprd_needer_id bigint(19) unsigned not null comment '求职者id',
        qzzprd_provider_id bigint(19) unsigned not null comment '招聘者id',
        qzzprd_who_active tinyint(3) unsigned not null comment '主被动(1-求职方主动投递/2-招聘方主动邀请)',
        qzzprd_status tinyint(3) unsigned not null comment '求职招聘进度(备注见sql文件qzzprd_status状态说明)',
        qzzprd_needer_view tinyint(3) unsigned not null default 1 comment '求职方是否已阅读本条最新消息(1-未阅读/2-已阅读)',
        qzzprd_provider_view tinyint(3) unsigned not null default 1 comment '求职方是否已阅读本条最新消息(1-未阅读/2-已阅读)',
        qzzprd_status_recored varchar(512) not null comment '求职招聘进度记录{"状态码":"时间"}',
        qzzprd_needer_hidden tinyint(3) unsigned not null default 0 comment '求职方是否逻辑删除本条记录(0-正常显示/1-隐藏)',
        qzzprd_provider_hidden tinyint(3) unsigned not null default 0 comment '招聘方是否逻辑删除本条记录(0-正常显示/1-隐藏)',
        qzzprd_is_expire tinyint(3) unsigned not null default 0 comment '是否已失效(0-正常/1-失效)',
        qzzprd_status_change_time int(11) unsigned not null comment '状态更新时间',
        qzzprd_creat_time int(11) unsigned not null comment '创建时间',
        key (qzzprd_work_id),
        key (qzzprd_needer_id),
        key (qzzprd_provider_id),
        key (qzzprd_status),
        key (qzzprd_status_change_time),
        key (qzzprd_creat_time)
    )engine=Innodb charset=utf8 comment '求职招聘记录表';
    -- qzzprd_status状态说明
    -- (1-等待招聘方查看/2-招聘方已查看/3-简历未筛选通过/4-通知面试/5-待求职方反馈面试邀请/6-求职方同意面试/7-求职方拒绝面试/8-双方同意面试后求职方取消面试/
    --  9-双方同意面试后招聘方取消面试/10-面试成功待招聘方反馈结果/11-招聘方拒绝录用/12-招聘方录用待求职方反馈/13-求职方拒绝被录用/14-双方达成劳务合作)

    

    -- 雇佣记录表
    create table if not exists cf_employrecord(
        emprd_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        emprd_needer_id bigint(19) unsigned not null comment '求职者id',
        emprd_provider_id bigint(19) unsigned not null comment '招聘者id',
        emprd_needer_agree tinyint(3) unsigned not null default 0 comment '求职者是否认可(0-不认可/1-认可)',
        emprd_provider_agree tinyint(3) unsigned not null default 0 comment '招聘者是否认可(0-不认可/1-认可)',
        emprd_agreement text not null default '' comment '劳动协议(合同)',
        emprd_agreement_type tinyint(3) unsigned not null comment '协议类型(1-兼职/2-实习或全职)',
        emprd_is_controversy tinyint(3) unsigned not null default 0 comment '是否存在争议(0-正常/1-存在争议)',
        emprd_suer tinyint(3) unsigned not null default 0 comment '起诉方(0-不存争议/1-求职方/2-招聘方)',
        emprd_sue_contents varchar(128) not null default '' comment '起诉简要原由',
        emprd_suer_evidence text not null default '' comment '起诉方证据',
        emprd_defendant_evidence text not null default '' comment '被告方证据',
        emprd_victoryer tinyint(3) unsigned not null default 0 comment '0-不存在争议/1-求职方胜诉/2-招聘方胜诉',
        emprd_judgement_contents text not null default '' comment '判决内容',
        emprd_needer_agree_time int(11) unsigned not null default 0 comment '求职方签名时间',
        emprd_provider_agree_time int(11) unsigned not null default 0 comment '招聘方签名时间',
        emprd_create_time int(11) unsigned not null comment '记录创建时间',
        key (emprd_needer_id),
        key (emprd_provider_id),
        key (emprd_create_time)
    )engine=Innodb charset=utf8 comment '雇佣记录表';
    

    -- 简历表
    create table if not exists cf_jianli(
        jli_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        jli_name varchar(32) not null comment '简历名称',
        jli_uid bigint(19) unsigned not null comment '简历所属者',
        jli_header_img varchar(128) not null default '' comment '简历头像',
        jli_true_name  varchar(32) not null comment '简历姓名',
        jli_sex tinyint(3) unsigned not null comment '性别要求(1-男/2-女)',
        jli_birth int(11) unsigned not null comment '出生年月日',
        jli_height decimal(5,1) unsigned not null default 0 comment '身高',
        jli_weight decimal(5,1) unsigned not null default 0 comment '体重(单位/千克)',
        jli_educational tinyint(3) unsigned not null comment '学历',
        jli_major smallint(5) unsigned not null default 0 comment '专业',
        jli_school int(11) unsigned not null default 0 comment '毕业或就读院校',
        jli_language tinyint(3) unsigned not null default 0 comment '语言',
        jli_jiguan smallint(5) unsigned not null comment '籍贯',
        jli_home_addr varchar(128) not null comment '家庭地址',
        jli_now_addr varchar(128) not null comment '现居住地址',
        jli_phone varchar(11) not null comment '手机号',
        jli_email varchar(32) not null comment '邮箱',
        jli_self_description varchar(512) not null default '' comment '自我描述',
        jli_work_type tinyint(3) not null comment '求职类型(1-兼职/2-实习或全职)',
        jli_work_experience decimal(4,1) unsigned not null default 0 comment '工作经验',
        jli_expect_province smallint(5) unsigned not null default 0 comment '期望工作省份',
        jli_expect_city int(11) unsigned not null default 0 comment '期望工作城市',
        jli_expect_zone int(11) unsigned not null default 0 comment '期望工作区域',
        jli_expect_idustry varchar(32) not null default '' comment '期望行业(id集合)',
        jli_expect_hour_salary decimal(7,2) unsigned not null default 0 comment '期望时薪',
        jli_expect_day_salary decimal(7,2) unsigned not null default 0 comment '期望日薪',
        jli_expect_month_salary decimal(10,2) unsigned not null default 0 comment '期望月薪',
        jli_expect_position smallint(5) unsigned not null default 0 comment '意向职位',
        jli_work_stauts tinyint(3) not null comment '目前工作状态(0-离职/1-在职)',
        jli_onwork_time int(11) unsigned not null default 0 comment '到岗时间，0为随时到岗',
        jli_start_work int(11) unsigned not null default 0 comment '可工作开始日期(兼职专用)',
        jli_end_work int(11) unsigned not null default 0 comment '可工作结束日期(兼职专用)',
        jli_free_time varchar(128) not null default '' comment '一周空余时间({"周几":{"0":"开始时间","1":"结束时间"}})',
        jli_create_time int(11) unsigned not null comment '简历创建时间',
        jli_refresh_time int(11) unsigned not null comment '简历刷新时间',
        jli_protect tinyint(3) unsigned not null default 2 comment '简历保护(0-隐藏/1-仅企业/2-公开)',
        jli_views int(11) unsigned not null default 0 comment '浏览量',
        jli_invite_counts smallint(5) unsigned not null default 0 comment '被邀请次数',
        jli_notice_mode tinyint(3) unsigned not null comment '通知方式(关联通知设置表数据)',
        jli_quality tinyint(3) unsigned not null default 0 comment '简历质量(0-普通/1-优秀简历/2-高质量简历)',
        jli_is_default tinyint(3) not null default 0 comment '是否为默认简历(0-否/1-是,默认否)',
        key (jli_uid),
        key (jli_birth),
        key (jli_height),
        key (jli_educational),
        key (jli_major),
        key (jli_school),
        key (jli_language),
        key (jli_work_experience),
        key (jli_expect_city),
        key (jli_expect_zone),
        key (jli_expect_hour_salary),
        key (jli_expect_day_salary),
        key (jli_expect_month_salary),
        key (jli_expect_position),
        key (jli_start_work),
        key (jli_create_time),
        key (jli_refresh_time),
        key (jli_views),
        key (jli_invite_counts)
    )engine=Innodb charset=utf8 comment '简历表';


    -- 简历付费规则表
    create table if not exists cf_jlirule(
        jlirl_id int(11) unsigned not null auto_increment primary key comment '主键id',
        jlirl_user_type int(3) unsigned not null comment '用户类型',
        jlirl_member_type tinyint(3) unsigned not null comment '会员类型',
        jlirl_type tinyint(3) unsigned not null comment '简历类型',
        jlirl_max_counts smallint(5) unsigned not null comment '简历额度(最高免费查看多少份简历，0为不限制)',
        jlirl_day_counts smallint(5) unsigned not null comment '每天免费查看多少份简历,0为不限制',
        jlirl_price decimal(5,1) unsigned not null comment '每份简历单价',
        key (jlirl_user_type),
        key (jlirl_member_type)
    )engine=Innodb charset=utf8 comment '简历规则表';

    -- 简历等级规则表
    

    -- 简历查看加密表
    create table if not exists cf_urjlimd(
        ujlimd_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ujlimd_uid bigint(19) unsigned not null comment '用户id',
        ujlimd_action_id bigint(19) unsigned not null comment '指定的id',
        ujlimd_out_time int(11) unsigned not null comment '过期时间',
        ujlimd_token varchar(128) not null comment '加密后的token',
        key (ujlimd_uid),
        key (ujlimd_out_time),
        key (ujlimd_token)
    )engine=Innodb charset=utf8 comment '简历查看加密表';


    -- 职位表
    create table if not exists cf_workposition(
        wkpst_id int(11) unsigned not null auto_increment primary key comment '主键id',
        wkpst_name varchar(32) not null comment '职位名称',
        wkpst_img varchar(128) not null comment '职位图标',
        wkpst_work_type tinyint(3) unsigned not null comment '职位类型(1-兼职/2-全职)',
        wkpst_industry smallint(5) unsigned not null comment '所属行业',
        wkpst_description varchar(64) not null comment '职位描述',
        key (wkpst_industry)
    )engine=Innodb charset=utf8 comment '职位表';

    -- 用户期望行业表(只记录默认简历数据)
    create table if not exists cf_userexpectindustry(
        urexpidu_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        urexpidu_jli_id bigint(19) unsigned not null comment '对应简历id',
        urexpidu_industry_id int(11) unsigned not null comment '行业id',
        urexpidu_work_type tinyint(3) unsigned not null comment '工作类型(1-兼职/2-全职)',
        urexpidu_is_use tinyint(3) unsigned not null comment '是否加入搜索队列(0-否/1-是)',
        key (urexpidu_jli_id),
        key (urexpidu_industry_id)
    )engine=Innodb charset=utf8 comment '用户期望行业表';

    -- 用户一周空闲表(兼职专用/只记录默认简历数据)
    create table if not exists cf_freetime(
        ft_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ft_free_day tinyint(3) unsigned not null comment '空余周几(1-7)',
        ft_start_hours tinyint(3) unsigned not null comment '当天空闲时间开始(0-23)',
        ft_end_hours tinyint(3) unsigned not null comment '当天空闲时间结束(1-24)',
        ft_jli_id bigint(19) unsigned not null comment '对应简历id',
        ft_is_use tinyint(3) unsigned not null comment '是否加入搜索队列(0-否/1-是)',
        key (ft_free_day),
        key (ft_start_hours),
        key (ft_end_hours),
        key (ft_jli_id)
    )engine=Innodb charset=utf8 comment '用户一周空闲表';

    -- 工作经历表
    create table if not exists cf_workexperience(
        wkexp_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        wkexp_jli_id bigint(19) unsigned not null comment '简历id',
        wkexp_start int(11) unsigned not null comment '起止时间',
        wkexp_end int(11) unsigned not null comment '起止时间',
        wkexp_unit varchar(32) not null comment '单位名称',
        wkexp_position varchar(32) not null comment '职务',
        wkexp_salary varchar(32) not null comment '待遇',
        wkexp_work_contents varchar(256) not null comment '主要工作内容',
        wkexp_leave_reason varchar(128) not null default '' comment '离职原因',
        wkexp_manager varchar(32) not null default '' comment '负责人以及联系方式',
        key (wkexp_jli_id),
        key (wkexp_start)
    )engine=Innodb charset=utf8 comment '工作经历表';

    -- 项目经历表
    create table if not exists cf_projectexperience(
        pjexp_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        pjexp_jli_id bigint(19) unsigned not null comment '简历id',
        pjexp_name varchar(32) not null comment '项目名称',
        pjexp_start int(11) unsigned not null comment '项目开始时间',
        pjexp_end int(11) unsigned not null comment '项目结束时间',
        pjexp_charge varchar(128) not null comment '项目主要负责内容',
        pjexp_description varchar(256) not null comment '项目描述',
        key (pjexp_jli_id)
    )engine=Innodb charset=utf8 comment '项目经历表';

    -- 用户教育经历表(非简历专用)(个人资料)
    create table if not exists cf_educationexperience(
        eduexp_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        eduexp_uid bigint(19) unsigned not null comment '用户id',
        eduexp_school varchar(32) not null comment '院校名称',
        eduexp_start int(11) unsigned not null comment '开始时间',
        eduexp_end int(11) unsigned not null comment '结束时间',
        eduexp_major varchar(32) not null default '' comment '专业',
        eduexp_educational varchar(32) not null default '' comment '学历',
        eduexp_degree varchar(32) not null default '' comment '学位',
        key (eduexp_uid)
    )engine=Innodb charset=utf8 comment '用户教育经历表';

    -- 学历表
    create table if not exists cf_qualifications(
        qft_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        qft_name varchar(32) not null comment '学历名称',
        qft_img varchar(128) not null default '' comment '学历图标',
        qft_level tinyint(3) unsigned not null default 0 comment '学历水平',
        qft_description varchar(128) not null default '' comment '描述或注释'
    )engine=Innodb charset=utf8 comment '学历表';

    -- 语言表
    create table if not exists cf_language(
        lgg_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        lgg_name varchar(32) not null comment '语言名称',
        lgg_img varchar(128) not null default '' comment '语言图标',
        lgg_description varchar(128) not null default '' comment '描述或注释'
    )engine=Innodb charset=utf8 comment '语言表';

    -- 行业表
    create table if not exists cf_industry(
        ids_id int(11) unsigned not null auto_increment primary key comment '主键id',
        ids_name varchar(32) not null comment '行业名称',
        ids_img varchar(128) not null comment '行业图标',
        ids_description varchar(128) not null default '' comment '描述或注释',
        ids_pid int(11) unsigned not null default 0 comment '上级id',
        ids_path varchar(64) not null default '' comment '全路径',
        ids_level tinyint(3) unsigned not null default 0 comment '分类水平',
        key (ids_pid)
    )engine=Innodb charset=utf8 comment '行业表';

    -- 专业表
    create table if not exists cf_major(
        mj_id int(11) unsigned not null auto_increment primary key comment '主键id',
        mj_name varchar(32) not null comment '专业名称',
        mj_img varchar(128) not null default '' comment '专业图标',
        mj_description varchar(128) not null default '' comment '描述或注释',
        mj_pid int(11) unsigned not null default 0 comment '上级id',
        mj_path varchar(64) not null default '' comment '全路径',
        mj_level tinyint(3) unsigned not null default 0 comment '分类水平',
        key (mj_pid)
    )engine=Innodb charset=utf8 comment '专业表';

    -- 院校表
    create table if not exists cf_school(
        sch_id int(11) unsigned not null auto_increment primary key comment '主键id',
        sch_name varchar(32) not null comment '院校名称',
        sch_img varchar(128) not null default '' comment '院校图标',
        sch_province smallint(5) unsigned not null comment '院校省份',
        sch_city int(11) unsigned not null comment '院校城市',
        sch_zone int(11) unsigned not null default 0 comment '院校地区',
        sch_addr varchar(64) not null default '' comment '院校具体地址',
        sch_classify tinyint(3) unsigned not null comment '院校分类',
        sch_x decimal(16,12) not null default 0 comment '院校经度',
        sch_y decimal(16,12) not null default 0 comment '院校纬度',
        sch_character tinyint(3) unsigned not null comment '办学性质(1-公办/2-民办)',
        sch_feature tinyint(3) unsigned not null default 0 comment '院校特色(1-211/2-985/3-研究生院)',
        sch_along tinyint(3) unsigned not null comment '院校隶属(1-教育部/2-其它中央部委/3-地方所属)',
        sch_type tinyint(3) unsigned not null comment '办学类型',
        sch_educational tinyint(3) unsigned not null comment '学历层次(1-本科/2-专科)',
        key (sch_name),
        key (sch_province),
        key (sch_city),
        key (sch_zone),
        key (sch_classify),
        key (sch_x),
        key (sch_y),
        key (sch_type)
    )engine=Innodb charset=utf8 comment '院校表';

    -- 院校分类表
    create table if not exists cf_schoolclassify(
        schcf_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        schcf_name varchar(32) not null comment '分类名称',
        schcf_img varchar(128) not null default '' comment '图标',
        schcf_description varchar(128) not null default '' comment '描述'
    )engine=Innodb charset=utf8 comment '院校分类表';

    -- 院校办学类型表
    create table if not exists cf_schooltype(
        schtp_id tinyint(3) unsigned not null auto_increment primary key comment '主键id',
        schtp_name varchar(32) not null comment '类型名称',
        schtp_img varchar(128) not null default '' comment '图标',
        schtp_description varchar(128) not null default '' comment '描述'
    )engine=Innodb charset=utf8 comment '院校办学类型表';



    -- 招聘评论表
    create table if not exists cf_zpcomment(
        zpcmt_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        zpcmt_type tiny(3) unsigned not null comment '评论类型(1-兼职/2-全职等)',
        zpcmt_contents varchar(512) not null comment '评论内容',
        zpcmt_uid bigint(19) unsigned not null comment '评论者',
        zpcmt_time int(11) unsigned not null comment '评论时间',
        zpcmt_floor smallint(5) unsigned not null comment '评论楼层',
        key (zpcmt_uid),
        key (zpcmt_time)
    )engine=Innodb charset=utf8 comment '招聘评论表';

    -- 招聘评论回复表
    create table if not exists cf_zpreply(
        zprp_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        zprp_pid bigint(19) unsigned not null default 0 comment '回复id(在回复中回复)',
        zprp_cmid bigint(19) unsigned not null comment '评论id',
        zprp_uid bigint(19) unsigned not null comment '回复者',
        zprp_contents varchar(512) not null comment '回复内容',
        zprp_time int(11) unsigned not null comment '回复时间',
        key (zprp_pid),
        key (zprp_cmid),
        key (zprp_uid),
        key (zprp_time)
    )engine=Innodb charset=utf8 comment '招聘评论回复表';

-- ⑩：信用评价体系
-- ⑪：公众号管理(菜单/定时任务群发(消息推送)/素材管理/粉丝管理等)
-- ⑪：问答/常见问题
-- ⑫：房产(租/买卖)
-- ⑬：汽车(租/买卖)
-- ⑭：金融(借贷平台)
-- ⑮：系统管理(基本配置信息,菜单设置等)

    -- 系统配置表
    create table if not exists cf_system_config(
        syscfg_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        syscfg_name varchar(32) not null comment '配置名',
        syscfg_flag varchar(32) not null unique comment '标识符',
        syscfg_type tinyint(1) unsigned not null comment '配置类型(1-固定[不可删除]/2-自由类型[可删除])',
        syscfg_value varchar(256) not null comment '配置内容',
        syscfg_description varchar(128) not null default '' comment '备注或描述',
        syscfg_indexno tinyint(3) unsigned not null default 0 comment '排序'
    )engine=Innodb charset=utf8 comment '系统配置表';


    -- 短信配置表
    create table if not exists cf_smsconfig(
        smcf_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        smcf_name varchar(32) not null comment '短信商名称',
        smcf_flag varchar(32) not null unique comment '标识符',
        smcf_user_name varchar(32) not null comment '用户名',
        smcf_password varchar(64) not null comment '密码',
        smcf_sign varchar(32) not null comment '签名',
        smcf_tpl_contents varchar(512) not null comment '短信模板内容',
        smcf_sent_counts  bigint(19) unsigned not null comment '已发送条数',
        smcf_surplus int(11) unsigned not null comment '剩余条数',
        smcf_api_addr varchar(128) not null comment '接口地址',
        smcf_valid_time int(11) unsigned not null comment '有效时间(针对验证码)/单位秒',
        smcf_limit_per smallint(5) unsigned not null default 60 comment '重复发送时间,默认60秒',
        smcf_limit_day smallint(5) unsigned not null default 0 comment '每天最多发送多少次,默认0不限制'
    )engine=Innodb charset=utf8 comment '短信配置表';

    -- 短信发送日志表
    create table if not exists cf_smssendlog(
        ssdlog_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ssdlog_cfgid varchar(32) not null comment '短信商(标识符)',
        ssdlog_phone varchar(11) not null comment '发送号码',
        ssdlog_code varchar(8) not null comment '验证码',
        ssdlog_status tinyint(3) unsigned not null comment '发送状态(1-成功/2-本地接口异常/3-发送失败/4-用户未校验)',
        ssdlog_fail_description varchar(32) not null default '' comment '失败描述',
        ssdlog_time int(11) unsigned not null comment '发送时间',
        ssdlog_out_time int(11) unsigned not null comment '过期时间',
        key (ssdlog_time)广告管理表
    )engine=Innodb charset=utf8 comment '短信发送日志表';

    -- 广告管理表
    create table if not exists cf_advertisement(
        adtt_id int unsigned not null auto_increment primary key comment '主键id',
        adtt_name varchar(32) not null comment '广告名称',
        adtt_cover varchar(128) not null default '' comment '广告封面',
        adtt_link varchar(128) not null default '' comment '广告链接',
        adtt_contents varchar(4096) not null default '' comment '广告内容',
        adtt_start_time int(11) unsigned not null default 0 comment '广告开始投放时间',
        adtt_end_time int(11) unsigned not null default 0 comment '广告结束投放时间',
        adtt_show_start tinyint(3) unsigned not null default 0 comment '当天开始显示时间(24小时制)',
        adtt_show_end tinyint(3) unsigned not null default 0 comment '当天结束显示时间(24小时制)',
        adtt_adtid smallint(5) unsigned not null comment '广告所属分类',
        adtt_province int(11) unsigned not null default 0 comment '所属省份',
        adtt_city int(11) unsigned not null default 0 comment '所属城市',
        adtt_zone int(11) unsigned not null default 0 comment '所属区/城镇',
        adtt_along_uid bigint(19) unsigned not null default 0 comment '广告主人',
        adtt_views int(10) unsigned not null default 0 comment '浏览量',
        adtt_is_use enum('1','2') not null default '1' comment '是否启用广告,默认未启用',
        adtt_time_out enum('1','2') not null default '1' comment '是否已经过期默认未过期',
        adtt_index smallint(5) unsigned not null default 0 comment '排序',
        key (adtt_adtid),
        key (adtt_province),
        key (adtt_city),
        key (adtt_zone),
        key (adtt_along_uid),
        key (adtt_index)
    )engine=Innodb charset=utf8 comment '广告管理表';

    -- 广告分类表
    create table if not exists cf_adtclassify(
        adtcf_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        adtcf_name varchar(32) not null comment '广告分类名称',
        adtcf_img varchar(128) not null default '' comment '广告分类图标',
        adtcf_description varchar(512) not null default '' comment '广告分类描述',
        adtcf_pid smallint(5) unsigned not null default 0 comment '上级id',
        adtcf_path varchar(64) not null default '' comment '路径',
        adtcf_level tinyint(3) unsigned not null default 0 comment '水平',
        key (adtcf_pid)
    )engine=Innodb charset=utf8 comment '广告分类表';

    --  url操作分类表
    create table if not exists cf_urlactiontype(
        urlatp_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        urlatp_name varchar(32) not null comment '分类名称',
        urlatp_flag varchar(32) not null comment '操作类型标识符',
        urlatp_description varchar(512) not null default '' comment '描述或注释'
    )engine=Innodb charset=utf8 comment 'url操作分类表';

-- ⑯：后台用户以及权限管理
    -- 后台管理人员表
    create table if not exists cf_admins(
        ads_id int(10) unsigned not null auto_increment primary key comment '后台管理员id',
        ads_name varchar(16) not null unique comment '管理员用户名',
        ads_passwd varchar(128) not null comment '后台管理员密码',
        ads_role tinyint(3) unsigned not null default 0 comment '后台用户角色(角色表id)',
        ads_department smallint(5) unsigned not null default 0 comment '用户所属部门',
        ads_nike_name varchar(16) not null default '' comment '后台管理员昵称',
        ads_header_img varchar(128) not null default '' comment '后台用户头像',
        ads_ture_name varchar(16) not null default '' comment '后台管理员真实姓名',
        ads_phone varchar(11) not null default '' comment '后台管理员手机',
        ads_email varchar(32) not null default '' comment '管理员邮箱',
        ads_qq varchar(16) not null default '' comment '后台管理员qq号',
        ads_wx varchar(32) not null default '' comment '后台管理员微信id',
        ads_sex tinyint(1) unsigned not null default 3 comment '性别，1-女,默认2男性,3保密',
        ads_age tinyint(3) unsigned not null default 0 comment '后台管理员年龄，默认0岁',
        ads_country smallint(5) unsigned not null default 0 comment '用户所属国家',
        ads_province bigint(19) unsigned not null default 0 comment '用户所属省份',
        ads_city bigint(19) unsigned not null default 0 comment '用户所属城市',
        ads_area bigint(19) unsigned not null default 0 comment '用户所属区',
        ads_county bigint(19) unsigned not null default 0 comment '用户所县',
        ads_town bigint(19) unsigned not null default 0 comment '用户所属镇',
        ads_village bigint(19) unsigned not null default 0 comment '用户所属村',
        ads_addr varchar(64) not null default '' comment '后台用户具体地址',
        ads_add_time int(10) unsigned not null comment '用户添加时间',
        ads_zjno varchar(32) not null default '' comment '后台用户证件号(例如身份证号)',
        ads_zjimg varchar(256) not null default '' comment '后台管理人员证件照片',
        ads_description varchar(1024) not null default '' comment '用户简介',
        is_check tinyint(1) unsigned not null default 1 comment '是否通过审核(1-未审核/2-已审核)',
        key (ads_name),
        key (ads_role),
        key (ads_department),
        key (ads_nike_name),
        key (ads_ture_name),
        key (ads_phone),
        key (ads_qq),
        key (ads_wx),
        key (ads_zjno),
        key (ads_add_time)
    )engine=Innodb charset=utf8mb4 comment='后台管理人员表';


    -- 后台管理员登录日志表
    create table if not exists cf_admloginlog(
        alg_id bigint(19) unsigned not null auto_increment primary key comment '主键id',
        ad_id int(10) unsigned not null comment '对应的管理员id',
        login_ip varchar(16) not null default '' comment '上次登录ip',
        login_time int(10) unsigned not null comment '上次登录时间',
        login_type tinyint(3) unsigned not null default 1 comment '1-为PC登录,2-手机,3-接口',
        login_status tinyint(3) unsigned not null default 1 comment '1-正常,2-异地,3-危险',
        key (ad_id),
        key (login_ip),
        key (login_time)
    )engine=Innodb charset=utf8 comment='后台管理员登录日志表';



    -- 部门表
    create table if not exists cf_department (
    	dptmt_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        dptmt_name varchar(32) not null unique comment '部门名称',
        dptmt_code varchar(32) not null unique comment '部门编号',
    	dptmt_indexno smallint(5) unsigned not null default 0 comment '部门排序',
    	dptmt_description varchar(512) not null default '' comment '部门描述'
	)engine=Innodb charset=utf8mb4 comment='部门表';


    -- 角色表
    CREATE TABLE IF NOT EXISTS cf_role (
        rol_id smallint(5) unsigned NOT NULL auto_increment,
        rol_name varchar(20) NOT NULL COMMENT '角色名称',
        rol_department smallint(5) not null default 0 comment '所属部门',
        rol_level smallint(5) not null default 0 comment '角色水平，数值越高，代表权限越高',
        rol_indexno smallint(5) unsigned not null default 0 comment '部门排序',
        rol_description varchar(512) not null default '' comment '角色描述',
        PRIMARY KEY (rol_id)
    ) ENGINE=Innodb CHARSET=utf8mb4 comment '用户角色表';


    --角色权限中间关联表
    create table if not exists cf_rlinka(
        rka_id int(11) unsigned not null auto_increment primary key comment '主键id',
        rka_rid smallint(5) unsigned unique not null comment '角色id',
        rka_aids varchar(256) not null default '' comment '权限ids'
    )engine=Innodb charset=utf8mb4 comment '角色权限中间关联表';


    -- 用户权限表
    create table if not exists cf_user_auth(
        urath_id int(11) unsigned not null auto_increment primary key comment '主键id',
        urath_uid int(11) unsigned not null comment '用户id',
        urath_aids varchar(256) not null default '' comment '权限ids',
        key (urath_uid)
    )engine=Innodb charset=utf8mb4 comment '用户权限表';


    -- 权限表
    CREATE TABLE IF NOT EXISTS cf_auth (
        auth_id smallint(6) unsigned NOT NULL auto_increment,
        auth_name varchar(20) NOT NULL COMMENT '名称',
        auth_pid smallint(6) unsigned NOT NULL COMMENT '父id',
        auth_controller varchar(32) NOT NULL default '' COMMENT '控制器',
        auth_action varchar(32) NOT NULL default '' COMMENT '操作方法',
        auth_model varchar(32) not null default '' comment '模块',
        auth_path varchar(32) NOT NULL default '' COMMENT '全路径',
        auth_level tinyint(3) NOT NULL default 0 COMMENT '级别',
        auth_indexno tinyint(3) NOT NULL default 0 COMMENT '序号',
        PRIMARY KEY  (auth_id),
        key (auth_pid),
        key (auth_indexno)
    ) ENGINE=Innodb CHARSET=utf8mb4 comment '角色权限表';


    -- 左侧菜单表
    create table if not exists cf_left_menu (
        ltmu_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        ltmu_name varchar(20) not null comment '菜单名称',
        ltmu_icon varchar(128) not null default '' comment '菜单图标',
        ltmu_url varchar(128) not null default '' comment '菜单url',
        ltmu_pid smallint(5) unsigned not null default 0 comment '父级菜单id',
        ltmu_path varchar(32) not null default '' comment '菜单路径',
        ltmu_level tinyint(3) unsigned not null default 0 comment '菜单层级',
        ltmu_indexno tinyint(3) unsigned not null default 0 comment '序号',
        ltmu_is_use tinyint(1) unsigned not null default 1 comment '使用启用(1-未启用,2-启用)',
        key (ltmu_pid)
    )engine=Innodb charset=utf8mb4 comment '左侧菜单表';

    
-- ⑰：分销机制

-- ⑱：通用模块

-- 文章模块
    -- 文章分类
    create table if not exists cf_article_classify (
        artcf_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        artcf_name varchar(20) not null comment '分类名称',
        artcf_icon varchar(64) not null default '' comment '分类图标',
        artcf_url varchar(128) not null default '' comment '分类url',
        artcf_pid smallint(5) unsigned not null default 0 comment '父级id',
        artcf_path varchar(32) not null default '' comment '分类路径',
        artcf_level tinyint(3) unsigned not null default 0 comment '分类水平',
        artcf_indexno tinyint(3) unsigned not null default 0 comment '序号',
        key (artcf_pid)
    )engine=Innodb charset=utf8mb4 comment '文章分类';

    -- 文章表
    create table if not exists cf_article(
        atic_id int(11) unsigned not null auto_increment comment '文章id',
        atic_cover varchar(128) not null default '' comment '文章封面',
        atic_title varchar(80) not null comment '文章标题',
        atic_abstract varchar(200) not null default '' comment '文章摘要，描述',
        atic_keywords varchar(100) not null default '' comment '文章关键词',
        atic_contents text not null comment '文章内容',
        atic_file_id varchar(64) not null default '' comment '文章文件',
        atic_add_time int(11) unsigned not null comment '文章发布时间',
        atic_upd_time int(11) unsigned not null default 0 comment '文章更新时间',
        atic_uid int(11) unsigned not null comment '文章发布者',
        atic_classify smallint(5) unsigned not null default 0 comment '文章类型',
        atic_province bigint(19) unsigned not null default 0 comment '所属省份',
        atic_state_city bigint(19) unsigned not null default 0 comment '所属州或城市',
        atic_area_county bigint(19) unsigned not null default 0 comment '所属区或县',
        atic_town bigint(19) unsigned not null default 0 comment '所属镇',
        atic_village bigint(19) unsigned not null default 0 comment '所属村',
        atic_is_up tinyint(5) unsigned not null default 0 comment '文章置顶,0:不置顶,大于0的用作置顶排序',
        atic_views int(10) unsigned not null default 0 comment '文章浏览量',
        atic_is_check tinyint(1) default 1 comment '文章审核，1:未审核，2:审核',
        atic_is_del tinyint(1) default 1 comment '文章删除，1:正常，2:删除',
        primary key (atic_id),
        key (atic_upd_time),
        key (atic_uid),
        key (atic_classify),
        key (atic_is_up)
    )engine=Innodb charset=utf8mb4 comment '文章表';



    -- 网站地图
    create table if not exists cf_web_map (
        wbmp_id smallint(5) unsigned not null auto_increment primary key comment '主键id',
        wbmp_name varchar(20) not null comment '导航名称',
        wbmp_flag varchar(20) not null unique comment '导航标识符',
        wbmp_icon varchar(256) not null default '' comment '导航图标',
        wbmp_url varchar(256) not null default '' comment '导航url',
        wbmp_pid smallint(5) unsigned not null default 0 comment '父级id',
        wbmp_path varchar(32) not null default '' comment '导航路径',
        wbmp_level tinyint(3) unsigned not null default 0 comment '导航水平',
        wbmp_indexno tinyint(3) unsigned not null default 0 comment '序号',
        key (wbmp_pid)
    )engine=Innodb charset=utf8mb4 comment '网站地图';
