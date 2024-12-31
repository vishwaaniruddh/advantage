# Invoke nocEmail.php script

Invoke-WebRequest -Uri "http://localhost/clarify/cronJobs/readEmailThread.php" -UseBasicParsing

Invoke-WebRequest -Uri "http://localhost/clarify/cronJobs/nocEmail.php" -UseBasicParsing

# Invoke alarmEmail.php script
Invoke-WebRequest -Uri "http://localhost/clarify/cronJobs/alarmEmail.php" -UseBasicParsing
