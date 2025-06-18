<?php
include './include/connection.inc.php';
include './include/functions.inc.php';

date_default_timezone_set('Asia/Kolkata');

$perPage = 3;
$page =(int) ($_GET['page'] ?? 1);
$offset = ($page - 1) * $perPage;

$stmtCount = $pdo->prepare('SELECT COUNT(*) AS `count` FROM `entries`');
$stmtCount->execute();
$count = $stmtCount->fetch(PDO::FETCH_ASSOC)['count'];
var_dump($count);

$numPages = ceil($count / $perPage);

$stmt = $pdo->prepare('SELECT * FROM `entries` ORDER BY `entries`.`date` DESC ,`entries`.`id` DESC LIMIT :perPage OFFSET :offset');
$stmt->bindValue('perPage',$perPage,PDO::PARAM_INT);
$stmt->bindValue('offset',$offset,PDO::PARAM_INT);
$stmt->execute();
$results = $stmt -> fetchAll(PDO::FETCH_ASSOC);
// var_dump($results);




?>

<?php require './views/header.inc.php'; ?>

    <main class="main">
        <div class="container">
            <h1 class="main-heading">Entries</h1>
           
            <?php foreach($results AS $result){?>
            <div class="card">
                <div class="card__image-container">
                    <img class="card__image" src="/uploads/<?php echo $result['image']; ?>" alt="" />
                </div>
                <?php 
                $dateEXploded = explode('-',$result['date']);
                $timeStamp = mktime(12,0,0,$dateEXploded[1],$dateEXploded[2],$dateEXploded[0]);
                ?>
                <div class="card__desc-container">
                    <div class="card__desc-time"><?php echo date('m/d/y',$timeStamp);?></div>
                    <h2 class="card__heading"><?php echo $result['title'];?></h2>
                    <p class="card__paragraph">
                    <?php echo $result['message'];?>
                    </p>
                </div>
            </div>
            <?php } ?>

           <?php if($numPages !== 1){ ?>
            <ul class="pagination">
            <?php if($page >1){ ?>
                <li class="pagination__li">
                    
                    <a class="pagination__link" href="index.php?<?php if($page > 1) { echo http_build_query(['page'=>($page-1)]) ; }?>"><</a>
            </li>
            <?php } ?>
            <?php for($i = 1;$i <= $numPages;$i++){ ?>
                
                 <li class="pagination__li"> 
                    <a  class="pagination__link <?php if($page === $i){echo 'pagination__link--active'; }?>  " href="index.php?<?php echo http_build_query(['page'=>$i]) ;?>"><?php echo $i;?></a>
                </li>
                                
            <?php } ?>
            <?php if($page < $numPages){ ?>
            <li class="pagination__li">
                <a class="pagination__link" href="index.php?<?php if($page < $numPages) { echo http_build_query(['page'=>($page+1)]) ; }?>">></a>
            </li>
            <?php } ?>
            </ul> 
          <?php } ?>

        </div>
    </main>

<?php require './views/footer.inc.php'; ?>



        

            