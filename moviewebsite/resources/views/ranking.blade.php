<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Phim cũ</title>
    <link rel="stylesheet" href="{{ asset('css/ranking.css') }}" />
</head>
<body>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br />

    <div class="overflow-container">
        <h3 style="color: white; margin-left: 20px; margin-top: 20px">Top 10 phim hot trong ngày</h3>
        <div class="number-row">
            @php
                $upvoteMinusOne = DB::table('movie')->update(['point' => DB::raw('upvote - 1')]);
                $updateRank = DB::table('movie')
                    ->join(DB::raw('(SELECT m.id, COUNT(DISTINCT CASE WHEN s.point > m.point THEN s.point ELSE NULL END) + 1 AS new_rank
                                    FROM movie AS m
                                    LEFT JOIN movie AS s ON m.point < s.point
                                    GROUP BY m.id) AS subquery'), 'movie.id', '=', 'subquery.id')
                    ->update(['movie.rank' => DB::raw('subquery.new_rank')]);

                for ($i = 1; $i <= 10; $i++) {
                    $links = DB::table('movie_link')
                        ->join('movie', 'movie_link.id', '=', 'movie.link_id')
                        ->where('movie.rank', $i)
                        ->pluck('rank_link');

                    echo '<div class="number-cell">';
                    echo '<span>' . $i . '</span>';
                    echo '<div class="empty-cell" style="position:relative;">';

                    foreach ($links as $link) {
                        echo '<img src="' . $link . '" alt=" ">'; 
                    }

                    echo '</div>';
                    echo '</div>';
                }
            @endphp
        </div>
    </div>

    <div>
        <button class="scroll-right">▶</button>
    </div>

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
</body>
</html>
