import ftplib
import sys

ftp_host = "ftpupload.net"
ftp_user = "if0_42730053"
ftp_pass = "sapReyWJaK"

try:
    print(f"Connecting to {ftp_host}...")
    ftp = ftplib.FTP(ftp_host, timeout=10)
    ftp.login(ftp_user, ftp_pass)
    print("FTP Login Successful!")
    print("Current directory:", ftp.pwd())
    print("Listing directories:")
    ftp.dir()
    ftp.quit()
except Exception as e:
    print(f"FTP Error: {e}")
