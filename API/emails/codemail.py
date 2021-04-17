import smtplib
import sys

from string import Template

from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

SEND_FROM = 'identitymanagementOU@gmail.com'
EMAIL_PWD = 'testtest123%'

if(len(sys.argv) != 4):
    sys.exit("Incorrect number of parameters")

email = sys.argv[1]
name = sys.argv[2]
code = sys.argv[3]


def read_template(file_name):
    with open(file_name, 'r', encoding='utf-8') as msg_template:
        msg_template_content = msg_template.read()
    return Template(msg_template_content)

def main():
    message_template = read_template('/var/www/idm/API.emails/codeemail_template.txt')

    # set up the SMTP server
    smtplib_server = smtplib.SMTP(host='smtp.gmail.com', port=587)
    smtplib_server.starttls()
    smtplib_server.login(SEND_FROM, EMAIL_PWD)

    # Get each user detail and send the email:
    multipart_message = MIMEMultipart()       
 

    msg = message_template.substitute(PERSON_NAME=name.title(), LOGIN_CODE=code)

    print(msg)


    multipart_message['From']=SEND_FROM
    multipart_message['To']= email
    multipart_message['Subject']="Identity Management: Use this code to sign in"
       

    multipart_message.attach(MIMEText(msg, 'html'))
       

    smtplib_server.send_message(multipart_message)
    del multipart_message
       

    smtplib_server.quit()
   
if __name__ == '__main__':
    main()