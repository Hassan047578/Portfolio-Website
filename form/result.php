<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $marks = $_POST['marks'];
    $names = $_POST['names'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        h3 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 25px;
            color: #0a121c;
        }

        h3 span {
            color: purple;
        }

        .container {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            max-width: 450px;
            margin: auto;
            padding: 30px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(128, 0, 128, 0.15);
        }

        .result-item {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(128, 0, 128, 0.25);
            padding: 12px 16px;
            border-radius: 10px;
            margin: 10px 0;
            font-weight: 600;
            color: #0a121c;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .result-item:hover {
            border-color: purple;
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 0 0 3px rgba(128, 0, 128, 0.1);
        }

        .result-item .name {
            color: #0a121c;
        }

        .result-item .mark {
            background: purple;
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 14px;
        }

        .empty {
            text-align: center;
            color: purple;
            font-weight: 600;
            font-size: 15px;
            padding: 10px;
        }

        .back-btn {
            display: block;
            width: fit-content;
            margin: 25px auto 0;
            padding: 10px 28px;
            background: purple;
            color: white;
            border: 2px solid purple;
            border-radius: 10px;
            font-weight: bold;
            font-size: 15px;
            text-decoration: none;
            transition: 0.3s;
            text-align: center;
        }

        .back-btn:hover {
            background: transparent;
            color: purple;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>View <span>Remarks</span></h3>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $found = false;
        foreach($marks as $id => $mark){
            if($mark == "") continue;
            echo "<div class='result-item'>
                    <span class='name'>{$names[$id]}</span>
                    <span class='mark'>$mark</span>
                  </div>";
            $found = true;
        }
        if(!$found){
            echo "<p class='empty'>No marks entered!</p>";
        }
    } else {
        echo "<p class='empty'>Invalid Request</p>";
    }
    ?>
    <a href="javascript:history.back()" class="back-btn">← Go Back</a>
</div>
</body>
</html>