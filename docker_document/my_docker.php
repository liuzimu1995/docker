<?php
vmware添加映射端口：
	C:\ProgramData\VMware\vmnetnat.conf
		把虚拟机的5022端口映射到主机的50022端口，在[incomingtcp]下面添加类似的语句
			50022 = 192.168.223.130:5022
	进入cmd重启vmware nat service
		net stop "Vmware Nat Service"
		net start "Vmware Nat Service"
	查看端口是否开启
		netstat -ano | find "50022"

给虚拟机nat网卡开启端口映射

在VMware Player中,并不像VMware Station中提供网络编辑功能,因此如果想使用port forward功能,则需要进行手动配置.

例如在Windows xp操作系统中,找到vmware nat的配置文件：

C:\Documents and Settings\All Users\Application Data\VMware\vmnetnat.conf

在windows 7系统中，vmware nat的配置文件在：

C:\ProgramData\VMware\vmnetnat.conf

如果想使用TCP,则修改其[incomingtcp]下面的配置选项,如：

8888 = 192.168.20.56:80

其意思是将主机的8888端口映射到虚拟机的80端口,这样其它的机器只要访问这台主机的8888端口,就可以访问到其虚拟机的80端口了

比如我的是增加了一下几项：

22:192.168.20.56:22  （ssh端口）

80:192.168.20.56:80   （将虚拟机的80端口映射到主机的80端口，这样的话，主机上不能再使用80端口）

 

最后需要在主机上重新启动一下vmware NAT service服务,然后就可以正常访问了.

服务的查看方法，在cmd中执行：

sc query|find /i "vmware"
执行后发现有如下服务名：

DISPLAY_NAME: VMware Authorization Service

DISPLAY_NAME: VMware DHCP Service

DISPLAY_NAME: VMware USB Arbitration Service

SERVICE_NAME: VMware NAT Service

DISPLAY_NAME: VMware NAT Service

在cmd中使用net stop "VMware NAT Service"&net start "VMware NAT Service"命令可以重启服务。

注:如果访问不了,请查看Windows防火墙是否阻止了这些端口.

另外，可以在本地机器设置一个host，这样可以使用域名的方式登录自己的虚拟机，或者查看虚拟机上的网站。

比如在windows下可以设置C:\Windows\System32\drivers\etc\hosts文件，在其中增加：

127.0.0.1   saiwaike.org

那样就可以使用saiwaike.org访问虚拟机中的网站了。











centos 系统可以查看 /var/log/secure日志

mysql镜像：
	$ docker run --name some-mysql[容器名] -v /my/own/datadir:/var/lib/mysql[把主机上的/my/own/datadir映射到容器中的/var/lib/mysql里面] -e MYSQL_ROOT_PASSWORD=my-secret-pw[mysql密码] -d[守护进程模式] daocloud.io/mysql:tag[镜像:标签]
	$ docker inspect -f "{{ .HostConfig.Links }}" nginxlinkmysql[容器名]

	$ docker inspect --format '{{.NetworkSettings.IPAddress}}' 容器ID



netstat -anp

安装数据库：
	进入安装mysql软件目录：执行命令 cd /usr/local/mysql
	修改当前目录拥有者为mysql用户：执行命令 chown -R mysql:mysql ./
	安装数据库：执行命令 ./scripts/mysql_install_db --user=mysql
	修改当前目录拥有者为root用户：执行命令 chown -R root:root ./
	修改当前data目录拥有者为mysql用户：执行命令 chown -R mysql:mysql data

连接mysql数据库
设置密码
	mysql -u 用户 -p 
		回车输入密码，密码默认为空
	use mysql; //使用mysql数据库
	update user set password=password('填写密码') where user='root'; //更新user表的password字段的值
	flush privileges; //刷新权限
设置Mysql远程访问
	grant all privileges on *.* to 'root'@'%' identified by 'root' with grant option;

授权root用户进行远程连接，注意替换 “password” 为 root 用户真正的密码：
	1\grant all privileges on *.* to root@"%" identified by "password" with grant option;
		"%"可以使用具体ip地址
	2\flush privileges;

MYSQL(on centos7)::
其实想要重置 5.7 的密码很简单，就一层窗户纸：

1、修改 /etc/my.cnf，在 [mysqld] 小节下添加一行：skip-grant-tables=1

这一行配置让 mysqld 启动时不对密码进行验证

2、重启 mysqld 服务：systemctl restart mysqld

3、使用 root 用户登录到 mysql：mysql -u root 

4、切换到mysql数据库，更新 user 表：

update user set authentication_string = password('root'), password_expired = 'N', password_last_changed = now() where user = 'root';

在之前的版本中，密码字段的字段名是 password，5.7版本改为了 authentication_string

5、退出 mysql，编辑 /etc/my.cnf 文件，删除 skip-grant-tables=1 的内容

6、重启 mysqld 服务，再用新密码登录即可



另外，MySQL 5.7 在初始安装后（CentOS7 操作系统）会生成随机初始密码，并在 /var/log/mysqld.log 中有记录，可以通过 cat 命令查看，找 password 关键字

找到密码后，在本机以初始密码登录，并且（也只能）通过 alter user 'root'@'localhost' identified by 'root' 命令，修改 root 用户的密码为 root，然后退出，重新以root用户和刚设置的密码进行登录即可。

环境介绍：CentOS 6.7
MySQL版本：5.7.11
1、查看现有的密码策略
mysql> SHOW VARIABLES LIKE 'validate_password%';
参数解释：
1).validate_password_dictionary_file 指定密码验证的文件路径;
2).validate_password_length  密码最小长度
3).validate_password_mixed_case_count  密码至少要包含的小写字母个数和大写字母个数;
4).validate_password_number_count  密码至少要包含的数字个数
5).validate_password_policy 密码强度检查等级，对应等级为：0/LOW、1/MEDIUM、2/STRONG,默认为1
注意：
0/LOW：只检查长度;
1/MEDIUM：检查长度、数字、大小写、特殊字符;
2/STRONG：检查长度、数字、大小写、特殊字符字典文件。
6).validate_password_special_char_count密码至少要包含的特殊字符数
2、创建用户时报错：
mysql> create user 'miner'@'192.168.%' IDENTIFIED BY 'miner123';
ERROR 1819 (HY000): Your password does not satisfy the current policy requirements
报错原因：密码强度不够。
解决方法：(该账号为测试账号，所以采用降低密码策略强度)
mysql> set global validate_password_policy=0;
mysql> set global validate_password_number_count=0;
mysql> set global validate_password_length=4;
mysql> set global validate_password_special_char_count=0;
mysql> SHOW VARIABLES LIKE 'validate_password%';





退出镜像容器
// 先按 ，+ ctrl + p 再按 ctrl + q
先按 ctrl + p + q

//查看容器的内部IP
docker inspect --format='{{.NetworkSettings.IPAddress}}' <iamge id>
//容器备份
docker save -o ~/container-backup.tar server/centos:tag
//容器恢复
docker load -i ~/container-backup.tar

查看正在运行的容器的id等信息
	sudo docker ps
查看到容器的相关信息
	sudo docker inspect 容器id
查看容器的具体IP地址，如果输出是空的说明没有配置IP地址
	sudo docker inspect --format '{{.NetworkSettings.IPAddress}}' 容器id
	或
	docker inspect --format='{{.NetworkSettings.IPAddress}}' $CONTAINER_ID

当执行docker run时可以设定的资源如下：
	Detached vs Foreground
	Container Identification
	IPC Setting
	Network Settings
	Clean Up (--rm)
	Runtime Constraints on CPU and Memory
	Runtime Privilege, Linux Capabilities, and LXC Configuration

如果在执行run命令时没有指定-a，那么docker默认会挂载所有标准数据流，包括输入输出和错误。你可以特别指定挂载哪个标准流。
	$ sudo docker run -a stdin -a stdout -i -t ubuntu /bin/bash (只挂载标准输入输出)
如果在docker run 后面追加-d=true或者-d，则containter将会运行在后台模式(Detached mode)。此时所有I/O数据只能通过网络资源或者共享卷组来进行交互。因为container不再监听你执行docker run的这个终端命令行窗口。但你可以通过执行docker attach 来重新挂载这个container里面。需要注意的时， 如果你选择执行-d使container进入后台模式，那么将无法配合"--rm"参数。










//查看运行的CONTAINER ID
docker ps 

//查看所有CONTAINER ID
docker ps -a

//删除单个CONTAINER ID
docker rm <CONTAINER ID>

//删除所有CONTAINER ID
docker rm $(docker ps -a -q)

//停止所有CONTAINER ID
docker stop $(docker ps -a -q)

//删除单个镜像
docker rmi <image id>

//删除所有镜像
docker rmi $(docker images -q)

//进入镜像容器
docker exec -t -i <image id> /bin/bash

//创建新镜像容器
docker run -t -i <image id> /bin/bash

# 保存对容器的修改; -a, --author="" Author; -m, --message="" Commit message  
$docker commit ID new_image_name 
Note：  image相当于类，container相当于实例，不过可以动态给实例安装新软件，然后把这个container用commit命令固化成一个image。

# 显示docker系统的信息  
$docker info

# 下载image  
$docker pull image_name

# 列出镜像列表; -a, --all=false Show all images; --no-trunc=false Don't truncate output; -q, --quiet=false Only show numeric IDs  
$docker images 

# 检索image  
$docker search image_name 

# 显示一个镜像的历史; --no-trunc=false Don't truncate output; -q, --quiet=false Only show numeric IDs  
$docker history image_name 

# 在容器中运行"echo"命令，输出"hello word"  
$docker run image_name echo "hello word" 

# 交互式进入容器中  
$docker run -i -t image_name /bin/bash

# 在容器中安装新的程序  
$docker run image_name apt-get install -y app_name
Note：  在执行apt-get 命令的时候，要带上-y参数。如果不指定-y参数的话，apt-get命令会进入交互模式，需要用户输入命令来进行确认，但在docker环境中是无法响应这种交互的。apt-get 命令执行完毕之后，容器就会停止，但对容器的改动不会丢失。

# 停止、启动、杀死一个容器  
$docker stop Name/ID  
$docker start Name/ID  
$docker kill Name/ID  

# 列出一个容器里面被改变的文件或者目录，list列表会显示出三种事件，A 增加的，D 删除的，C 被改变的  
$docker diff Name/ID  

# 从一个容器中取日志; -f, --follow=false Follow log output; -t, --timestamps=false Show timestamps  
$docker logs Name/ID

# 从容器里面拷贝文件/目录到本地一个路径  
$docker cp Name:/container_path to_path  
$docker cp ID:/container_path to_path  

# 重启一个正在运行的容器; -t, --time=10 Number of seconds to try to stop for before killing the container, Default=10  
$docker restart Name/ID 

# 附加到一个运行的容器上面; --no-stdin=false Do not attach stdin; --sig-proxy=true Proxify all received signal to the process  
$docker attach ID 
Note： attach命令允许你查看或者影响一个运行的容器。你可以在同一时间attach同一个容器。你也可以从一个容器中脱离出来，是从CTRL-C。

# 保存镜像到一个tar包; -o, --output="" Write to an file  
$docker save image_name -o file_path  
# 加载一个tar包格式的镜像; -i, --input="" Read from a tar archive file  
$docker load -i file_path 
# 机器a  
$docker save image_name > /home/save.tar  
# 使用scp将save.tar拷到机器b上，然后：  
$docker load < /home/save.tar 


#build  
      --no-cache=false Do not use cache when building the image  
      -q, --quiet=false Suppress the verbose output generated by the containers  
      --rm=true Remove intermediate containers after a successful build  
      -t, --tag="" Repository name (and optionally a tag) to be applied to the resulting image in case of success  
$docker build -t image_name Dockerfile_path

# 登陆registry server; -e, --email="" Email; -p, --password="" Password; -u, --username="" Username  
$docker login  

# 发布docker镜像  
$docker push new_image_name









INSTALL DOCKER:
首先，你需要安装EPEL仓库， docker的包是由EPEL提供的
$ yum install epel-release

CentOS-6 中 安装 docker-io 之前需要先卸载 docker 包。
如果你已经安装了(不相关)的docker包，它会跟docker-io有冲突，有一个错误报告，如果想继续安装docker-io，请先删除docker.
$ sudo yum -y remove docker

下一步，安装 docker-io 包来为我们的主机安装 Docker。
$ sudo yum install docker-io

当 Docker 安装完成之后，你需要启动 docker 进程。
$ sudo service docker start

如果我们希望 Docker 默认开机启动，如下操作：
$ sudo chkconfig docker on

现在，我们来验证 Docker 是否正常工作。第一步，我们需要下载最新的 centos 镜像。
$ sudo docker pull centos

下一步，我们运行下边的命令来查看镜像，确认镜像是否存在：
$ sudo docker images centos

这里想要进去容器ping通外网则：
$ echo "net.ipv4.ip_forward=1">>/etc/sysctl.conf
/etc/sysctl.conf立即生效,或reboot重启：
$ sysctl -p

运行简单的脚本来测试镜像：
$ sudo docker run -i -t centos /bin/bash


