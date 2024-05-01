<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Phim cũ</title>
    <link rel="stylesheet" href="ranking.css" />

</head>
<body>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br />
    <?php
        include 'connect.php';
try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<div class="overflow-container">';
    echo'<h3 style="color: white; margin-left: 20px; margin-top: 20px">
    Top 10 phim hot trong ngày
    </h3>';
    echo '<div class="number-row">';
    for ($i = 1; $i <= 10; $i++) {
        echo '<div class="number-cell">';
        echo '<span>' . $i . '</span>';
        echo '<div class="empty-cell" style="position:relative;">';

        $sql1= "UPDATE `movie` SET `point` = (`upvote` - 1) ";
        $conn->query($sql1);
        $sql2= "UPDATE `movie`
        JOIN (
            SELECT m.`id`, COUNT(DISTINCT CASE WHEN s.`point` > m.`point` THEN s.`point` ELSE NULL END) + 1 AS new_rank
            FROM `movie` AS m
            LEFT JOIN `movie` AS s ON m.`point` < s.`point`
            GROUP BY m.`id`
        ) AS subquery ON `movie`.`id` = subquery.`id`
        SET `movie`.`rank` = subquery.`new_rank`;
        ";
        $conn->query($sql2);
        $sql = "SELECT `rank_link` FROM `movie_link` INNER JOIN `movie` ON `movie_link`.`id`=`movie`.`link_id` WHERE`movie`.`rank`=".$i;
        
        foreach ($conn->query($sql) as $row) {
          
            echo '<img src="'.$row["rank_link"].'" alt=" ">'; 
        }

        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const numberRow = document.querySelector(".number-row");
            const scrollRightButton = document.querySelector(".scroll-right");
            const numberCellWidth = document.querySelector(".number-cell").offsetWidth;
            const arrowKeys = [37, 38, 39, 40];
            const emptycell = document.querySelectorAll(".empty-cell");
            const popup = document.querySelectorAll(".pop-up");
            const scrollright = document.getElementById('scroll-right');


            scrollRightButton.addEventListener("click", function () {
                const currentLeft = parseFloat(getComputedStyle(numberRow).left);
                const newLeft = currentLeft - numberCellWidth * 5;
                numberRow.style.left = newLeft + "px";
                if (newLeft <= -numberCellWidth * 10) {
                    numberRow.style.left = "0";
                }
            });

            document.addEventListener("keydown", function (event) {
                if (arrowKeys.includes(event.keyCode)) {
                    event.preventDefault();
                    if (event.keyCode === 37) {
                        currentPosition--;
                    } else if (event.keyCode === 39) {
                        currentPosition++;
                    }
                    updatePosition();
                }
            });
        });
    </script>

    <div>
        <button class="scroll-right">▶</button>
    </div>
</body>
</html>