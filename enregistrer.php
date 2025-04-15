<?php
$host = "localhost";
$dbname = "gestion";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $nom = $_POST["nomuser"];
    $prenom = $_POST["prenomuser"];
    $email = $_POST["emailuser"];
    $age = $_POST["age"];

    if (!empty($id)) {
        $stmt = $pdo->prepare("UPDATE users SET nomuser=?, prenomuser=?, emailuser=?, age=? WHERE id=?");
        $stmt->execute([$nom, $prenom, $email, $age, $id]);
        echo "updated";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (nomuser, prenomuser, emailuser, age) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $age]);
        echo "added";
    }
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
    echo "deleted";
    exit;
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "<tr>
            <td>{$user['id']}</td>
            <td>{$user['nomuser']}</td>
            <td>{$user['prenomuser']}</td>
            <td>{$user['emailuser']}</td>
            <td>{$user['age']}</td>
            <td>
                <a href='#' onclick='editStudent({$user['id']})'>Modifier</a>
                <a href='#' class='delete' onclick='deleteStudent({$user['id']})'>Supprimer</a>
            </td>
        </tr>";
}