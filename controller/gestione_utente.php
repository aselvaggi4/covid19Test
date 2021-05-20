<?php 

require_once('model/user_db.php');

class UtenteController {

    function loginUtente($usr, $psw) {
        
        $utente = new Utente();

        $risultato = $utente->getUtente($usr, $psw);
        
        if($risultato !== false) {
            $utente->validaSessione(
                $risultato->tipo_utente, 
                $risultato->id, 
                $risultato->email, 
                $risultato->nome
            );
        } else {
            throw new Exception("Dati errati!");
        }
    }

    function registrazioneUtente($tipo_utente, $nome, $cognome, $citta, $provincia, $cap, $indirizzo, $CF, $tel, $user_email, $user_psw) {
        
        $utente = new Utente();

        $check = $utente->setUtente($tipo_utente, $nome, $cognome, $citta, $provincia, $cap, $indirizzo, $CF, $tel, $user_email, $user_psw);

        if ($check == true) {
            $result = $utente->getUtente($user_email, $user_psw);
            $utente->validaSessione($tipo_utente, $result->id, $user_email, $nome);
            return true;

        } else {

            throw new Exception("Email esistente!");

        }
    }

}



?>