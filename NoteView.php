<?php
class NoteView
{
    protected Config $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function printHeader():void
    {
        ?>
        <!DOCTYPE html>
        <html lang="de">
            <head>
                <title>my-note-oop</title>
                <meta name="viewport" content="width=device-width">
                <link rel="icon" href="img/icon.png">
                <link rel="stylesheet" href="css/style.css">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
                      rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
                      crossorigin="anonymous">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                        crossorigin="anonymous"></script>
                <script src="https://kit.fontawesome.com/66699a9cd6.js" crossorigin="anonymous"></script>
            </head>
            <body class="bg-dark text-white">
                <header class="container-fluid bg-primary">
                    <div class="row d-flex justify-content-center own-bg-image">
                        <a href="index.php" aria-label="return to start site"
                           class="fs-1 fw-bold text-decoration-none text-white col-12 d-flex justify-content-center text-uppercase mb-3 mt-5">
                            my note
                        </a>
                        <a href="index.php?page=add" class="col-1 text-decoration-none mb-5" aria-label="add a note">
                            <i class="d-flex justify-content-center fa-regular fa-square-plus fs-1 text-white"></i>
                        </a>
                    </div>
                </header>
        <?
    }

    public function printErrorMessage($error):void
    {
        ?>
        <div class="modal fade show" id="errorPopup" tabindex="-1" aria-labelledby="errorPopup" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content bg-danger">
                    <div class="modal-header">
                        <h1 class="modal-title fs-2" id="errorPopup">ERROR!</h1>
                        <a href="index.php" class="btn-close own-btn-white" aria-label="return back to start site"></a>
                    </div>
                    <div class="modal-body">
                        <p class="fs-4 text-center">Problem appeared by MY NOTE.<br>reason: <b><?=$error?></b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        <?
    }

    public function printNoNoteFound($searched):void
    {
        ?>
            <div class="container mt-5">
                <div class="row d-flex justify-content-center">
                    <div class="col-8 bg-primary border border-3 border-white rounded-3 text-white">
                        <h1>No Note!</h1>
                        <p class="fs-4">
                            No matching note was found for your search result: <b>"<?=$searched?>"</b><br>
                            Please search again or go back to the main page.
                        </p>
                        <div class="own-col-1 p-0 mb-3">
                            <a href="index.php" aria-label="return to start"
                               class="text-decoration-none d-flex justify-content-start m-auto">
                                <i class="fa-solid fa-backward fs-3 align-middle text-white"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?
    }

    public function printNote(Note $noteItem): void
    {
        ?>
        <section class="container own-mb-6">
            <div class="row text-white mt-4 mb-4 border border-3 rounded-3">
                <a href="index.php?page=edit&noteId=<?=$noteItem->getId()?>" aria-label="go to edit mode"
                   class="container-fluid text-white text-decoration-none">
                    <div class="row">
                        <span class="col-12 text-center <?=$this->config->getBgColors()[(int)$noteItem->getBg()]['class']?> fs-2 pt-2 pb-2 rounded-bottom">
                            <?=$noteItem->getTitle()?>
                        </span>
                        <span class="col-12 mt-1 text-center"><?=$noteItem->getDate()?></span>
                        <p class="col-12 fs-5 fs-4"><?=nl2br($noteItem->getContent())?></p>
                    </div>
                </a>
                <div class="container-fluid">
                    <div class="row justify-content-between mb-3 ps-3 pe-3">
                        <div class="own-col-1 p-0">
                            <a href="index.php" aria-label="return to start"
                               class="text-decoration-none d-flex justify-content-start m-auto">
                                <i class="fa-solid fa-backward fs-3 align-middle text-primary"></i>
                            </a>
                        </div>
                        <div class="own-col-1 p-0">
                            <a href="index.php?page=delete&noteId=<?=$noteItem->getId()?>" aria-label="delete your note"
                               class="text-decoration-none d-flex justify-content-center m-auto text-primary">
                                <i class="fa-regular fa-trash-can fs-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?
    }

    public function printSearchBar():void
    {
        ?>
        <section class="own-mb-6">
            <div class="container mt-3 p-0">
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6 col-lg-3">
                        <form action="index.php" method="GET" class="input-group m-0">
                            <label class="sr-only" for="searchbar" aria-label="enter a search term"></label>
                            <input type="text"
                                   id="searchbar"
                                   name="searched"
                                   maxlength="25"
                                   class="form-control bg-primary-subtle text-white fs-5 border border-3 rounded"
                                   placeholder="Searchbar"
                                   autocomplete="off">
                            <button class="btn bg-primary text-white" type="submit" aria-label="enter searching for specific note">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>
        <?
    }

    public function printSortDropdown(int $curSort, array $sortings, $isPage, $isSearched):void
    {
        ?>
                <div class="btn-group col-md-4 col-lg-3 mt-4 mt-md-0 ">
                    <button type="button" class="btn bg-primary text-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        sorted - <?=$sortings[$curSort]['label']?>
                    </button>
                    <ul class="dropdown-menu p-0">
                        <? foreach ($sortings AS $key => $sortingItem) {
                            echo '<li><a class="dropdown-item';
                            if ($curSort == $key) {
                                echo ' active';
                            }
                            echo'" href="?sort='.$key.$isPage.$isSearched.'">'.$sortingItem['label'].'</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <?
    }

    public function printNoteList($allNotes):void
    {
        ?>
        <ul class="container list-page-height">
        <?
        foreach ($allNotes as $noteItem) {
        /** @var Note $noteItem */

            if ($noteItem->getPinned() === 1) {
                $pinnedTrue = "<a href='index.php?noteId=".$noteItem->getId()."&pinned=0' class='text-end' aria-label='remove pinned state'><i class='fa-solid fa-thumbtack text-white pizza-rotation'></i></a>";
                $pinnedFalse = NULL;
            } else {
                $pinnedTrue = "<div></div>";
                $pinnedFalse = "<div class='d-flex justify-content-end'><a href='index.php?noteId=".$noteItem->getId()."&pinned=1' class='w-auto mb-3 p-0 text-decoration-none text-end' aria-label='pin your message'><i class='fa-solid fa-thumbtack fs-2 text-white'></i></a></div>";
            }
        ?>
        <li class="row text-white mt-4 mb-4 border border-3 rounded-3">
            <div
                class="btn-primary pt-2 pb-2 d-flex justify-content-between header-hover <?=$this->config->getBgColors()[$noteItem->getBg()]['class']?> rounded-bottom fs-4 fs-sm-2 text-white"
                tabindex="0"
                data-bs-toggle="collapse"
                data-bs-target="#collapse<?=$noteItem->getId()?>"
                aria-expanded="false"
                aria-controls="collapse<?=$noteItem->getId()?>"
                aria-label="open your note for the content"
            >
                <?=$pinnedTrue?>
                <?=$noteItem->getTitle()?>
                <i class="fa-solid fa-caret-down"></i>
            </div>
            <div class="collapse" id="collapse<?=$noteItem->getId()?>">
                <div class="row">
                    <a href="index.php?page=detail&noteId=<?=$noteItem->getId()?>" aria-label="go to detail site"
                       class="text-decoration-none text-white p-0">
                        <div class="card card-body bg-dark border border-0 pt-0 pb-0">
                            <div class="row">
                                <span class="col-12 text-center mt-1"><?=$noteItem->getDate()?></span>
                                <p class="col-12 fs-4"><?=$noteItem->getContent()?></p>
                            </div>
                        </div>
                    </a>
                    <?=$pinnedFalse?>
                </div>
            </div>
        </li>
        <?
    }
        ?>
        </ul>
        <?
    }

    public function printPagination($paginationLogic, $isSearched, $isSorted, $maxPages, $filteredArray):void
    {
        if ($maxPages > 1) {
            if ($paginationLogic['returnButton'] === 1) {
                $paginationLogic['returnButton'] = '<li class="col-2 col-sm-1 bg-primary border border-3 rounded-3 ms-1 me-1"><a href="?page=1' . $isSearched . $isSorted . '" class="p-1 text-center text-decoration-none text-white d-block w-100" aria-label="go to first site"><i class="fa-solid fa-angles-left"></i></a></li>';
            }

            if ($paginationLogic['forwardButton'] === 1) {
                $paginationLogic['forwardButton'] = '<li class="col-2 col-sm-1 bg-primary border border-3 rounded-3 ms-1 me-1"><a href="?page=' . $maxPages . $isSearched . $isSorted . '" class="p-1 text-center text-decoration-none text-white d-block w-100" aria-label="go to last site"><i class="fa-solid fa-angles-right"></i></a></li>';
            }
            ?>
            <div class="container mt-3 own-mb-6">
                <ul class="d-flex mb-4 justify-content-center list-unstyled">
                    <?
                    echo $paginationLogic['returnButton'];
                    for ($pageNumber = $paginationLogic['startPoint']; $pageNumber<=$paginationLogic['endPoint']; $pageNumber++) {
                        echo '<li class="col-2 col-sm-1 border border-3 rounded-3 ms-1 me-1 ';
                        if ($pageNumber == $filteredArray['default']) {
                            echo 'bg-primary';
                        }
                        echo '"><a href="?page='.$pageNumber.$isSearched.$isSorted.'" class=" text-center text-decoration-none text-white fs-5 d-block w-100" aria-label="go to site '.$pageNumber.'">'.$pageNumber.'</a></li>';
                    }
                    echo $paginationLogic['forwardButton'];
                    ?>
                </ul>
            </div>
        </section>
            <?
        }
    }

    public function printEditNote($noteItem):void
    {
        $date = $noteItem->getDate();
        if ($date == '01:00 - 01.01.1970') {
            $date = date('H:i - d.m.Y');
        }
        ?>
        <section class="container">
            <form action="index.php" method="POST" class="row text-white mt-4 mb-4 border border-3 rounded-3">
                <? if ($noteItem->getId() > 0) {
                    echo '<input type="hidden" id="noteId" name="noteId" value="'.$noteItem->getId().'">';
                }
                ?>
                <label for="title" aria-label="enter a title for your note in this field"></label>
                <input
                        required
                        type="text"
                        id="title"
                        name="title"
                        minlength="1"
                        maxlength="25"
                        placeholder="enter your title"
                        autocomplete="off"
                        class="col-12 own-placeholder-color text-white fs-2 text-center pt-2 pb-2
                                <?=$this->config->getBgColors()[$noteItem->getBg()]['class']?> rounded-bottom"
                        value="<?=$noteItem->getTitle()?>"
                >
                <span class="col-12 mt-1 text-center"><?=$date?></span>
                <label for="content" aria-label="enter content for your note in this field"></label>
                <textarea
                        required
                        id="content"
                        name="content"
                        minlength="1"
                        maxlength="3000"
                        placeholder="Enter your content"
                        class="col-12 text-white fs-5 fs-sm-4 add-page-height bg-light"><?=$noteItem->getContent()?></textarea>
                <div class="container-fluid mt-2">
                    <fieldset class="row">
                        <legend class="sr-only">choose your background color:</legend>
                    <?
                        foreach ($this->config->getBgColors() AS $key => $bgColorItem) {
                            echo '<div class="col-12 col-lg-3 d-flex justify-content-center"><input type="radio" id="'.$bgColorItem['label'].'" name="bgColor" class="text-center" value="'.$key.'"';
                            if ($noteItem->getBg() == $key) {
                                echo ' checked';
                            }
                            echo'><label 
                            for="'.$bgColorItem['label'].'" 
                            class="w-75 m-1 p-1 fs-4 text-center rounded-3 border border-3 border-white '.$bgColorItem['class'].'">
                            '.$bgColorItem['label'].'
                            </label></div>';
                        }
                    ?>
                    </fieldset>
                </div>
                <div class="container-fluid">
                    <div class="row justify-content-between mt-3 mb-3 ps-3 pe-3">
                        <div class="col-4 col-sm-3 col-md-2 col-lg-1 p-0 order-1">
                            <input type="submit" value="Submit" aria-label="create/update your note" class="bg-primary border border-3 rounded-1 text-white w-100">
                        </div>
                        <div class="own-col-1 p-0 order-0">
                            <a href="index.php" class="text-decoration-none d-flex justify-content-start m-auto text-primary" aria-label="return to start page">
                                <i class="fa-solid fa-backward fs-3 align-middle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <?
    }

    public function printFooter():void
    {
        ?>
                <footer class="container-fluid bg-primary pt-3 pb-3 fixed-bottom">
                    <div class="row">
                        <span class="d-flex justify-content-center text-center">© Copyright by Ben<br>Designed by Ben</span>
                    </div>
                </footer>
            </body>
        </html>
        <?
    }
}