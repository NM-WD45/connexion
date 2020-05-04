<?php
session_start();
include ('includes/header.php');
include ('includes/menu.php');

if(!isset($_SESSION['auth']))
{
    if(!empty($_POST))
    {
        include ('includes/db.php');
        $query = $db ->prepare('SELECT * FROM identifiants 
        WHERE (identifiant =:identifiant OR mail=:mail) 
        AND confirm_at IS NOT NULL');

        $identifiant = $_POST['username'];
        $mail = $_POST['mail'];
        $mdp = $_POST['password'];

        $query->bindValue(':identifiant',$identifiant,PDO::PARAM_STR);
        $query->bindValue(':mail',$mail,PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch();

        $hash = $data['mdp'];

        if(($data['mail'] == $mail || $data['identifiant'] == $identifiant) && password_verify($mdp, $hash)){
            $_SESSION['auth'] = $data;
            echo'<meta http-equiv="refresh" content="0.3;URL=mon-compte.php?id='.$data['id'].'">';
        }
        else{

            echo '<div class="alert-danger">';
            die('Identifiant ou mot de passe incorrect.
            <p class="bouton-danger">
            <a href="connexion.php">Fermer</a>
            </p>');
            echo '</div>';
        }
    }

    else
    {
        ?>

        <div class="container-center" style="height:550px;">
            
            <h1>Connexion</h1>

            <form method="POST" action="" style="text-align: center;width:50%">

                <label for="">E-mail</label><br/>
                <input type="text" name="mail" ><br/><br/>
                
                <label for="">Identifiant</label><br/>
                <input type="text" name="username" ><br/><br/>

                <label for="">Mot de passe</label><br/>
                <input type="password" name="password" ><br/><br/>

                <button type="submit">Se connecter</button>
            </form>
            <p style="text-align: center"><a href="inscription.php">Pas encore inscrit ? </a></p>
        </div>
        <?php
    }
}
else
{
    echo
    '<p>Vous êtes déjà connecté(e) ' .
    $_SESSION['auth']['identifiant'] .'</p>';
    //var_dump($_SESSION);
    echo'<br/><p><a href="mon-compte.php?id='.$id.'" title="Mon compte">Mon compte</a></p>';
}

include ('includes/footer.php');
?>
