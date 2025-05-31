<?php require_once 'sections/header.php';
checkLogin();
$userData = getUserData();
?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">
        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">یادداشت ها</h2>
            <div class="devider"></div>
            <div class="searchbox">
                <?php require_once 'sections/search.php' ?>
            </div>
            <?php require_once 'sections/menu.php' ?>

            <div class="upgrade">
                <a href="#" class=""><i class="fas fa-trophy"></i>خرید نسخه کامل</a>
            </div>
        </div>
        <div class="col-lg-10 col-md-9 content g-0">
            <div class="bg">
                <a class="profile"><i class="fas fa-user"></i>مشاهده پروفایل</a>
                <div class="titles">
                    <h1 class="title"><?php echo $userData['title'] ?> <?php echo getUserDisplayname(); ?></h1>
                    <h2 class="title"><?php echo $userData['subtitle'] ?></h2>
                </div>
            </div>

            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 bg-transparent">
                            <div class="card-body">
                                <div class="notes-list" id="notesList">
                                    <?php
                                    $query = "SELECT n.*, c.name as category_name, c.color as category_color 
                                            FROM notes n 
                                            LEFT JOIN categories c ON n.category_id = c.id 
                                            WHERE n.user_id = ? 
                                            ORDER BY n.created_at DESC";
                                    $stmt = mysqli_prepare($db, $query);
                                    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($note = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <div class="note-item mb-3 p-3 border rounded">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="mb-1"><?php echo htmlspecialchars($note['title']); ?></h5>
                                                        <p class="mb-1"><?php echo htmlspecialchars($note['content']); ?></p>
                                                        <?php if ($note['category_name']) { ?>
                                                            <span class="badge" style="background-color: <?php echo $note['category_color']; ?>">
                                                                <?php echo htmlspecialchars($note['category_name']); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="note-actions">
                                                        <button class="btn btn-sm btn-outline-primary edit-note" 
                                                                data-note-id="<?php echo $note['id']; ?>"
                                                                data-title="<?php echo htmlspecialchars($note['title']); ?>"
                                                                data-content="<?php echo htmlspecialchars($note['content']); ?>"
                                                                data-category="<?php echo $note['category_id']; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger delete-note" 
                                                                data-note-id="<?php echo $note['id']; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo '<p class="text-center">No notes found. Create your first note!</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mycards mx-auto notes">
                <div class="col-lg-9">
                    <div class="box">
                        <h2><i class="fas fa-calendar-day"></i>همه یادداشت ها</h2>
                        <ul class="list">
                            <?php
                            $notes = getUserNotes();
                            foreach ($notes as $note) {
                            ?>
                                <li>
                                    <a href="?done=<?php echo $note['id'] ?>"><i class="fas fa-square-check"></i></a>
                                    <?php if ($note['category_name']) { ?>
                                        <span class="note-category" style="background-color: <?php echo $note['category_color']; ?>">
                                            <?php echo $note['category_name']; ?>
                                        </span>
                                    <?php } ?>
                                    <?php echo $note['note_text'] ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="box">
                        <h2><i class="fas fa-square-check"></i>انجام شده ها</h2>
                        <ul class="list done">
                            <?php
                            $doneNotes = getDoneNotes();
                            foreach ($doneNotes as $doneNote) {
                            ?>
                                <li><a href="?delete=<?php echo $doneNote['id']; ?>"><i class="fas fa-trash"></i></a><?php echo $doneNote['note_text'] ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add/edit/delete note functionality
    const addNoteModal = document.getElementById('addNoteModal');
    const editNoteModal = document.getElementById('editNoteModal');
    
    // Add note form submission
    document.getElementById('addNoteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Your existing add note code
    });

    // Edit note form submission
    document.getElementById('editNoteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Your existing edit note code
    });

    // Delete note functionality
    document.querySelectorAll('.delete-note').forEach(button => {
        button.addEventListener('click', function() {
            // Your existing delete note code
        });
    });
});
</script>

<style>
.note-actions {
    display: flex;
    gap: 0.5rem;
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.note-category {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    margin-right: 0.5rem;
}

.card {
    background: transparent;
}

.card-body {
    padding: 0;
}

.note-item {
    background: transparent;
    border: none !important;
    padding: 0.5rem 0 !important;
}
</style>

<?php require_once 'sections/footer.php'; ?>