cd %~pd0

rem 准备docker源
docker pull billryan/gitbook
docker rm -f gitbook
docker run -d --name gitbook billryan/gitbook tail -f /etc/issue
rem 阿里云镜像
docker cp deb.list gitbook:/etc/apt/sources.list
rem 安装字体文件
docker exec -it gitbook bash -c "apt-get -y update && apt-get -y  install fonts-droid"
rem 保存字体镜像
docker commit gitbook docker-gitbook
docker rm -f gitbook
