<?php
include './include/connection.inc.php';
include './include/functions.inc.php';
?>

<?php
if(!empty($_POST)){
    $title = (string) ($_POST['title'] ?? '');
    $date = (string) ($_POST['date'] ?? '');
    $message = (string) ($_POST['message'] ?? '');
    $imageName = null;

    if (!empty($_FILES) && !empty($_FILES['image'])) {
        if ($_FILES['image']['error'] === 0 && $_FILES['image']['size'] !== 0) {
            $nameWithoutExtension = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $name = preg_replace('/[^a-zA-Z0-9]/', '', $nameWithoutExtension);
    
            $originalImage = $_FILES['image']['tmp_name'];
            $imageName = $name . '-' . time() . '.jpg';
            $destImage = __DIR__ . '/uploads/' . $imageName;
    
            $imageSize = getimagesize($originalImage);
            if (!empty($imageSize)) {
                [$width, $height] = $imageSize;
    
                $maxDim = 400;
                $scaleFactor = $maxDim / max($width, $height);
        
                $newWidth = $width * $scaleFactor;
                $newHeight = $height * $scaleFactor;

                $im = imagecreatefromjpeg($originalImage);

        
/////////////////////////////////
                        // Detect MIME type and load image accordingly
        // $mime = mime_content_type($originalImage);
        // switch ($mime) {
        //     case 'image/jpeg':
        //         $im = imagecreatefromjpeg($originalImage);
        //         break;
        //     case 'image/png':
        //         $im = imagecreatefrompng($originalImage);
        //         break;
        //     case 'image/webp':
        //         $im = imagecreatefromwebp($originalImage);
        //         break;
        //     default:
        //         die("Unsupported image type: $mime");
        // }
                

//////////////////////////////////
                if (!empty($im)) {
                    $newImg = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    
        
                    imagejpeg($newImg, $destImage);
                }
            }
            
        }
    

}



$stmt = $pdo->prepare('INSERT INTO `entries` (`title`, `date`, `message`, `image`) VALUES (:title, :date, :message, :image)');
$stmt->bindValue('title',$title);
$stmt->bindValue('message',$message);
$stmt->bindValue('date',$date);
$stmt->bindValue('image',$imageName);
$stmt->execute();
echo '<a href="index.php">continue to the diary...</a>';
die(); 
}         
?>

<?php require './views/header.inc.php'; ?>
    <main class="main">
        <div class="container">
            <h1 class="main-heading">New Entry</h1>

            <form method="POST" action="form.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="from-group__label" for="title">Title:</label>
                    <input class="from-group__input" type="text" id="title" name="title" required/>
                </div>
                <div class="form-group">
                    <label class="from-group__label" for="date">Date:</label>
                    <input class="from-group__input" type="date" id="date" name="date" required />
                </div>
                <div class="form-group">
                    <label class="from-group__label" for="message">Message:</label>
                    <textarea class="from-group__input" id="message" name="message" rows="6" required></textarea>
                </div>
                <div class="form-group">
                    <label class="from-group__label" for="image">Image:</label>
                     <input class="from-group__input" type="file" id="image" name="image" />
                </div>
                <div class="form-submit">
                    <button class="button">
                        <svg class="button__icon" viewBox="0 0 34.7163912799 33.4350009649">
                            <g style="fill: none; stroke: currentColor; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2px;">
                                <polygon points="20.6844359446 32.4350009649 33.7163912799 1 1 10.3610302393 15.1899978903 17.5208901631 20.6844359446 32.4350009649"/>
                                <line x1="33.7163912799" y1="1" x2="15.1899978903" y2="17.5208901631"/>
                            </g>
                        </svg>
                        Save!
                    </button>
                </div>
            </form>
        </div>
    </main>
    <footer class="footer">
        <div class="container">
            <h3 class="footer__heading">PHP diary project</h3>
            <p class="footer__desc">This PHP diary project allows users to systematically document and reflect on their learning journey, enhancing retention and providing valuable insights into their personal growth and development.</p>
        </div>
    </footer>
</body>
</html>

