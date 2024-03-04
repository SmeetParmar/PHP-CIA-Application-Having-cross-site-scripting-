<!doctype html>
<html>
    <head>
        <title>CIA App</title>
        <style>
            .record {
                border:1px solid black;
                margin:16px;
                padding-left:8px;
                padding-right:8px;
                border-radius:8px;
            }

            /* css to hide instructions class */
            .instructions{
                display:none;
            }

        </style>
    </head>
    <body>
        <p>
            <div class="instructions">
                Welcome CIA Recruit! This is your first assignment. You will
                discover the instructions as you go along.<br><br>
                
                There is a table in the database called <b>Secret</b> with the columns 
                <b>SecretId,Description,Code</b>, and <b>SecretLevelId</b>. Use the PDO API to retrieve the row data, then follow the rest of the instructions that get displayed. </p>
            </div>
        <?php
            ini_set('display_errors',1);
            require_once("db_conn.php");
            
            //sanitizing variable using htmlspecialchars function, it will make tags like <b> and <br> intact...
            $searchTerm=htmlspecialchars($_POST["searchterm"]);

            //function to validate and clean variable...
            function prepare_string($pdo,$searchTerm) {
                $string_trimmed = trim($searchTerm);
                $searchTerm = $pdo->quote($string_trimmed);
                return $searchTerm;
            }
        ?>
        <form method="POST">
            <b>CIA Database:</b> 

            <!-- it will make textbox remember that what was value entered previously...  -->
            <input name="searchterm" value="<?php if(isset($_POST['searchterm'])) { echo htmlspecialchars($_POST['searchterm']); } else { echo ''; } ?>" placeholder="Filter Secrets - Eg. INSTRUCTIONS:" size="50">

            <input type="submit" value="Go">
            <br>
            <br>
        </form>
        <script>
            /* DO NOT DELETE!!! */
            function hacked()
            {
                let e=document.createElement("div");
                e.style.color="white";
                e.style.position="fixed";
                e.style.bottom="8px";
                e.style.backgroundColor="red";
                e.style.border="1px solid black";
                e.style.borderRadius="6px";
                e.style.padding="3px";
                e.innerHTML="CROSS SITE SCRIPTING! One of the records has a script tag!  DO NOT DELETE IT!";
                document.body.append(e);
            }
        </script>
        <?php
            
            //calling the function to clean variable...
            prepare_string($pdo,$searchTerm);

            $searchTerm="%$searchTerm%";
            if($searchTerm=="%%") $searchTerm=="%";            
            
            //TODO: Use SQL to get data from database here.
            //Query where it will search from description where it contains word like variable search...
            $stmt=$pdo->prepare("SELECT * FROM secret where description like :searchTerm");

            //binding :searchTerm parameter with query...
            $stmt->bindParam(":searchTerm", $searchTerm, PDO::PARAM_STR);

            //executing the query...
            $stmt->execute();
            
            
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                //if description will contain "Mark" or "Marks" word followed by number it will use color as bisque, if not it will use white color...
                if(preg_match('/\b\d+\sMarks?\b/', $row['Description']))
                {
                    $recordColor = 'bisque';
                }
                else
                {
                    $recordColor = 'white';
                }
                
                echo "<div class='record' style='background-color:$recordColor'>";
                echo "    <p>".$row['Description']."</p>";
                echo "    <p>".$row['Code']."</p>";
                echo "</div>";
            }
            
        ?>
        
    </body>
</html>





















