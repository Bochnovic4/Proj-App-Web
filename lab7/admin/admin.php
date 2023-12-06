<?php
session_start();
include('../cfg.php');
global $login, $pass;

function FormularzLogowania()
{
    $wynik = '
        <div class="logowanie">
         <h1 class="heading">Panel CMS:</h1>
          <div class="logowanie">
           <form method="post" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <table class="logowanie">
             <tr><td class="log4_t">[email]</td><td><input type="text" name="login_mail" class="logowanie" /></td></td>
             <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></td>
             <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
            </table>
           </form>
          </div>
         </div>
        ';
    return $wynik;
}

function PokazPodstrone($id)
{
    $id_clear = htmlspecialchars($id);
    $conn = OpenCon();

    $query = "SELECT * FROM page_list WHERE id ='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_array($result)) {
        if ($_SESSION['zalogowany'] == true) {

                $decodedTitle = htmlspecialchars_decode($row['page_title']);
                $decodedContent = htmlspecialchars_decode($row['page_content']);

                echo '
            <form method="post" action="" onsubmit="reloadPage()">
                <label for="title">Title: </label>
                <input type="text" name="title" value="' . htmlspecialchars($decodedTitle) . '" /><br />
                
                <label for="page_content">Page Content:</label>
                <textarea name="page_content">' . htmlspecialchars($decodedContent) . '</textarea><br />
                
                <label for="status">Aktywna:</label>
                <input type="checkbox" name="status" ' . ($row['status'] == 1 ? 'checked' : '') . ' /><br />
                
                <input type="hidden" name="edit_button_id" value="' . $row['id'] . '">
                <input type="submit" name="submit" value="Edytuj" />
            </form>
            
        ';
        }
    }

    if (isset($_POST['submit'])) {
        EdytujPodstrone($id_clear);
    }
    unset($_POST['edit_button_id']);
    CloseCon($conn);
}

function EdytujPodstrone($id)
{
    $id_clear = htmlspecialchars($id);
    $conn = OpenCon();

    if ($_SESSION['zalogowany'] == true && isset($_POST['submit'])) {
        $title_clear = htmlspecialchars($_POST['title']);
        $content_clear = $_POST['page_content'];

        $title = mysqli_real_escape_string($conn, $title_clear);
        $content = mysqli_real_escape_string($conn, $content_clear);
        $status = isset($_POST['status']) ? 1 : 0;

        $query = "UPDATE page_list SET page_title='$title', page_content='$content', status=$status WHERE id=$id_clear";
        mysqli_query($conn, $query);

        echo 'Page updated successfully.<br /><br />';
    }
    header('Location:'.$_SERVER['PHP_SELF']);
    CloseCon($conn);
}

function ListaPodstron()
{
    $conn = OpenCon();
    $query = "SELECT * FROM page_list ORDER BY id ASC LIMIT 100";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        echo 'id: ' . $row['id'] . ' page_title: ' . $row['page_title'] . '
             <form method="post" action="">
                 <input type="hidden" name="edit_button_id" value="' . $row['id'] . '">
                 <button type="submit" name="edit_button">Edytuj</button>
                 <button type="submit" name="usun_button">Usun</button>
             </form>
             <br />';

        if (isset($_POST['usun_button'])) {
            UsunPodstrone($row['id']);
        }
    }

    CloseCon($conn);
}
function UsunPodstrone($id)
{
    $id_clear = htmlspecialchars($id);
    $conn = OpenCon();
    var_dump($id_clear);
    if ($_SESSION['zalogowany'] == true && isset($_POST['usun_button'])) {
        $query = "DELETE FROM page_list WHERE id=$id_clear LIMIT 1";
        mysqli_query($conn, $query);

        echo 'Page deleted successfully.<br /><br />';
    }

    CloseCon($conn);
}

function DodajPodstroneForm()
{
    echo '
        <form method="post" action="">
            <label for="new_title">New Page Title: </label>
            <input type="text" name="new_title" /><br />
            
            <label for="new_page_content">New Page Content:</label>
            <textarea name="new_page_content"></textarea><br />
            
            <label for="new_status">Aktywna:</label>
            <input type="checkbox" name="new_status" /><br />
            
            <input type="submit" name="submit_new_page" value="Dodaj Nową Podstronę" />
        </form>
    ';
}

function DodajNowaPodstrone()
{
    $conn = OpenCon();

    if ($_SESSION['zalogowany'] == true && isset($_POST['submit_new_page'])) {
        $title_clear = htmlspecialchars($_POST['new_title']);
        $content_clear = $_POST['new_page_content'];

        $title = mysqli_real_escape_string($conn, $title_clear);
        $content = mysqli_real_escape_string($conn, $content_clear);
        $status = isset($_POST['new_status']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
        mysqli_query($conn, $query);

        echo 'New page added successfully.<br /><br />';
    }

    CloseCon($conn);
}



if (!isset($_SESSION['zalogowany'])) {
    $_SESSION['zalogowany'] = false;
}

if ($_SESSION['zalogowany'] !== true) {
    if (isset($_POST['login_mail'])) {
        if ($_POST['login_mail'] == $login && $_POST['login_pass'] == $pass) {
            $_SESSION['zalogowany'] = true;
            echo 'Logowanie powiodło się . <br /><br />';
            ListaPodstron();
        } else {
            echo 'Błąd logowania. Spróbuj ponownie. <br/>';
            echo FormularzLogowania();
        }
    } else {
        echo FormularzLogowania();
    }
} else {
    ListaPodstron();
    DodajPodstroneForm();
}

if (isset($_POST['edit_button_id'])) {
    $buttonId = $_POST['edit_button_id'];
    PokazPodstrone($buttonId);
}
if (isset($_POST['usun_button'])) {
    #UsunPodstrone($_POST['edit_button_id']);
}

DodajNowaPodstrone();
?>
<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>
