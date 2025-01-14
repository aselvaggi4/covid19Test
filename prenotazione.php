<?php     
include("sessioni.php"); 
require_once('model/user_db.php');
require_once('controller/gestione_utente.php');
require_once("controller/gestione_tampone.php"); 
require_once("view/vista-dashboard-prenotazione.php");

if(!isset($_SESSION['valid'])) {
    header("Location: index.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php 
   
   include("head-include.php"); 
   
    $page_id = 0;

   ?>

</head>


<body style="background: linear-gradient(#141e30, #243b55);">

    <?php include ("view/navbar.php"); 
   
    $tampone = new TamponeController();
    $infoTampone = $tampone->datiTampone($_REQUEST['pren'], $_SESSION['id']);
    
    if((!is_object($infoTampone)) || $infoTampone->stato == "completato") {
        header('Location: dashboard.php');
            die();
    }
   ?>
    <div class="container" style="max-width:998px; margin-top:3rem;">
        <div class="row">
            <?php if($_SESSION['tipo_utente'] != 4) { ?>
            <div class="card" style="color:black; text-align:center;">
                <?php if(!isset($_POST['annulla_prenotazione'])) {?>
                <div class="card-header">
                    Prenotazione presso <?php echo $infoTampone->nome; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">La tua prenotazione del
                        <?php echo $infoTampone->orario. " presso " . $infoTampone->nome. " in " . $infoTampone->via; ?>
                    </h5>
                    <p class="card-text">
                    <div class="col-md-6" style="margin:auto;">
                            <?php
                                $vistaDashboard = new MostraPrenotazione($infoTampone, $_SESSION['tipo_utente']);
                                
                                 if($infoTampone->stato != "completato") { ?>

                            <?php }
                            } else { ?>
                        <div class="card-header">
                                Prenotazione annullata con successo
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">La prenotazione è stata annullata!</h5>
                            <p class="card-text">
                            <div class="col-md-6" style="margin:auto;">
                                        <?php 
                            }
                            ?>
                            </div>
                            </p>
                        </div>
                    </div>
                    <?php }  else if($_SESSION['tipo_utente'] == 4){ ?>
                        <div class="card" style="color:black; text-align:center;">
               
                <div class="card-header">
                    Prenotazione di <?php echo $infoTampone->utente . ' ' . $infoTampone->cognome; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">La prenotazione del
                        <?php echo $infoTampone->orario; ?>
                    </h5>
                    <p class="card-text">
                    <div class="col-md-6" style="margin:auto;">
                            <?php
                                $vistaDashboard = new MostraPrenotazione($infoTampone, $_SESSION['tipo_utente']);
                                
                                if($infoTampone->stato != "completato") { ?>

                            <?php }
                             ?>
                 
                            </div>
                            </p>
                        </div>
                    </div>
                
            </div>
        </div>
<?php }

$tampone = new TamponeController();

if(isset($_POST['file_inserito'])) {

    $tampone->aggiungiAnamnesi($_FILES['anamnesi'], $_REQUEST['pren']);
    // $tampone->eliminaPrenotazione($_REQUEST['pren']);
    echo "<meta http-equiv='refresh' content='0'>";
}

if(isset($_POST['annulla_prenotazione'])) {
    $tampone->annullaTampone($infoTampone->id, $infoTampone->prenotazione);
}

if(isset($_POST['esito_inserito'])) {
    $tampone->inserisciEsito($infoTampone, $_POST['esito_inserito']);
    echo '<script>alert("Esito inserito!");
    window.location.replace("dashboard.php");
    </script>';
}

include("footer-include.php");
?>

</body>

</html>