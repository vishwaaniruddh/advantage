Set objShell = CreateObject("WScript.Shell")
objShell.Run "powershell.exe -ExecutionPolicy Bypass -File ""C:\xampp\htdocs\clarify\cronJobs\runScript.ps1""", 0, False
Set objShell = Nothing
