Hello {{$mail_data['name']}}!
Hello {{$mail_data['link']}}!
<a href="{{ url('http://127.0.0.1:8000/api/forgotpassword/'. $mail_data['token']) }}">Click Here to Reset Password</a>.
