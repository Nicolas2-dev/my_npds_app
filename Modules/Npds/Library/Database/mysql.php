<?php

use Npds\Config\Config;


$sql_nbREQ = 0;

// Escape string
function SQL_escape_string($arr)
{
    global $dblink;

    if (function_exists("mysql_real_escape_string"))
        @mysql_real_escape_string($arr, $dblink);

    return ($arr);
}

// Connexion
function sql_connect()
{
    $mysql_p = Config::get('npds.mysql_p');

    if ($mysql_p or (!isset($mysql_p)))
        $dblink = @mysql_pconnect(Config::get('npds.dbhost'), Config::get('npds.dbuname'), Config::get('npds.dbpass'));
    else
        $dblink = @mysql_connect(Config::get('npds.dbhost'), Config::get('npds.dbuname'), Config::get('npds.dbpass'));

    if (!$dblink)
        return (false);
    else {
        if (!@mysql_select_db(Config::get('npds.dbname'), $dblink))
            return (false);
        else
            return ($dblink);
    }
}

// Erreur survenue
function sql_error()
{
    return @mysql_error();
}

// Exécution de requête
function sql_query($sql)
{
    global $sql_nbREQ;

    $sql_nbREQ++;

    if (!$query_id = @mysql_query(SQL_escape_string($sql)))
        return false;
    else
        return $query_id;
}

function sql_fetch_array($q_id = '')
{
    if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
    }

    return @mysql_fetch_array($q_id);
}


// Tableau Associatif du résultat
function sql_fetch_assoc($q_id = "")
{
    if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
    }

    return @mysql_fetch_assoc($q_id);
}

// Tableau Numérique du résultat
function sql_fetch_row($q_id = "")
{
    if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
    }

    return @mysql_fetch_row($q_id);
}

// Resultat sous forme d'objet
function sql_fetch_object($q_id = "")
{
    if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
    }

    return @mysql_fetch_object($q_id);
}

// Nombre de lignes d'un résultat
function sql_num_rows($q_id = "")
{
    if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
    }

    return @mysql_num_rows($q_id);
}

// Nombre de champs d'une requête
function sql_num_fields($q_id = "")
{
    if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
    }

    return @mysql_num_fields($q_id);
}

// Nombre de lignes affectées par les requêtes de type INSERT, UPDATE et DELETE
function sql_affected_rows()
{
    return @mysql_affected_rows();
}

// Le dernier identifiant généré par un champ de type AUTO_INCREMENT
function sql_last_id()
{
    return @mysql_insert_id();
}

// Lister les tables
function sql_list_tables($dbnom = "")
{
    if (empty($dbnom)) {
        $dbnom = Config::get('npds.dbname');
    }

    return @sql_query("SHOW TABLES FROM $dbnom");
}

// Controle
function sql_select_db()
{
    global $dblink;

    if (!@mysql_select_db($dblink, Config::get('npds.dbname')))
        return (false);
    else
        return (true);
}

// Libère toute la mémoire et les ressources utilisées par la requête $query_id
function sql_free_result($q_id = "")
{
    return @mysql_free_result($q_id);
}

// Ferme la connexion avec la Base de données
function sql_close($dblink)
{
    return @mysql_close($dblink);
}
