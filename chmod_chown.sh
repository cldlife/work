# Shell - init file permession
# 2014/10/12

#!/bin/bash
chown nobody:nobody /wwwroot/WanZhu-DB-v1-20160908 -Rf
chown nobody:nobody /dev/shm/
chmod 755 /wwwroot/WanZhu-DB-v1-20160908 -Rf
chmod 766 /wwwroot/WanZhu-DB-v1-20160908/log -Rf
chmod 766 /wwwroot/WanZhu-DB-v1-20160908/storage -Rf

