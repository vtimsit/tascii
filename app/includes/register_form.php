<?php 
function connection($account) {
    $_SESSION['user'] = $account;
    header('Location: ./');
}

function deconnection($account) {
    unset($_SESSION['user']);
    header('Location: ./');
}

if(!empty($_POST)) {
    !empty($_POST['user_name']) ? $userName = $_POST['user_name'] : $registerErrors['user_name'] = 'register__emptyError--active';
    !empty($_POST['password']) ? $password = $_POST['password'] : $registerErrors['password'] = 'password__emptyError--active';

    if(!empty($_POST['user_name']) && !empty($_POST['password'])) {

        // Préparation de la requête
        $query = $pdo->query('SELECT * FROM users');
    
        // Éxécution de la requête et récupération des données
        $users = $query->fetchAll();
        $usersName = array();
        $passwords = array();

        foreach($users as $_user) {
            $usersName[] = $_user->user_name;
        }

        // userCheck($userName, $usersName, $users, $pdo);
        function userCheck($userName, $usersName, $users, $pdo) {
            if(!empty($_POST['user_name']) && !empty($_POST['password'])) {
                // $query = $pdo->query('SELECT * FROM users WHERE user_name = " ' . $userName . ' " ');
                // $user = $query->fetch();
        
                // Values
                $data = ['user_name' => $userName];
                
                // Prepare request
                $prepare = $pdo->prepare('SELECT * FROM users WHERE user_name = :user_name');
        
                $prepare->bindValue(':user_name', $data['user_name']);
                
                // Execute request
                $exec = $prepare->execute($data);
        
                $user = $prepare->fetch();
        
                // echo '<pre>';
                // var_dump($user);
                // echo '</pre>';

                if($user) {
                    // echo 'trouvéééé';
                    passwordCheck($user, $_POST['password']);
                    
                } else {
                    echo 'pas trouvé';
                    $registerErrors['user_name'] = "register__invalidUsername--active"; // NE FONCTIONNE PAS
                }
            }
        }
        
        function passwordCheck($user, $password) {
            $userPassword = $user->password;
            if($userPassword == md5($password)) {
                $_SESSION['user'] = $user->user_name;
            } else {
                $registerErrors['password'] = "register__invalidPassword--active";
            }
        }
        // passwordCheck($userName, $usersName, $users, $password, $pdo);
        userCheck($userName, $usersName, $users, $pdo);
    }
}
