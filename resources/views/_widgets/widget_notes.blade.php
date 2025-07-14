<?php
// Sample notes data - in real implementation this would come from database
$notes = [
    [
        'id' => 1,
        'content' => 'Server maintenance scheduled for tomorrow at 3 AM EST',
        'author' => 'Admin',
        'date' => '7/13/2025',
        'created_at' => now()->subDays(1)
    ]
];
?>

<div class="notes-widget h-100">
    <div class="widget-content h-100 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="widget-subtitle mb-0">Quick Notes</h6>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                <i class="fas fa-plus me-1"></i>
                Add Note
            </button>
        </div>

        <div class="notes-list">
            <?php if (!empty($notes)): ?>
                <?php foreach ($notes as $note): ?>
                    <div class="note-item mb-3">
                        <div class="note-content">
                            <?php echo htmlspecialchars($note['content']); ?>
                        </div>
                        <div class="note-meta d-flex justify-content-between align-items-center mt-2">
                            <span class="note-author"><?php echo htmlspecialchars($note['author']); ?> • <?php echo $note['date']; ?></span>
                            <button type="button" class="btn btn-link btn-sm text-danger p-0">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-sticky-note fa-2x mb-2"></i>
                    <p>No notes yet. Add your first note!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">
                    <i class="fas fa-sticky-note me-2"></i>
                    Add New Note
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNoteForm">
                    <div class="mb-3">
                        <label for="noteContent" class="form-label">Note Content</label>
                        <textarea class="form-control" id="noteContent" rows="4" placeholder="Enter your note here..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="noteAuthor" class="form-label">Author</label>
                        <input type="text" class="form-control" id="noteAuthor" value="Admin" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNote">
                    <i class="fas fa-save me-2"></i>
                    Save Note
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.notes-widget {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
}

.widget-subtitle {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
}

.btn-primary {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    border: none;
    border-radius: 6px;
    font-weight: 500;
    padding: 6px 12px;
    font-size: 0.85rem;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e96b00 0%, #fd7e14 100%);
    transform: translateY(-1px);
}

.note-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    border-left: 3px solid #fd7e14;
}

.note-content {
    color: #2c3e50;
    font-size: 0.9rem;
    line-height: 1.4;
}

.note-meta {
    font-size: 0.8rem;
}

.note-author {
    color: #6c757d;
    font-weight: 500;
}

.btn-link {
    color: #dc3545 !important;
    text-decoration: none;
}

.btn-link:hover {
    color: #c82333 !important;
}

.modal-header {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    color: white;
    border-bottom: none;
}

.modal-header .modal-title {
    color: white;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e3e6f0;
    padding: 10px 12px;
}

.form-control:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 6px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveNoteBtn = document.getElementById('saveNote');
    const noteForm = document.getElementById('addNoteForm');
    const modal = new bootstrap.Modal(document.getElementById('addNoteModal'));

    if (saveNoteBtn) {
        saveNoteBtn.addEventListener('click', function() {
            const content = document.getElementById('noteContent').value.trim();
            const author = document.getElementById('noteAuthor').value.trim();

            if (!content) {
                alert('Please enter note content');
                return;
            }

            // In a real implementation, this would save to the database
            console.log('Saving note:', { content, author });
            
            // Show success message
            alert('Note saved successfully!');
            
            // Reset form and close modal
            noteForm.reset();
            document.getElementById('noteAuthor').value = 'Admin';
            modal.hide();
            
            // In real implementation, reload the widget or add the note to the list
        });
    }
});
</script> 