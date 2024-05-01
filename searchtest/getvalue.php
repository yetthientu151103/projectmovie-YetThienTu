<?php
function getvalue($x){
        include 'connect.php';
if($x!=0)
{
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql1=" CREATE TABLE `temp_table` AS
        SELECT `movie_link`.`poster_link`, `movie`.`point`, `movie`.`rank`,`movie`.`id`
        FROM `movie_link`,`movie`
        JOIN `moviecategory` ON `movie`.`category_id` = `moviecategory`.`id`
        WHERE `movie`.`link_id`=`movie_link`.`id` AND `moviecategory`.`id` =".(int)$x;
        $conn->query($sql1);
        $updrank="UPDATE `temp_table`
        JOIN (
            SELECT m.`id`, COUNT(DISTINCT CASE WHEN s.`point` > m.`point` THEN s.`point` ELSE NULL END) + 1 AS new_rank
            FROM `temp_table` AS m
            LEFT JOIN `temp_table` AS s ON m.`point` < s.`point`
            GROUP BY m.`id`
        ) AS subquery ON `temp_table`.`id` = subquery.`id`
        SET `temp_table`.`rank` = subquery.`new_rank`;";
        $conn->query($updrank);


    echo '<div class="row-posters">';
    for ($i = 1; $i <= 6; $i++) {
        $sql = "SELECT `poster_link` FROM `temp_table` WHERE`temp_table`.`rank`=".$i;

        foreach ($conn->query($sql) as $row) {
          
            echo '<img src="'.$row["poster_link"].'" alt=" " class="row-poster row-posterLarge">'; 
        }
    }
    echo '</div>';

    echo '<div class="row-posters">';
    
    for ($i = 7; $i <= 12; $i++) {
        $sql = "SELECT `poster_link` FROM `temp_table` WHERE`temp_table`.`rank`=".$i;

        foreach ($conn->query($sql) as $row) {
          
            echo '<img src="'.$row["poster_link"].'" alt=" " class="row-poster row-posterLarge">'; 
        }
    }
    echo '</div>';

    echo '<div class="row-posters">';
    
    for ($i = 13; $i <= 18; $i++) {
        $sql = "SELECT `poster_link` FROM `temp_table` WHERE`temp_table`.`rank`=".$i;

        foreach ($conn->query($sql) as $row) {
          
            echo '<img src="'.$row["poster_link"].'" alt=" " class="row-poster row-posterLarge">'; 
        }
    }
    echo '</div>';
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$drop="DROP TABLE `temp_table`";
$conn->query($drop);
$conn = null;
}
else
{
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo '<div class="row-posters">';
        for ($i = 1; $i <= 6; $i++) {
            $sql = "SELECT `poster_link` FROM `movie_link` INNER JOIN `movie` ON `movie`.`link_id`=`movie_link`.`id` WHERE`movie`.`rank`=".$i;
    
            foreach ($conn->query($sql) as $row) {
              
                echo '<img src="'.$row["poster_link"].'" alt=" " class="row-poster row-posterLarge">'; 
            }
        }
        echo '</div>';
    
        echo '<div class="row-posters">';
        
        for ($i = 7; $i <= 12; $i++) {
            $sql = "SELECT `poster_link` FROM `movie_link` INNER JOIN `movie` ON `movie`.`link_id`=`movie_link`.`id` WHERE`movie`.`rank`=".$i;
    
            foreach ($conn->query($sql) as $row) {
              
                echo '<img src="'.$row["poster_link"].'" alt=" " class="row-poster">'; 
            }
        }
        echo '</div>';
    
        echo '<div class="row-posters">';
        
        for ($i = 13; $i <= 18; $i++) {
            $sql = "SELECT `poster_link` FROM `movie_link` INNER JOIN `movie` ON `movie`.`link_id`=`movie_link`.`id` WHERE`movie`.`rank`=".$i;
    
            foreach ($conn->query($sql) as $row) {
              
                echo '<img src="'.$row["poster_link"].'" alt=" " class="row-poster">'; 
            }
        }
        echo '</div>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}
}
$x = $_POST['x'];
echo getvalue($x);
?>