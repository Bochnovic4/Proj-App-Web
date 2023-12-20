<?php
session_start();
include('../cfg.php');
global $login, $pass;

// -----------------------------------------
// Function: Get Refreshed URL
// -----------------------------------------
function getRefreshedUrl() {
    $currentUrl = $_SERVER['REQUEST_URI'];
    $randomParam = time();
    return $currentUrl . '?refresh=' . $randomParam;
}

// -----------------------------------------
// Function: Display Login Form
// -----------------------------------------
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

// -----------------------------------------
// Function: Display Page for Editing
// -----------------------------------------
function PokazPodstrone($id)
{
    $id_clear = htmlspecialchars($id);
    $conn = OpenCon();

    if (isset($_POST['submit'])) {
        EdytujPodstrone($id_clear);
    }

    $query = "SELECT * FROM page_list WHERE id ='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_array($result)) {
        if ($_SESSION['zalogowany'] == true) {
            $decodedTitle = htmlspecialchars_decode($row['page_title']);
            $decodedContent = htmlspecialchars_decode($row['page_content']);

            echo '
                <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
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

    CloseCon($conn);
}

// -----------------------------------------
// Function: Edit Page Content
// -----------------------------------------
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
        header('Location: ' . getRefreshedUrl());
        exit();
    }
    CloseCon($conn);
}

// -----------------------------------------
// Function: Display List of Pages
// -----------------------------------------
function ListaPodstron()
{
    $conn = OpenCon();
    $query = "SELECT * FROM page_list ORDER BY id ASC LIMIT 100";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        echo 'id: ' . $row['id'] . ' page_title: ' . $row['page_title'] . '
             <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                 <input type="hidden" name="edit_button_id" value="' . $row['id'] . '">
                 <button type="submit" name="edit_button">Edytuj</button>
                 <input type="hidden" name="usun_button_id" value="' . $row['id'] . '">
                 <button type="submit" name="usun_button' . $row['id'] . '">Usun</button>
             </form>
             <br />';

        if (isset($_POST['usun_button' . $row['id']])) {
            UsunPodstrone($row['id']);
            header('Location: ' . getRefreshedUrl());
            exit();
        }
    }

    CloseCon($conn);
}

// -----------------------------------------
// Function: Delete Page
// -----------------------------------------
function UsunPodstrone($id) {
    if ($_SESSION['zalogowany'] == true && isset($_POST['usun_button' . $id])) {
        $id_clear = htmlspecialchars($id);
        $conn = OpenCon();

        $query = "DELETE FROM page_list WHERE id=$id_clear LIMIT 1";
        mysqli_query($conn, $query);

        header('Location: ' . getRefreshedUrl());
        exit();
    }
}

// -----------------------------------------
// Function: Display Form for Adding Page
// -----------------------------------------
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

// -----------------------------------------
// Function: Add New Page
// -----------------------------------------
function DodajNowaPodstrone() {
    $conn = OpenCon();

    if ($_SESSION['zalogowany'] == true && isset($_POST['submit_new_page'])) {
        $title_clear = htmlspecialchars($_POST['new_title']);
        $content_clear = $_POST['new_page_content'];

        $title = mysqli_real_escape_string($conn, $title_clear);
        $content = mysqli_real_escape_string($conn, $content_clear);
        $status = isset($_POST['new_status']) ? 1 : 0;

        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
        mysqli_query($conn, $query);

        header('Location: ' . getRefreshedUrl());
        exit();
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
            DodajPodstroneForm();
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

if (isset($_POST['submit_new_page'])) {
    DodajNowaPodstrone();
}

?>
<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>
