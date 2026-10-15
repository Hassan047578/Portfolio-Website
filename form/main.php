<?php
include 'config.php';
$result = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remarks Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
            color: #0a121c;
            min-height: 100vh;
            padding: 40px 20px;
        }

        h2 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 30px;
            color: #0a121c;
        }

        h2 span {
            color: purple;
        }

        form {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            padding: 30px;
            max-width: 450px;
            margin: auto;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(128, 0, 128, 0.15);
        }

        div {
            margin-bottom: 16px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #0a121c;
        }

        input[type="text"] {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid rgba(128, 0, 128, 0.4);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.6);
            color: #0a121c;
            font-size: 14px;
            transition: 0.3s;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: purple;
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 0 0 3px rgba(128, 0, 128, 0.15);
        }

        button {
            width: 100%;
            padding: 11px;
            background: purple;
            color: white;
            border: 2px solid purple;
            border-radius: 10px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 8px;
        }

        button:hover {
            background: transparent;
            color: purple;
        }
    </style>
</head>
<body>

<h2>Enter <span>Remarks</span></h2>

<form method="post" action="/form/result.php">
    <?php while($row = $result->fetch_assoc()) { ?>
        <div>
            <label><?php echo $row['name']; ?></label>
            <input type="text" name="marks[<?php echo $row['id']; ?>]" placeholder="Enter marks">
            <input type="hidden" name="names[<?php echo $row['id']; ?>]" value="<?php echo $row['name']; ?>">
        </div>
    <?php } ?>
    <br>
    <button type="submit">Submit</button>
</form>

</body>
</html>