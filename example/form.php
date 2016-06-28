<?php
$inputDate = '';
$message = false;
if (isset($_POST[ 'date' ])) {
    require_once '../src/result.php';
    require_once '../src/date-validator.php';
    $inputDate = $_POST[ 'date' ];
    $result = DateValidator::validateHistoricalDate($inputDate, true);
    if ($result->isValid()) {
        $message = "Date ($inputDate) is valid";
        $inputDate = '';
    } else {
        if ($inputDate == 'null') $inputDate = '';
        $message = $result->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Form Example</title>
</head>
<body>
	<h1>Example form</h1>
<?php 
    if ($message) {
        print "\t<h3>The results</h3>\n\t<p>$message</p>\n";
    }
?>
	<h3>The form</h3>
	<p>Input a date in the format 'DD/MM/YYYY'</p>
	<form action="./form.php" method="post">
		<input type="text" name="date" value="<?php echo $inputDate; ?>" />
		<input type="submit" value="Validate date" />
	</form>
</body>
</html>
