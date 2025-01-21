<?php  
// Activer l'affichage des erreurs uniquement en développement  
ini_set('display_errors', 1);  
ini_set('display_startup_errors', 1);  
error_reporting(E_ALL);  

// Connexion à SphinxQL  
$mysqli = new mysqli("127.0.0.1", "root", "", "test", 9306);  

// Vérifiez la connexion  
if ($mysqli->connect_error) {  
    die(json_encode(['error' => "Erreur de connexion: " . $mysqli->connect_error]));  
}  

// Assurez-vous que la connexion utilise UTF-8  
$mysqli->set_charset("utf8");  

// Fonction de recherche  
function search($query, $index) {  
    global $mysqli;  
    $query = $mysqli->real_escape_string($query);  
    $sql = "SELECT * FROM `$index` WHERE MATCH('$query') LIMIT 20";   

    // Débogage : afficher la requête SQL  
    echo "Executing SQL for index $index: $sql<br>";   

    // Exécute la requête et récupère les résultats  
    $results = $mysqli->query($sql);  
    if (!$results) {  
        return ['error' => 'Erreur lors de l\'exécution de la requête : ' . htmlspecialchars($mysqli->error)];  
    }  
    return $results;  
}  

// Liste des index à interroger  
$indexes = ['index_livres', 'index_auteurs_stemmed', 'test1'];  

// Initialisation d'un tableau pour stocker les résultats  
$allResults = [];   

// Utilisation  
if (isset($_GET['q']) && !empty($_GET['q'])) {   
    $query = $_GET['q'];  
    $query = htmlspecialchars($query);  

    foreach ($indexes as $index) {  
        $results = search($query, $index);  

        if (is_array($results) && isset($results['error'])) {  
            // Si une erreur est renvoyée, l'ajouter à allResults  
            $allResults[] = ['index' => $index, 'message' => $results['error']];  
            continue; // Ne pas chercher dans l'index suivant  
        }  

        if ($results->num_rows > 0) {  
            // Initialiser un tableau pour stocker les résultats de chaque index  
            $indexResults = ['index' => $index, 'data' => []];  

            while ($item = $results->fetch_assoc()) {  
                // Construction conditionnelle selon l'index  
                $resultItem = [];  
                if ($index === 'index_livres') {  
                    $resultItem = [  
                        'title' => $item['title'] ?? 'Titre non disponible',  
                        'description' => $item['description'] ?? 'Description non disponible'  
                    ];  
                } elseif ($index === 'index_auteurs_stemmed') {  
                    $resultItem = [  
                        'title' => $item['title'] ?? 'Nom non disponible',   
                        'bio' => $item['bio'] ?? 'Biographie non disponible'  
                    ];  
                } elseif ($index === 'test1') {  
                    $resultItem = [  
                        'title' => $item['title'] ?? 'Titre non disponible',  
                        'group_id' => $item['group_id'] ?? 'Groupe non disponible'  
                    ];  
                }  
                $indexResults['data'][] = $resultItem;  // Collecte des résultats par index  
            }   
            // Ajoute les résultats de l'index à la liste globale   
            $allResults[] = $indexResults;   
        } else {   
            // Ajoute un message indiquant qu'aucun résultat n'a été trouvé pour cet index   
            $allResults[] = ['index' => $index, 'message' => "Aucun résultat trouvé dans l'index $index."];  
        }   
    }   
} else {   
    $allResults[] = ["message" => "Veuillez entrer une requête de recherche."];  
}  

// Fermer la connexion   
$mysqli->close();  

// Débogage : afficher les résultats avant de les encoder  
echo "<pre>";  
print_r($allResults);  
echo "</pre>";  

// Retourner les résultats au format JSON   
header('Content-Type: application/json');   

// Encode les résultats et les renvoie  
echo json_encode($allResults);  
exit(); // Assurez-vous que rien d'autre n'est envoyé après cela  
?>
