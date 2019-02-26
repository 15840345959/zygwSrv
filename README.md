# 中移和采

# 权限说明

农场管理员权限



#### v1.4更新内容（2018-8-20）

- 添加企业、农场管理员功能；


本地运行  
php artisan serve

1. 运行server 

composer update
php -S localhost:8000 -t public


2. 部署

- 安装php

https://blog.csdn.net/21aspnet/article/details/8203447  
http://php.net/manual/zh/install.unix.nginx.php  

- 安装与配置

http://php.net/manual/zh/install.php  

```
yum -y install python-devel
yum -y install libxml2
wget http://cn2.php.net/distributions/php-7.2.8.tar.gz
./configure --enable-fpm --with-mcrypt --enable-mbstring --with-curl --disable-debug --enable-inline-optimization --with-bz2  --with-zlib --enable-sockets --enable-sysvsem --enable-sysvshm --enable-pcntl --enable-mbregex --with-mhash --enable-zip --with-pcre-regex --with-mysql --with-mysqli --with-gd --with-jpeg-dir --with-openssl --with-pdo-mysql
make
make install
```

查看到已有扩展  
php -m

安装pdo  
```
cd pdo

./configure --with-php-config=/usr/local/bin/php-config
phpize
make && make install

cd pdo_mysql
./configure --with-php-config=/usr/local/bin/php-config --with-pdo-mysql=/usr/bin/mysql --with-zlib-dir=
```
-- 关闭PHP  
```
killall php-fpm
php-fpm /usr/local/etc/php-fpm.conf
```
composer可以切换至中国镜像  
修改 composer 的全局配置文件（推荐方式）  
```
composer config -g repo.packagist composer https://packagist.phpcomposer.com
```

#### nginx 启动、停止、更新配置
```
nginx -c /usr/local/webserver/nginx/conf/nginx.conf
nginx -s stop
nginx -s reload
```

##### mysql

```
mysql -uhecai -P63306 -Dzyhcdb -p < zyhcdb_20180804.sql
```

#### redis


3. 添加商户管理员

- 管理员管理：
```
-- t_admin_info添加字段merchant_id, 
alter table t_admin_info add merchant_id int(11) comment '农场或企业id';
-- t_admin_info表中字段role类型，0：管理员 1：根管理员 ，根管理员无法被删除; 2:企业管理员；3:企业运营人员；4:农场管理员；5:农场运营人员；
alter table t_admin_info modify column role varchar(45) comment '类型，0：管理员 1：根管理员 ，根管理员无法被删除；2:企业管理员；3:企业运营人员；4:农场管理员；5:农场运营人员；';
```
- 商品：
```
-- t_goods_info添加creator_id
alter table t_goods_info add creator_id int(11) comment '创建者id';
-- 添加merchant_id、merchant_type
alter table t_goods_info add merchant_id int(11) comment '商户id';
alter table t_goods_info add merchant_type int(11) comment '商户类型，0:和采；1：企业；2：农场；';
-- 添加is_superior
alter table t_goods_info add is_superior int(2) DEFAULT '0' comment '是否精品';
```
- 订单表：
```
-- t_order_info 添加merchant_id，order_type订单类型 0：历史农场订单 有farm_id green_house_id house_land_id； 1：新型商品订单 有goods_id；2：企业下商品订单;
alter table t_order_info add merchant_id int(11) comment '农场或企业id';
alter table t_order_info add merchant_type int(2) comment '商户类型，0:和采；1：企业；2：农场；';
-- 修改order_type注释
alter table t_order_info modify column order_type varchar(45) comment '订单类型 0：历史农场订单 有farm_id green_house_id house_land_id； 1：新型商品订单 有goods_id；';
```
- 资讯表：
```
-- t_zx_info添加creator_id
alter table t_zx_info add creator_id int(11) comment '创建者id';

-- t_zx_type添加privilege字段
alter table t_zx_type add privilege varchar(100) comment '权限字段';
```
- 管理后台：

农场、企业管理员去掉管理员、用户、农场管理、合作企业模块；

农场管理员4：  
农场管理：查看农场下的大棚；  
订单管理  

企业管理员2：  
资讯管理；  
精选商品：  
订单管理  
合作企业->企业资讯管理  
       ->企业商品管理  

- 订单管理：  
农场、企业管理员只管理本商户的订单；

- 资讯管理：  
企业管理员只管理本商户的企业资讯；资讯类别只限于企业资讯;

数据库表sql：
```
alter table t_admin_info add merchant_id int(11) comment '农场或企业id'; 
alter table t_admin_info modify column role varchar(45) comment '类型，0：管理员 1：根管理员 ，根管理员无法被删除；2:企业管理员；3:企业运营人员；4:农场管理员；5:农场运营人员；';
alter table t_goods_info add creator_id int(11) comment '创建者id'; 
alter table t_goods_info add merchant_id int(11) comment '商户id';
alter table t_goods_info add merchant_type int(11) comment '商户类型，0:和采；1：企业；2：农场；'; 
alter table t_goods_info add is_superior int(2) DEFAULT '0' comment '是否精品';
alter table t_order_info add merchant_id int(11) comment '农场或企业id';
alter table t_order_info modify column order_type varchar(45) comment '订单类型 0：历史农场订单 有farm_id green_house_id house_land_id； 1：新型商品订单 有goods_id；2：企业商品订单';
alter table t_zx_info add creator_id int(11) comment '创建者id';
alter table t_zx_type add privilege varchar(100) comment '权限字段';
```
