@echo off
cd /d C:\xampp\htdocs\voterlist-system
echo Starting at %date% %time% >> C:\xampp\htdocs\voterlist-system\server.log
C:\xampp\php\php.exe artisan serve --port 9999 >> C:\xampp\htdocs\voterlist-system\server.log 2>&1
echo Crashed at %date% %time% >> C:\xampp\htdocs\voterlist-system\server.log
timeout /t 3