<?php
1\
問題：進入docker容器無法ping外網?
解決：前提是宿主機(容器的宿主機是centos系統)要能ping通外網，然後通過把宿主機iptables的ip_forward設置為1再重啟，再進入docker容器就可以ping通外網了.
解決步驟： 
exit 退出(確保進入宿主機centos系統)
vi /etc/sysctl.conf
net.ipv4.ip_forward = 1
reboot 重啟centos系統

或者：
echo "net.ipv4.ip_forward=1">>/etc/sysctl.conf
sysctl -p (/etc/sysctl.conf立即生效)



INSTALL DOCKER:
首先，你需要安装EPEL仓库， docker的包是由EPEL提供的
$ yum install epel-release

CentOS-6 中 安装 docker-io 之前需要先卸载 docker 包。
如果你已经安装了(不相关)的docker包，它会跟docker-io有冲突，有一个错误报告，如果想继续安装docker-io，请先删除docker.
$ sudo yum -y remove docker

下一步，安装 docker-io 包来为我们的主机安装 Docker。
$ sudo yum install docker-io

或者 下一步，来让我们安装docker-io在我们的主机上
# sudo yum -y install docker-io
      升级docker-io包
#sudo yum -y update docker-io
      现在我们就安装好了，让我们开始docker进程
# sudo service docker start
      如果你想让他开机启动，我们需要这样做
# sudo chkconfig docker on
      现在让我们确认一下docker是否工作了
# sudo docker run -i -t fedora /bin/bash

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
$ sudo docker run -i -t centos /bin/bashINSTALL DOCKER:
首先，你需要安装EPEL仓库， docker的包是由EPEL提供的
$ yum install epel-release

CentOS-6 中 安装 docker-io 之前需要先卸载 docker 包。
如果你已经安装了(不相关)的docker包，它会跟docker-io有冲突，有一个错误报告，如果想继续安装docker-io，请先删除docker.
$ sudo yum -y remove docker

下一步，安装 docker-io 包来为我们的主机安装 Docker。
$ sudo yum install docker-io

或者 下一步，来让我们安装docker-io在我们的主机上
# sudo yum -y install docker-io
      升级docker-io包
#sudo yum -y update docker-io
      现在我们就安装好了，让我们开始docker进程
# sudo service docker start
      如果你想让他开机启动，我们需要这样做
# sudo chkconfig docker on
      现在让我们确认一下docker是否工作了
# sudo docker run -i -t fedora /bin/bash

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



总的来说分为以下几种：
容器生命周期管理 — docker [run|start|stop|restart|kill|rm|pause|unpause]
容器操作运维 — docker [ps|inspect|top|attach|events|logs|wait|export|port]
容器rootfs命令 — docker [commit|cp|diff]
镜像仓库 — docker [login|pull|push|search]
本地镜像管理 — docker [images|rmi|tag|build|history|save|import]
其他命令 — docker [info|version]





1/Docker安装应用(CentOS 6.5_x64)

Docker官网 http://www.docker.com/

一，安装EPEL
yum install epel-release
 
关于EPEL：https://Fedoraproject.org/wiki/EPEL/zh-cn
 
>rpm -ivh http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm
 >rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-EPEL-6
 >yum -y install yum -priorities
 

二，安装Docker
 
>yum -y install docker-io
 >service docker start
 >chkconfig docker on
 

三，应用Docker
 
1，获取Centos镜像
 >docker pull centos:latest
 
2，查看镜像运行情况
 >docker images centos
 
3，在容器下运行 shell bash
 >docker run -i -t centos /bin/bash
 
4，停止容器
 >docker stop <CONTAINER ID>
 
5，查看容器日志
 >docker logs -f <CONTAINER ID>
 
6，删除所有容器
 >docker rm $(docker ps -a -q)
 
7，删除镜像
 >docker rmi <image id/name>
 
8，提交容器更改到镜像仓库中
 >docker run -i -t centos /bin/bash
 >useradd myuser
 >exit
 >docker ps -a |more
 >docker commit <CONTAINER ID> myuser/centos
 
9，创建并运行容器中的 hello.sh
 >docker run -i -t myuser/centos /bin/bash
 >touch /home/myuser/hello.sh
 >echo "echo \"Hello,World!\"" > /home/myuser/hello.sh
 >chmod +x /home/myuser/hello.sh
 >exit
 >docker commit <CONTAINER ID> myuser/centos
 >docker run -i -t myuser/centos /bin/sh /home/myuser/hello.sh
 
10，在容器中运行Nginx
 
在容器中安装好Nginx，并提交到镜像中
 >docker run -t -i -p 80:80 nginx/centos /bin/bash
 启动Nginx
 >/data/apps/nginx/sbin/nginx
 (还不清楚如何在后台运行!!!)
 
在浏览器访问宿主机80端口。
 
11，映射容器端口
 >docker run -d -p 192.168.9.11:2201:22 nginx/centos /usr/sbin/sshd -D
 
用ssh root@192.168.9.11 -p 2201 连接容器，提示：
 
Connection to 192.168.1.205 closed.(此问题还未解决!!!)
 

可能会遇到的问题：
 ##在容器里面修改用户密码的时候报错：
 /usr/share/cracklib/pw_dict.pwd: No such file or directory
 PWOpen: No such file or directory
 
解决：
 yum -y reinstall cracklib-dicts











2/CentOS 7安装Docker V1.0

rpm -Uvh http://dl.Fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm

yum -y install docker-io                      # 仅此一条命令就可以搞定；

service docker start                          # 启动docker

chkconfig docker on                            # 加入开机启动

docker pull centos:latest                      #从docker.io中下载centos镜像到本地 /var/lib/docker/graph

docker images                                  #查看已下载的镜像

docker run -i -t centos /bin/bash              #启动一个容器

docker imr image_id                            #删除镜像

docker rmi $(docker images | grep none | awk '{print $3}' | sort -r)  #删除所有镜像

docker ps -a                                  #查看所有容器(包括正在运行和已停止的)

docker start container                        #开启一个容器（注意container_id和image_id是完全不一样de）

docker logs <容器名orID> 2>&1 | grep '^User: ' | tail -n1 #查看容器的root用户密码,因为docker容器启动时的root用户的密码是随机分配的。所以，通过这种方式就可以得到redmine容器的root用户的密码了

docker logs -f <容器名orID>                    #查看容器日志

docker rm $(docker ps -a -q)                    #删除所有容&删除单个容器docker rm <容器名orID>

docker run --name redmine -p 9003:80 -p 9023:22 -d -v /var/redmine/files:/redmine/files -v  /var/redmine/mysql:/var/lib/mysql sameersbn/redmine
#运行一个新容器，同时为它命名、端口映射、文件夹映射。以redmine镜像为例

docker run -i -t --name sonar -d -link mmysql:db  tpires/sonar-server
# 一个容器连接到另一个容器&sonar容器连接到mmysql容器，并将mmysql容器重命名为db。这样，sonar容器就可以使用

db的相关的环境变量了。

#当需要把一台机器上的镜像迁移到另一台机器的时候，需要保存镜像与加载镜像。

机器a

docker save busybox-1 > /home/save.tar

使用scp将save.tar拷到机器b上，然后：

docker load < /home/save.tar

docker build -t <镜像名> <Dockerfile路径>        #构建自己的镜像














