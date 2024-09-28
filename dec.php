<?php

$host = 'localhost';
$db = 'dec-datbase'; 
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4"; 
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options); 
    $stm=$pdo->query("SELECT letter, random_value FROM letters");
    $substitution =[];
    while($row=$stm->fetch()){
        $substitution[$row['letter']] = $row['random_value'];
        $reverseSubstitution[$row['random_value']] = $row['letter'];
    }
} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function encryptText($text,$substitution){
    $encrypted ='';
    $text = str_replace(' ', '', $text);
    $text = strtoupper($text);
    for ($i = 0; $i < strlen($text); $i++){
        $char=$text[$i];
        if(isset($substitution[$char])){
            $encrypted .= $substitution[$char];
        }else{
            $encrypted .= $char;
        }
    }  
    return $encrypted;
}
function decryptText($encryptedText,$reverseSubstitution){
    $decrypted ='';
    $temp='';
    for($i=0; $i<strlen($encryptedText);$i++){
        $temp .= $encryptedText[$i];
        if(isset($reverseSubstitution[$temp])){
            $decrypted .= $reverseSubstitution[$temp];
            $temp ='';
        }
    }
    return $decrypted;
}

$inputText = "ahmed wold";
$encryptedText = encryptText($inputText, $substitution);
echo "Encrypted Text: " . $encryptedText;

$decryptedText = decryptText($encryptedText, $reverseSubstitution);
echo "Decrypted Text: " . $decryptedText;