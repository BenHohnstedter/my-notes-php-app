<?php
class Controller
{
    protected NoteRepo $noteRepo;

    protected NoteView $noteView;

    protected Config $config;

    protected Filters $filters;

    protected Pagination $pagination;

    public function __construct()
    {
        $this->config = new Config();
        $this->noteRepo = new NoteRepo($this->connectDB($this->config->setDb()));
        $this->noteView = new NoteView();
        $this->pagination = new Pagination();
        $this->filters = new Filters();
    }

    public function main():void
    {
        $this->noteView->printHeader();

        $this->addNote();

        $this->editNote();

        $this->deleteNote();

        switch ($this->getParam('page')) {
            case 'detail':
                $this->noteView->printNote(
                    $this->noteRepo->findById($this->getParam('noteId')) //find specific id
                );
                break;
            case 'add':
                $this->noteView->printEditNote(new Note());
                break;
            case 'edit':
                $this->noteView->printEditNote(
                    $this->noteRepo->findById($this->getParam('noteId'))
                );
                break;
            default:
                $this->noteRepo->changePinnedState(         //logic for changing pinnedStatement
                    $this->getParam('noteId'),       //get id
                    $this->filters->filterPinnedState($this->getParam('pinned')) //get filtered pinnedState
                );
                $this->noteView->printSearchBar();         //searchBar OUTPUT
                $this->printSortDropdown();
                $this->printNoteList($this->findAll(), $this->getParam('searched')); //noteList OUTPUT
                $this->printPagination();
                break;
        }

        $this->noteView->printFooter();
    }

    public function connectDB($configDb):PDO
    {
        $db = new PDO(
            $configDb['dbn'],
            $configDb['user'],
            $configDb['password']
        );
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $db;
    }

    public function getParam($param):?string
    {
        if (!isset($_GET[$param]) AND !isset($_POST[$param])) {
            return NULL;
        } else {
            if (isset($_POST[$param])) {
            $param = $_POST[$param];
            } elseif (isset($_GET[$param])) {
                $param = $_GET[$param];
            }
            return trim(preg_replace('/[[:^print:]]/', '', htmlspecialchars($param)));
        }
    }

    public function spamProtection():bool
    {
        if (!isset($_SESSION['note_count'])) { //Session
            $_SESSION['note_count'] = 1;
            $_SESSION['cur_time'] = time();
        }

        if (time() - $_SESSION['cur_time'] >= 60) {
            $_SESSION['note_count'] = 1;
            $_SESSION['cur_time'] = time();
        }

        if ($_SESSION['note_count'] >= 5) {
            return false;
        } else {
            $_SESSION['note_count']++;
            return true;
        }
    }

    public function printNoteList($allNotes, $searched):void
    {
        if (!isset($allNotes[0]) AND isset($searched)) {
            $this->noteView->printNoNoteFound($searched);
        }
        $this->noteView->printNoteList($allNotes);
    }

    public function addNote():void
    {
        if (
            $this->getParam('noteId') === NULL
            AND $this->getParam('title') !== NULL
            AND $this->getParam('content') !== NULL
        ) {
            if ($this->getParam('title') !== "" AND $this->getParam('content') !== "") {
                if ($this->spamProtection()) { // Session
                    $this->noteRepo->addNoteRepo(
                        $this->getParam('title'),
                        $this->getParam('content'),
                        $this->filters->filterArrays($this->getParam('bgColor'), $this->config->getBgColors()),
                    );
                } else {
                    $this->noteView->printErrorMessage('TO MANY REQUESTS');
                }
            } else {
                $this->noteView->printErrorMessage('INVALID CHARACTERS');
            }
        }
    }

    public function editNote():void
    {
        if (
            $this->getParam('noteId') !== NULL
            AND $this->getParam('title') !== NULL
            AND $this->getParam('content') !== NULL
        ) {
            if ($this->getParam('title') !== "" AND $this->getParam('content') !== "") {
                $this->noteRepo->editNoteRepo(
                    $this->getParam('noteId'),
                    $this->getParam('title'),
                    $this->getParam('content'),
                    $this->filters->filterArrays($this->getParam('bgColor'), $this->config->getBgColors()),
                );
            } else {
                $this->noteView->printErrorMessage('INVALID CHARACTERS');
            }
        }
    }

    public function deleteNote():void
    {
        if ($this->getParam('page') === 'delete' AND $this->getParam('noteId') !== NULL) {
            $this->noteRepo->deleteNoteRepo($this->getParam('noteId'));
        }
    }
    
    public function stackURLParams($paramURL):?string
    {
        $param = NULL;
        if (isset($_GET[$paramURL])) {
            $param = '&'.$paramURL.'='.$_GET[$paramURL];
        }
        return $param;
    }

    public function printSortDropdown():void
    {
        $this->noteView->printSortDropdown(        //sortDropdown OUTPUT
            $this->filters->filterArrays((int)$this->getParam('sort'), $this->config->getSortings()), //get filtered sortingState
            $this->config->getSortings(),           //get sortingArray
            $this->stackURLParams('page'),
            $this->stackURLParams('searched')
        );
    }

    public function findAll():array
    {
        return $this->noteRepo->findAll(            //building sql statement
            $this->getParam('searched'),     //get searchInput
            $this->config->getSortings()[$this->filters->filterArrays((int)$this->getParam('sort'), $this->config->getSortings())]['sql'], //get sortOrder
            $this->getOffset(
                $this->getParam('page'),
                $this->config->getLimit(),
                $this->countFilteredNotes($this->getParam('searched'))
            ),
            $this->config->getLimit()
        );
    }

    public function getOffset($page, $limit, $maxEntries):int
    {
        if ($page === NULL) {
            $page = 1;
        }

        $page = max(1, min((int)ceil($maxEntries/$limit), $page));

        return ($page-1)*$limit;
    }

    protected function countFilteredNotes($filter): int
    {
        return count($this->noteRepo->findAll($filter));
    }

    public function printPagination():void
    {
        $this->noteView->printPagination(
            $this->pagination->paginationLogic(
                $this->getParam('page'),
                $this->pagination->getMaxPages(
                    $this->config->getLimit(),
                    $this->countFilteredNotes($this->getParam('searched'))
                ),
                $this->filterPagination()
            ),
            $this->stackURLParams('searched'),
            $this->stackURLParams('sort'),
            $this->pagination->getMaxPages(
                $this->config->getLimit(),
                $this->countFilteredNotes($this->getParam('searched'))
            ),
            $this->filterPagination()
        );
    }

    public function filterPagination(): array
    {
        return $this->filters->filterPagination(
            $this->getParam('page'),
            $this->pagination->getMaxPages(
                $this->config->getLimit(),
                $this->countFilteredNotes($this->getParam('searched'))
            )
        );
    }
}