<?php if ($this->session->flashdata('message')): ?>
    <div class="alert alert-<?= $this->session->flashdata('message_type') ?: 'info' ?>">
        <?= $this->session->flashdata('message'); ?>
    </div>
<?php endif; ?>