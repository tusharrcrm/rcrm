<?php include "db.in.php"; ?>
<html>

<head>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: left;
      justify-content: top;
      height: 100vh;
    }

    h1 {
      color: #4caf50;
    }

    form {
      background: linear-gradient(to right, #3498db, #2980b9);
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      padding: 20px;
      margin-bottom: 20px;
      width: 50%;
      max-width: 600px;
      box-sizing: border-box;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: linear-gradient(to right, #3498db, #2980b9);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      border-radius: 8px;
      overflow: hidden;
    }

    table,
    th,
    td {
      border: 1px solid #4caf50;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      color: white;
    }

    th {
      background-color: #4caf50;
    }

    input[type="text"] {
      padding: 10px;
      width: calc(100% - 20px);
      margin-bottom: 10px;
      border: 1px solid #3498db;
      border-radius: 4px;
      box-sizing: border-box;
      color: #333;
    }

    input[type="submit"] {
      padding: 12px;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    tr:hover {
      background-color: #4caf50;
      color: #fff;
    }
  </style>
</head>

<body>
  <h1>Student Management System</h1>
  <?php
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  VerifyEmployeesTable($connection, DB_DATABASE);

  $employee_name = htmlentities($_POST['NAME']);
  $employee_address = htmlentities($_POST['ADDRESS']);

  if (strlen($employee_name) || strlen($employee_address)) {
    AddEmployee($connection, $employee_name, $employee_address);
  }
  ?>

  <!-- Input form -->
  <form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
    <table>
      <tr>
        <td>
          <input type="text" name="NAME" placeholder="Enter Name" maxlength="45" required />
        </td>
        <td>
          <input type="text" name="ADDRESS" placeholder="Enter Address" maxlength="90" required />
        </td>
        <td>
          <input type="submit" value="Add Data" />
        </td>
      </tr>
    </table>
  </form>

  <!-- Display table data. -->
  <table>
    <tr>
        <th>ID<th>
      <th>NAME</th>
      <th>ADDRESS</th>
    </tr>

    <?php
    $result = mysqli_query($connection, "SELECT * FROM EMPLOYEES");

    while ($query_data = mysqli_fetch_row($result)) {
      echo "<tr>";
      echo "<td>", $query_data[0], "</td>",
      "<td>", $query_data[1], "</td>",
      "<td>", $query_data[2], "</td>";
      echo "</tr>";
    }
    ?>
  </table>

  <!-- Clean up. -->
  <?php
  mysqli_free_result($result);
  mysqli_close($connection);
  ?>
</body>

</html>

<?php

/* Add an employee to the table. */
function AddEmployee($connection, $name, $address)
{
  $n = mysqli_real_escape_string($connection, $name);
  $a = mysqli_real_escape_string($connection, $address);

  $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS) VALUES ('$n', '$a');";

  if (!mysqli_query($connection, $query)) echo ("<p>Error adding employee data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyEmployeesTable($connection, $dbName)
{
  if (!TableExists("EMPLOYEES", $connection, $dbName)) {
    $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90)
       )";

    if (!mysqli_query($connection, $query)) echo ("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName)
{
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
    "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if (mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
