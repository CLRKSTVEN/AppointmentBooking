<!DOCTYPE html>
<html lang="en">

<?php include('includes/head.php'); ?>

<body>
    <div id="wrapper">
        <?php include('includes/top-nav-bar.php'); ?>
        <?php include('includes/sidebar.php'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <style>
                        .messages-thread {
                            background: #f7f8fa;
                            overflow-y: auto;
                            flex: 1 1 auto;
                            min-height: 360px;
                            max-height: 65vh;
                        }

                        .messages-composer {
                            position: sticky;
                            bottom: 0;
                            background: #fff;
                            padding-top: 8px;
                            border-top: 1px solid #e1e3e8;
                        }
                    </style>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="page-title-box d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="page-title mb-0">Messages</h4>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#recipientModal">
                                    Choose recipient
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlentities($success); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlentities($error); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box d-flex flex-column h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <?php
                                        $withName = '';
                                        if ($with_profile) {
                                            $withName = trim(
                                                ($with_profile->first_name ?? '') . ' ' .
                                                    ($with_profile->last_name ?? '')
                                            );
                                        }
                                        ?>
                                        <h5 class="mb-0"><?= $withName !== '' ? htmlentities($withName) : 'Select a recipient'; ?></h5>
                                        <div class="text-muted small">
                                            <?= $with_profile ? htmlentities(ucfirst($with_profile->position_title ?? $with_profile->role ?? '')) : ''; ?>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#recipientModal">
                                        <?= $with_profile ? 'Change recipient' : 'Choose recipient'; ?>
                                    </button>
                                </div>

                                <div id="threadPanel" class="messages-thread border rounded p-3 mb-3">
                                    <?php if (!empty($thread)): ?>
                                        <?php foreach ($thread as $msg): ?>
                                            <?php $isMine = ((int)$msg->sender_staff_id === (int)$current_staff_id); ?>
                                            <div class="d-flex <?= $isMine ? 'justify-content-end' : 'justify-content-start'; ?> mb-2">
                                                <div class="p-2 rounded <?= $isMine ? 'bg-primary text-white' : 'bg-white'; ?> shadow-sm" style="max-width: 75%;">
                                                    <div><?= nl2br(htmlentities($msg->body)); ?></div>
                                                    <div class="text-muted small mt-1" style="<?= $isMine ? 'opacity:0.85;' : ''; ?>">
                                                        <?= htmlentities(date('M d, Y h:i a', strtotime($msg->created_at))); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-muted small">No messages yet. Start the conversation.</div>
                                    <?php endif; ?>
                                </div>

                                <form id="messageForm" class="messages-composer" action="<?= site_url('messages/send'); ?>" method="post">
                                    <input type="hidden" name="receiver_staff_id" id="receiver_staff_id" value="<?= (int) $with_id; ?>">
                                    <div class="form-group mb-2">
                                        <label for="body">Message</label>
                                        <textarea id="body" name="body" class="form-control" rows="3" placeholder="Type your message" <?= ($with_id <= 0) ? 'disabled' : ''; ?> required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary" id="sendBtn" <?= ($with_id <= 0) ? 'disabled' : ''; ?>>Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/themecustomizer.php'); ?>
    <?php include('includes/footer_plugins.php'); ?>

    <!-- Recipient Modal -->
    <div class="modal fade" id="recipientModal" tabindex="-1" role="dialog" aria-labelledby="recipientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="recipientModalLabel">Choose a recipient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($recipients)): ?>
                        <div class="form-group">
                            <input type="text" id="modalRecipientSearch" class="form-control" placeholder="Search recipients">
                        </div>
                        <div class="list-group" id="modalRecipientList" style="max-height:320px; overflow-y:auto;">
                            <?php foreach ($recipients as $rec): ?>
                                <?php
                                $name = trim(($rec->first_name ?? '') . ' ' . ($rec->last_name ?? ''));
                                $name = $name !== '' ? $name : 'User #' . ($rec->staff_id ?? '');
                                ?>
                                <a href="#" class="list-group-item list-group-item-action recipient-link"
                                    data-id="<?= (int)$rec->staff_id; ?>"
                                    data-name="<?= htmlentities(strtolower($name)); ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="font-weight-bold"><?= htmlentities($name); ?></div>
                                            <div class="text-muted small"><?= htmlentities(ucfirst($rec->position_title ?? $rec->role ?? '')); ?></div>
                                        </div>
                                        <span class="badge badge-light border">Select</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted small">No available recipients.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
                const receiverInput = document.getElementById('receiver_staff_id');
                const bodyInput = document.getElementById('body');
                const threadPanel = document.getElementById('threadPanel');
                const sendBtn = document.getElementById('sendBtn');
                const currentWith = parseInt(receiverInput.value || '0', 10);
                const modalSearchInput = document.getElementById('modalRecipientSearch');
                const recipientLinks = document.querySelectorAll('.recipient-link');

                // Scroll to bottom on load
                function scrollToBottom() {
                    if (threadPanel) {
                        threadPanel.scrollTop = threadPanel.scrollHeight;
                    }
                }
                scrollToBottom();

                // Handle recipient clicks
                $(document).on('click', '.recipient-link', function(e) {
                    e.preventDefault();
                    const id = parseInt($(this).data('id'), 10) || 0;
                    if (!id) return;
                    window.location = '<?= site_url('messages'); ?>?with=' + id;
                });

                function sendMessage() {
                    if (!receiverInput.value) {
                        return;
                    }
                    const payload = (bodyInput.value || '').trim();
                    if (payload === '' || sendBtn.disabled) {
                        return;
                    }
                    sendBtn.disabled = true;
                    fetch('<?= site_url('messages/send_ajax'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                            },
                            body: new URLSearchParams({
                                receiver_staff_id: receiverInput.value,
                                body: payload
                            }),
                            cache: 'no-store'
                        })
                        .then(resp => resp.json())
                        .then(res => {
                            if (!res || !res.success) throw new Error((res && res.error) || 'Unable to send message.');
                            bodyInput.value = '';
                            appendMessage(res.message, true);
                            fetchThread(true); // immediately sync from server
                            scrollToBottom();
                        })
                        .catch(err => {
                            alert(err.message || 'Unable to send message.');
                        })
                        .finally(() => {
                            sendBtn.disabled = false;
                            bodyInput.focus();
                        });
                }

                // AJAX send
                $('#messageForm').on('submit', function(e) {
                    e.preventDefault();
                    sendMessage();
                });

                // Enter to send (Shift+Enter for newline)
                if (bodyInput) {
                    bodyInput.addEventListener('keydown', function(e) {
                        if ((e.key === 'Enter' || e.keyCode === 13) && !e.shiftKey) {
                            e.preventDefault();
                            sendMessage();
                        }
                    });
                }

                // Polling for new messages
                let polling = false;

                function fetchThread(force) {
                    const withId = parseInt(receiverInput.value || '0', 10);
                    if (!withId) return;
                    if (polling && !force) return;
                    polling = true;
                    fetch('<?= site_url('messages/thread'); ?>?with=' + withId + '&t=' + Date.now(), {
                            cache: 'no-store'
                        })
                        .then(resp => resp.json())
                        .then(res => {
                            if (!res || !res.success) return;
                            renderThread(res.thread || []);
                            scrollToBottom();
                        })
                        .finally(() => {
                            polling = false;
                        });
                }

                function renderThread(items) {
                    if (!threadPanel) return;
                    if (!items.length) {
                        threadPanel.innerHTML = '<div class="text-muted small">No messages yet. Start the conversation.</div>';
                        return;
                    }
                    const me = <?= (int)$current_staff_id; ?>;
                    threadPanel.innerHTML = items.map(function(msg) {
                        const isMine = parseInt(msg.sender_staff_id, 10) === me;
                        const time = msg.created_at ? new Date(msg.created_at.replace(' ', 'T')) : null;
                        const timeText = time ? time.toLocaleString() : '';
                        return `
                        <div class="d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'} mb-2">
                            <div class="p-2 rounded ${isMine ? 'bg-primary text-white' : 'bg-white'}" style="max-width:75%;">
                                <div>${escapeHtml(msg.body || '')}</div>
                                <div class="text-muted small mt-1" style="${isMine ? 'opacity:0.85;' : ''}">${escapeHtml(timeText)}</div>
                            </div>
                        </div>`;
                    }).join('');
                }

                function appendMessage(msg, isMine) {
                    if (!threadPanel || !msg) return;
                    const timeText = msg.created_at ? new Date(msg.created_at.replace(' ', 'T')).toLocaleString() : '';
                    const div = document.createElement('div');
                    div.className = 'd-flex ' + (isMine ? 'justify-content-end' : 'justify-content-start') + ' mb-2';
                    div.innerHTML = `
                    <div class="p-2 rounded ${isMine ? 'bg-primary text-white' : 'bg-white'}" style="max-width:75%;">
                        <div>${escapeHtml(msg.body || '')}</div>
                        <div class="text-muted small mt-1" style="${isMine ? 'opacity:0.85;' : ''}">${escapeHtml(timeText)}</div>
                    </div>`;
                    threadPanel.appendChild(div);
                }

                function escapeHtml(str) {
                    return String(str || '').replace(/[&<>\"']/g, function(c) {
                        const map = {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;'
                        };
                        return map[c] || c;
                    });
                }

                    if (currentWith) {
                        fetchThread(true);
                        setInterval(() => fetchThread(true), 1200); // always force to reduce missed updates
                        window.addEventListener('focus', () => fetchThread(true));
                    }

                    // Filter recipients in modal
                    if (modalSearchInput) {
                        modalSearchInput.addEventListener('input', function(e) {
                            const term = (e.target.value || '').toLowerCase();
                            recipientLinks.forEach(function(link) {
                                const name = link.getAttribute('data-name') || '';
                                link.style.display = name.indexOf(term) !== -1 ? '' : 'none';
                            });
                        });
                    }
                })();
    </script>
</body>

</html>
