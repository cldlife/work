# Shell同步数据脚本
# 2014/10/12
#
# param string DataFile  $1 需同步有文件或目录
# param int DstHost      $2 目标HOST地址
#

#!/bin/bash
DataFile=''
DstHost=''

if [ -f "${1-$DataFile}" -o -d "${2-$DstHost}" ]
then
  chown nobody:nobody ${1-$DataFile}
  chmod 755 ${1-$DataFile}
  rsync --exclude ".svn" --delete -atv ${1-$DataFile} root@${2-$DstHost}:${1-$DataFile}
else
  echo "No data rsync..."
fi