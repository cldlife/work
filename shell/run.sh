# Shell脚本多进程启动程序
# 2014/07/25
#
# param string ShellName    $1 脚本应用名称
# param int ProcessNum      $2 启动进程数
# param int ServerId 		$3 当前服务器id
#

#!/bin/bash
PHPBin=/usr/local/app/php5-cgi/bin/php
ShellPath=/wwwroot/WanZhu-DB-v1-20160908/shell

ShellName=''
ProcessNum=1
ServerId=1

#check running thread
checkThread()
{
  ShellCount=`ps -ef | grep -i "$1 $2" | grep -vE "grep|bash|sh" | wc -l`
  if [ $ShellCount -gt 0 ]
  then
    echo "Thread already running..."
  else
    mkdir -m 766 $ShellPath/log/$1 -p
    autoThread $1 $2
  fi
} 

#start thread
autoThread()
{
  for((i=1;i<=$2;i++))
  do
    nohup $PHPBin $ShellPath/main.php $1 $2 ${i} >> $ShellPath/log/$1/process_${i}_running_$(date +%Y%m%d).log 2>&1 &
  done
}

#run script
if [ "${1-$ShellName}" ]
then
  checkThread ${1-$ShellName} ${2-$ProcessNum}
else
  echo "Not found shell name..."
fi