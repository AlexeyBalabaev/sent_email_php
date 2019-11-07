<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	
<?php

	$file = 'test.txt';
	$mailTo = "admin@dpmain";
	$from = "test@files.com";
	$subject = "Test file";
	$message = "Test message with attachment";
	$r = sendMailAttachment($mailTo, $from, $subject, $message, $file);
	echo ($r)?'The message sent.':'Error. The message didnt send!';

	/**
	* Send email with attachment
	* @param string $mailTo
	* @param string $from
	* @param string $subject
	* @param string $message
	* @param string|bool $file
	*
	* @return bool
	*/

	function sendMailAttachment($mailTo, $from, $subject, $message, $file = false) {
		$separator = "---";

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "From: $from\nReply-To: $from\n";
		$headers .= "Content-Type: multipart/mixed; boundary=\"$separator\"";

		if($file) {
			$bodyMail = "--$separator\n";
			$bodyMail .= "Content-type: text/html; charset='utf-8'\n";
			$bodyMail .= "Content-Transfer-Encoding: quoted-printable";
			$bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode(basename($file))."?=\n\n";
			$bodyMail .= $message."\n";
			$bodyMail .= "--$separator\n";
			$fileRead = fopen($file, "r");
			$contentFile = fread($fileRead, filesize($file));
			fclose($fileRead);
			$bodyMail .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode(basename($file))."?=\n";
			$bodyMail .= "Content-Transfer-Encoding: base64\n";
			$bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode(basename($file))."?=\n\n";
			$bodyMail .= chunk_split(base64_encode($contentFile))."n";
			$bodyMail .= "--".$separator."--\n";
		} else {
			$bodyMail = $message;
		}

		$result = mail($mailTo, $subject, $bodyMail, $headers);
		return $result;
	}

?>

</body>
</html>