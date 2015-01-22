<?php
require_once("config/db_config.php");
global $DB;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Raving Ruby - Build Google Product List</title>
	</head>

	<body>
	<h2>Raving Ruby - Google Product List Build Tool</h2>
	<?php

		//first line of the tab delimeted feed file
		$firstLine = "id\ttitle\tdescription\tgoogle product category\tproduct type\tlink\timage link\tcondition\tavailability\tprice\tgender\tage group\tcolor\tbrand\tidentifier exists\n";

		$result = $DB->query("SELECT * from wp_posts WHERE post_type = 'product'");

		//create the feed file and write the first line
		$googleFile = fopen("googlefile.txt", "w") or die("Unable to open file!");
		fwrite($googleFile, $firstLine);

		echo "writing google file...";
		while ($row = $DB->fetch_array_assoc($result)) {
			$id = $row['ID'];

			//find the id of the image used for the thumbnail
			$result_meta = $DB->query("Select * from wp_postmeta WHERE post_id = '".$id."' AND meta_key = '_thumbnail_id'");
			$meta = $DB->fetch_array_assoc($result_meta);
			$imageID = $meta['meta_value'];

			echo "Found Meta Value: " . $imageID;

			//find the value of the path for that image
			$result_image = $DB->query("Select * from wp_postmeta WHERE post_id = '".$imageID."' AND meta_key = '_wp_attached_file'");
			$imageQuery = $DB->fetch_array_assoc($result_image);
			$imagePath = $imageQuery['meta_value'];

			//find the price
			$result_meta = $DB->query("Select * from wp_postmeta WHERE post_id = '".$id."' AND meta_key = '_price'");
			$meta = $DB->fetch_array_assoc($result_meta);
			$price = $meta['meta_value'];

			$idForGoogle = $id.$row['post_name'];
			$idForGoogle = substr($idForGoogle, 0, 50);

			$description = preg_replace("/\r|\n/", "", $row['post_excerpt']);
			$description = strip_tags($description);
			$line = $idForGoogle . "\t" . $row['post_title'] . "\t" . $description . "\t" .
				"Apparel & Accessories > Jewelry" . "\t" . "Handmade Women's Accessories > Jewelry" . "\t" .
				"http://ravingruby.com/product/".$row['post_name'] . "\t" . "http://ravingruby.com/wp-content/uploads/" . $imagePath . "\t" .
				"new" . "\t" . "in stock" . "\t" . $price." USD" . "\t" . "female" . "\t" . "adult" . "\t" . "varies" . "\t" . "Raving Ruby" . "\t" . "FALSE" . "\n";
			fwrite($googleFile, $line);
		}

		fclose($googleFile);

	?>
	</body>
</html>